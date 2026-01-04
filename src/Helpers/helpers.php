<?php

use LWSoftBD\LwSettings\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (! function_exists('setting')) {

    /**
     * Get setting value only (backward compatible)
     */
    function setting(string $key, $default = null)
    {
        $setting = setting_row($key);

        if (! $setting) {
            return $default;
        }

        return setting_cast($setting);
    }
}

if (! function_exists('setting_row')) {

    /**
     * Get full setting row (id, key, value, type, group)
     */
    function setting_row(string $key)
    {
        if (! config('site-settings.cache', true)) {
            return Setting::where('key', $key)->first();
        }

        // Optional: Tag-based cache (Redis / Memcached) for safety
        if (Cache::supportsTags()) {
            return Cache::tags('site_settings')->rememberForever(
                'site_setting_row_' . $key,
                fn () => Setting::where('key', $key)->first()
            );
        }

        // Default cache for all drivers
        return Cache::rememberForever(
            'site_setting_row_' . $key,
            fn () => Setting::where('key', $key)->first()
        );
    }
}

if (! function_exists('setting_cast')) {

    /**
     * Cast value based on type
     */
    function setting_cast(Setting $setting)
    {
        return match ($setting->type) {
            'boolean' => (bool) $setting->value,
            'json'    => json_decode($setting->value, true),
            'number'  => is_numeric($setting->value) ? $setting->value + 0 : null,
            default   => $setting->value,
        };
    }
}

if (! function_exists('setting_forget')) {

    /**
     * Clear single setting cache
     */
    function setting_forget(string $key): void
    {
        if (Cache::supportsTags()) {
            Cache::tags('site_settings')->forget("site_setting_row_{$key}");
        } else {
            Cache::forget("site_setting_{$key}");
            Cache::forget("site_setting_row_{$key}");
        }
    }
}

if (! function_exists('setting_forget_all')) {

    /**
     * Clear all settings cache safely
     */
    function setting_forget_all(): void
    {
        if (Cache::supportsTags()) {
            Cache::tags('site_settings')->flush();
            return;
        }

        // File / DB cache driver fallback
        $keys = Setting::pluck('key');
        foreach ($keys as $key) {
            Cache::forget("site_setting_{$key}");
            Cache::forget("site_setting_row_{$key}");
        }
    }
}