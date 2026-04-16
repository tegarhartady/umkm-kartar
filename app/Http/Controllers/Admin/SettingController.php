<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
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
            'company_description' => 'required|string',
            'company_email' => 'required|email',
            'company_phone' => 'required|string|max:20',
            'company_address' => 'required|string',
            'company_mission' => 'nullable|string',
            'company_vision' => 'nullable|string',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'company_hero_title' => 'nullable|string|max:255',
            'company_hero_subtitle' => 'nullable|string|max:500',
            'company_footer_text' => 'nullable|string',
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
            Setting::updateOrCreate(
                ['key' => $key, 'group' => 'company'],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Pengaturan perusahaan berhasil diperbarui!');
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
