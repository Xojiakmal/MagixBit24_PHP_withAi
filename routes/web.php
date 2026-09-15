<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\ProfileSettings;

$domain = env('APP_DOMAIN', 'magixbit24.com');

$useSubdomains = env('USE_SUBDOMAINS', false);

// ==========================================
// LOCALE SWITCHER
// ==========================================
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['uz', 'en'])) {
        session()->put('locale', $locale);
        if (auth()->check()) {
            auth()->user()->update(['locale' => $locale]);
        }
    }
    return redirect()->back();
})->name('lang.switch');

// ==========================================
// ADMIN ROUTES
// ==========================================
$adminGroup = function () {
    Route::get('/', function () {
        return redirect()->route('admin.login');
    })->name('admin.home');
    
    Route::get('/login', [App\Http\Controllers\Auth\BotLoginController::class, 'show'])->name('admin.login');
    
    // Local Auth Routes for SuperAdmin 2FA (must be logged in via Telegram first)
    Route::middleware(['auth'])->group(function () {
        Route::get('/auth/local', [App\Http\Controllers\AdminAuthController::class, 'show'])->name('admin.local.login');
        Route::post('/auth/local', [App\Http\Controllers\AdminAuthController::class, 'authenticate'])->name('admin.local.authenticate');
    });
    
    Route::middleware(['auth', 'admin.2fa'])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\SuperAdminController::class, 'index'])->name('superadmin.index');
        Route::post('/approve/{id}', [App\Http\Controllers\SuperAdminController::class, 'approveCompany'])->name('superadmin.approve');
        Route::post('/reject/{id}', [App\Http\Controllers\SuperAdminController::class, 'rejectCompany'])->name('superadmin.reject');
    });
};

if ($useSubdomains) {
    Route::domain('admin.' . $domain)->group($adminGroup);
} else {
    Route::prefix('admin')->group($adminGroup);
}

// ==========================================
// COMPANY ROUTES
// ==========================================
$companyGroup = function () {
    Route::get('/', function () {
        return redirect()->route('company.login');
    });

    Route::get('/login', [App\Http\Controllers\Auth\BotLoginController::class, 'show'])->name('company.login');

    Route::get('/dashboard', function() {
        return redirect()->route('company.create.form');
    });

    Route::middleware(['auth'])->group(function () {
        // Agar foydalanuvchi allaqachon tasdiqlangan kompaniyaga ega bo'lsa, asosiy saytga yo'naltiriladi
        Route::get('/create', function() {
            if (auth()->user()->current_tenant_id) {
                if (!env('USE_SUBDOMAINS', false)) {
                    return redirect()->route('dashboard');
                }
                return redirect()->to('http://' . env('APP_DOMAIN', 'magixbit24.com') . '/dashboard');
            }
            return app()->call('App\Http\Controllers\CompanyController@select');
        })->name('company.create.form');
        
        Route::post('/create', [App\Http\Controllers\CompanyController::class, 'create'])->name('company.create');
        Route::get('/pending', [App\Http\Controllers\CompanyController::class, 'pending'])->name('company.pending');
    });
};

if ($useSubdomains) {
    Route::domain('company.' . $domain)->group($companyGroup);
} else {
    Route::prefix('company')->group($companyGroup);
}

