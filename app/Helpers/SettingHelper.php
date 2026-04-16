<?php

namespace App\Helpers;

use App\Models\Setting;

class SettingHelper
{
    /**
     * Get setting value by key
     */
    public static function get($key, $default = null)
    {
        $setting = Setting::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup($group)
    {
        return Setting::where('group', $group)->get()->keyBy('key');
    }

    /**
     * Get company settings array
     */
    public static function company()
    {
        return self::getByGroup('company');
    }
}
