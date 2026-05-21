<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\AdminBankAccount;
use App\Models\Bank;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display company profile settings
     */
    public function company()
    {
        $settings = Setting::where('group', 'company')->get()->keyBy('key');
        return view('admin.settings.company', compact('settings'));
    }

    /**
     * Update company profile settings
     */
    public function updateCompany(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_name_short' => 'required|string|max:255',
            'company_description' => 'required|string',
            'company_email' => 'required|email',
            'company_phone' => 'required|string|max:20',
            'company_address' => 'required|string',
            'company_mission' => 'nullable|string',
            'company_vision' => 'nullable|string',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'company_hero_title' => 'nullable|string|max:255',
            'company_hero_subtitle' => 'nullable|string|max:500',
            'company_hero_badge' => 'nullable|string|max:255',
            'about_heading' => 'nullable|string|max:255',
            'about_text' => 'nullable|string',
            'company_footer_text' => 'nullable|string',
            'company_whatsapp' => 'nullable|string|max:20',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'social_facebook' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_youtube' => 'nullable|url',
        ]);

        // Handle logo upload
        if ($request->hasFile('company_logo')) {
            $file = $request->file('company_logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/company'), $filename);
            $validated['company_logo'] = 'uploads/company/' . $filename;
        }

        // Save all settings
        foreach ($validated as $key => $value) {
            // Don't overwrite logo if no new file uploaded
            if ($key === 'company_logo' && is_null($value)) {
                continue;
            }

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'company']
            );
        }

        return redirect()->back()->with('success', 'Pengaturan perusahaan berhasil diperbarui!');
    }

    /**
     * Display payment settings
     */
    public function payment()
    {
        $settings = Setting::where('group', 'payment')->get()->keyBy('key');
        $adminBanks = AdminBankAccount::latest()->get();
        $masterBanks = Bank::where('is_active', true)->orderBy('nama_bank')->get();
        return view('admin.settings.payment', compact('settings', 'adminBanks', 'masterBanks'));
    }

    /**
     * Update payment settings
     */
    public function updatePayment(Request $request)
    {
        $validated = $request->validate([
            'payment_bank_name' => 'nullable|string|max:255',
            'payment_bank_account' => 'nullable|string|max:255',
            'payment_bank_holder' => 'nullable|string|max:255',
            'payment_qris_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'payment_manual_enabled' => 'nullable|in:0,1',
            'payment_midtrans_enabled' => 'nullable|in:0,1',
            'midtrans_server_key' => 'nullable|string|max:255',
            'midtrans_client_key' => 'nullable|string|max:255',
            'midtrans_is_production' => 'nullable|string|in:0,1',
        ]);

        if ($request->hasFile('payment_qris_image')) {
            $file = $request->file('payment_qris_image');
            $filename = 'qris_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/payment'), $filename);
            $validated['payment_qris_image'] = 'uploads/payment/' . $filename;
        }

        foreach ($validated as $key => $value) {
            // Don't overwrite image if no new file uploaded
            if ($key === 'payment_qris_image' && is_null($value)) {
                continue;
            }

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'payment']
            );
        }

        return redirect()->back()->with('success', 'Pengaturan pembayaran berhasil diperbarui!');
    }

    public function addAdminBank(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_holder' => 'required|string|max:255',
        ]);

        AdminBankAccount::create($validated);

        return redirect()->back()->with('success', 'Rekening bank berhasil ditambahkan!');
    }

    public function deleteAdminBank(AdminBankAccount $bank)
    {
        $bank->delete();
        return redirect()->back()->with('success', 'Rekening bank berhasil dihapus!');
    }

    /**
     * Display delivery settings
     */
    public function delivery()
    {
        $settings = Setting::where('group', 'delivery')->get()->keyBy('key');
        return view('admin.settings.delivery', compact('settings'));
    }

    /**
     * Update delivery settings
     */
    public function updateDelivery(Request $request)
    {
        $validated = $request->validate([
            'delivery_enabled' => 'required|string',
            'delivery_fee_type' => 'required|in:flat,distance',
            'delivery_fee_flat' => 'nullable|numeric|min:0',
            'delivery_fee_per_km' => 'nullable|numeric|min:0',
            'delivery_min_distance' => 'nullable|numeric|min:0',
        ]);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'delivery']
            );
        }

        return redirect()->back()->with('success', 'Pengaturan pengiriman berhasil diperbarui!');
    }

    /**
     * Get setting value by key
     */
    public static function get($key, $default = null)
    {
        $setting = Setting::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}