// ==========================================
// MAIN ROUTES
// ==========================================
$mainGroup = function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });

    Route::get('/seed-permissions', function () {
        $permissions = [
            'view_dashboard', 'view_crm', 'create_deal', 'comment_deal', 'assign_deal',
            'view_tasks', 'create_task', 'edit_task', 'delete_task',
            'view_storage', 'manage_storage', 'view_employees', 'manage_employees', 'view_contacts', 'manage_contacts'
        ];
        foreach ($permissions as $p) {
            \Spatie\Permission\Models\Permission::findOrCreate($p, 'web');
        }
        $admin = \Spatie\Permission\Models\Role::findOrCreate('Admin', 'web');
        $admin->givePermissionTo($permissions);
        return 'Permissions seeded.';
    });

    Route::middleware(['auth', 'company'])->group(function () {
        Route::get('/dashboard', function () {
            $user = auth()->user();
            $hasDashboardAccess = $user->hasRole('Admin') || $user->can('view_dashboard');

            if (!$hasDashboardAccess) {
                if ($user->can('view_crm')) {
                    return redirect()->route('crm.deals');
                } elseif ($user->can('view_tasks')) {
                    return redirect()->route('projects.tasks');
                } elseif ($user->can('view_storage')) {
                    return redirect()->route('storage.index');
                } elseif ($user->can('view_employees')) {
                    return redirect()->route('roles.index');
                }
            }

            $timeFilter = request('time', 'month');

            $startDate = match($timeFilter) {
                'day' => now()->startOfDay(),
                'week' => now()->startOfWeek(),
                'month' => now()->startOfMonth(),
                'year' => now()->startOfYear(),
                default => now()->startOfMonth(),
            };

            $dealsQuery = \App\Models\Deal::where('created_at', '>=', $startDate);

            $closedRevenue = (clone $dealsQuery)->whereHas('stage', function($q) {
                $q->where('name', 'Closed');
            })->sum('amount');

            $inProgressRevenue = (clone $dealsQuery)->whereHas('stage', function($q) {
                $q->whereNotIn('name', ['Closed', 'Cancelled']);
            })->sum('amount');

            $inProgressCount = (clone $dealsQuery)->whereHas('stage', function($q) {
                $q->whereNotIn('name', ['Closed', 'Cancelled']);
            })->count();

            $totalDeals = (clone $dealsQuery)->count();

            $recentClients = \App\Models\Contact::where('created_at', '>=', $startDate)
                ->withCount('deals')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            // Chart Data Generation (Running balance of In-Progress Deals)
            $deals = \App\Models\Deal::select('id', 'amount', 'created_at', 'updated_at', 'pipeline_stage_id')
                         ->with('stage')
                         ->get();

            $labels = [];
            $data = [];

            if ($timeFilter === 'day') {
                for ($i = 0; $i <= 23; $i++) {
                    $timestamp = now()->startOfDay()->addHours($i)->endOfHour();
                    $labels[] = sprintf("%02d:00", $i);
                    
                    if ($timestamp > now()->endOfHour()) {
                        $data[] = null;
                        continue;
                    }
                    
                    $total = 0;
                    foreach ($deals as $deal) {
                        if ($deal->created_at <= $timestamp) {
                            $isClosedOrCancelled = in_array(optional($deal->stage)->name, ['Closed', 'Cancelled']);
                            if (! ($isClosedOrCancelled && $deal->updated_at <= $timestamp) ) {
                                $total += $deal->amount;
                            }
                        }
                    }
                    $data[] = $total;
                }
            } elseif ($timeFilter === 'week' || $timeFilter === 'month') {
                $days = $startDate->diffInDays(now()->endOfMonth()); // To show all labels for the month
                if ($timeFilter === 'week') $days = 6;
                
                for ($i = 0; $i <= $days; $i++) {
                    $dateObj = $startDate->copy()->addDays($i);
                    $timestamp = $dateObj->copy()->endOfDay();
                    $labels[] = $dateObj->format('d-M');
                    
                    if ($timestamp > now()->endOfDay()) {
                        $data[] = null;
                        continue;
                    }

                    $total = 0;
                    foreach ($deals as $deal) {
                        if ($deal->created_at <= $timestamp) {
                            $isClosedOrCancelled = in_array(optional($deal->stage)->name, ['Closed', 'Cancelled']);
                            if (! ($isClosedOrCancelled && $deal->updated_at <= $timestamp) ) {
                                $total += $deal->amount;
                            }
                        }
                    }
                    $data[] = $total;
                }
            } elseif ($timeFilter === 'year') {
                for ($i = 1; $i <= 12; $i++) {
                    $dateObj = \Carbon\Carbon::create(null, $i, 1);
                    $timestamp = $dateObj->copy()->endOfMonth();
                    $labels[] = $dateObj->format('M');
                    
                    if ($dateObj->startOfMonth() > now()->startOfMonth()) {
                        $data[] = null;
                        continue;
                    }

                    $total = 0;
                    foreach ($deals as $deal) {
                        if ($deal->created_at <= $timestamp) {
                            $isClosedOrCancelled = in_array(optional($deal->stage)->name, ['Closed', 'Cancelled']);
                            if (! ($isClosedOrCancelled && $deal->updated_at <= $timestamp) ) {
                                $total += $deal->amount;
                            }
                        }
                    }
                    $data[] = $total;
                }
            }

            $chartData = [
                'labels' => $labels,
                'data' => $data,
            ];

            return view('dashboard', compact(
                'timeFilter', 'closedRevenue', 'inProgressRevenue', 
                'inProgressCount', 'totalDeals', 'recentClients', 'chartData'
            ));
        })->name('dashboard');

        Route::get('/profile', App\Livewire\ProfileSettings::class)->name('profile');
        Route::get('/contacts', \App\Livewire\ContactManager::class)->name('contacts')->middleware('can:view_contacts');
        Route::get('/employees', \App\Livewire\EmployeeManager::class)->name('employees')->middleware('can:view_employees');
        Route::get('/company/employees', App\Livewire\EmployeeManager::class)->name('roles.index')->middleware('can:view_employees');
        Route::get('/crm/deals', App\Livewire\DealKanbanBoard::class)->name('crm.deals')->middleware('can:view_crm');
        Route::get('/projects/tasks', App\Livewire\TaskKanbanBoard::class)->name('projects.tasks')->middleware('can:view_tasks');
        
        Route::get('/company/settings', \App\Livewire\CompanySettings::class)->name('company.settings');
        
        Route::get('/storage', \App\Livewire\StorageManager::class)->name('storage.index')->middleware('can:view_storage');
        Route::post('/storage/settings', [App\Http\Controllers\StorageController::class, 'saveSettings'])->name('storage.settings')->middleware('can:view_storage');
        Route::post('/storage/upload', [App\Http\Controllers\StorageController::class, 'upload'])->name('storage.upload')->middleware('can:view_storage');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/company/select', function() {
            if (auth()->user()->current_tenant_id) {
                return redirect()->route('dashboard');
            }
            return app()->call('App\Http\Controllers\CompanyController@select');
        })->name('company.select');
        
        Route::post('/company/join', [App\Http\Controllers\CompanyController::class, 'join'])->name('company.join');
    });
};

if ($useSubdomains) {
    Route::domain($domain)->group($mainGroup);
} else {
    Route::middleware([])->group($mainGroup);
}

require __DIR__.'/auth.php';
Route::post('/telegram/webhook', [App\Http\Controllers\TelegramWebhookController::class, 'handle']);
