<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class WebsiteSetting extends Model
{
    protected $fillable = ['group', 'key', 'value', 'type', 'label'];

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $settings = Cache::remember('website_settings_map', 3600, function () {
            return static::query()->pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }

    public static function setValue(string $key, mixed $value, string $group = 'general', string $type = 'text', ?string $label = null): self
    {
        $record = static::updateOrCreate(
            ['key' => $key],
            array_filter([
                'group' => $group,
                'value' => is_array($value) ? json_encode($value) : $value,
                'type' => $type,
                'label' => $label,
            ], fn ($v) => $v !== null)
        );

        Cache::forget('website_settings_map');

        return $record;
    }

    public static function clearCache(): void
    {
        Cache::forget('website_settings_map');
    }
}
