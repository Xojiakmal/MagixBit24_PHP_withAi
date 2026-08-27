<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\TenantUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public function select()
    {
        $mode = session('registration_mode');
        
        // Agar xodim sifatida qo'shilish niyatida kirgan bo'lsa va create ga o'tishga urinsa:
        if (request()->routeIs('company.create.form') && $mode === 'join') {
            return redirect()->route('company.select')->withErrors(['name' => 'Kompaniya yaratish uchun maxsus manzildan kirmagansiz.']);
        }
        
        // Agar kompaniya yaratish niyatida kirgan bo'lsa va select ga o'tishga urinsa:
        if (request()->routeIs('company.select') && $mode === 'create') {
            return redirect()->route('company.create.form')->withErrors(['unique_link' => 'Siz faqat kompaniya yaratishingiz mumkin.']);
        }
        
        $companyExists = Tenant::count() > 0;
        return view('company.select', compact('companyExists'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'creator_name' => 'required|string|max:255',
            'gmail' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
        ]);

        $uniqueLink = Str::slug($request->name) . '-' . Str::random(6);

        $tenant = Tenant::create([
            'name' => $request->name,
            'unique_link' => $uniqueLink,
            'owner_id' => Auth::id(),
            'status' => 'pending', // Pending approval from superadmin
        ]);

        // Save additional creator info if needed (for example to User model if not set)
        $user = \App\Models\User::find(Auth::id());
        if (!$user->phone) {
            $user->phone = $request->phone;
            $user->save();
        }

        TenantUser::create([
            'tenant_id' => $tenant->id,
            'user_id' => Auth::id(),
            'status' => 'pending',
        ]);

        return redirect()->route('company.pending')->with('success', 'So\'rovingiz qabul qilindi. Tasdiqlanishini kuting.');
    }

    public function join(Request $request)
    {
        $request->validate([
            'unique_link' => 'required|string',
        ]);

        $tenant = Tenant::where('unique_link', $request->unique_link)->first();

        if (!$tenant) {
            return back()->withErrors(['unique_link' => 'Bunday havola mavjud emas.']);
        }

        if ($tenant->status !== 'active') {
            return back()->withErrors(['unique_link' => 'Kompaniya hozircha faol emas.']);
        }

        // Check if already requested
        $existing = TenantUser::where('tenant_id', $tenant->id)->where('user_id', Auth::id())->first();
        if ($existing) {
            return back()->withErrors(['unique_link' => 'Siz allaqachon so\'rov yuborgansiz yoki a\'zosiz.']);
        }

        TenantUser::create([
            'tenant_id' => $tenant->id,
            'user_id' => Auth::id(),
            'status' => 'approved', // Auto-approved via invite link
        ]);

        $user = \App\Models\User::find(Auth::id());
        $user->update(['current_tenant_id' => $tenant->id]);
        $user->assignRole('Xodim');

        return redirect()->route('dashboard')->with('success', 'Kompaniyaga muvaffaqiyatli qo\'shildingiz.');
    }

    public function pending()
    {
        return view('company.pending');
    }
}
