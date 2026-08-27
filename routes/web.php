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
            'view_storage', 'manage_storage', 'manage_employees'
        ];
        foreach ($permissions as $p) {
            \Spatie\Permission\Models\Permission::findOrCreate($p, 'web');
        }
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
                } elseif ($user->can('manage_employees')) {
                    return redirect()->route('roles.index');
                }
            }

            $totalRevenue = \App\Models\Deal::sum('amount');
            $completedTasks = \App\Models\Task::where('status', 'done')->count();
            $newClients = \App\Models\Contact::count();

            return view('dashboard', compact('totalRevenue', 'completedTasks', 'newClients'));
        })->name('dashboard');

        Route::get('/profile', App\Livewire\ProfileSettings::class)->name('profile');
        Route::get('/contacts', \App\Livewire\ContactManager::class)->name('contacts');
        Route::get('/employees', \App\Livewire\EmployeeManager::class)->name('employees')->middleware('can:manage_employees');
        Route::get('/company/employees', App\Livewire\EmployeeManager::class)->name('roles.index')->middleware('can:manage_employees');
        Route::get('/crm/deals', App\Livewire\DealKanbanBoard::class)->name('crm.deals')->middleware('can:view_crm');
        Route::get('/projects/tasks', App\Livewire\TaskKanbanBoard::class)->name('projects.tasks')->middleware('can:view_tasks');
        
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
