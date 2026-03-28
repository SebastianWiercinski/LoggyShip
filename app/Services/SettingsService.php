<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class SettingsService
{
    public function get(string $group, string $key, mixed $default = null): mixed
    {
        $cacheKey = "settings.{$group}.{$key}";

        return Cache::remember($cacheKey, 300, function () use ($group, $key, $default) {
            $setting = Setting::where('group', $group)->where('key', $key)->first();

            if (! $setting) {
                return $default;
            }

            if ($setting->encrypted) {
                return Crypt::decryptString($setting->value);
            }

            return $setting->value;
        });
    }

    public function set(string $group, string $key, mixed $value, bool $encrypted = false): void
    {
        $storeValue = $encrypted && $value !== null
            ? Crypt::encryptString($value)
            : $value;

        Setting::updateOrCreate(
            ['group' => $group, 'key' => $key],
            ['value' => $storeValue, 'encrypted' => $encrypted]
        );

        Cache::forget("settings.{$group}.{$key}");
    }

    public function getGroup(string $group): array
    {
        return Setting::where('group', $group)
            ->get()
            ->mapWithKeys(function (Setting $setting) {
                $value = $setting->encrypted
                    ? Crypt::decryptString($setting->value)
                    : $setting->value;

                return [$setting->key => $value];
            })
            ->toArray();
    }

    public function delete(string $group, string $key): void
    {
        Setting::where('group', $group)->where('key', $key)->delete();
        Cache::forget("settings.{$group}.{$key}");
    }
}
