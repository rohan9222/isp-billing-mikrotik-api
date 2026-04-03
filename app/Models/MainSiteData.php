<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class MainSiteData extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'value',
    ];

    /**
     * Get a specific value by its type name, returning the decoded array or string.
     */
    public static function getValue(string $type, $default = null)
    {
        $record = self::where('type', $type)->first();

        if (! $record) {
            return $default;
        }

        // Return array if JSON, otherwise string
        $raw = $record->getRawOriginal('value');
        $decoded = @json_decode($raw, true);

        return (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : $record->value;
    }

    /**
     * Update or create a specific key-value record
     */
    public static function setValue(string $type, $value): self
    {
        // Encode to JSON if array
        if (is_array($value)) {
            $value = json_encode($value);
        }

        return self::updateOrCreate(
            ['type' => $type],
            ['value' => $value]
        );
    }

    /**
     * Get all active data and return it as an object
     * so that existing Blade templates ($siteData->hero_title) don't break.
     */
    public static function getActive()
    {
        return Cache::rememberForever('main_site_data_active', function () {
            $data = new \stdClass;
            $records = self::all();

            foreach ($records as $record) {
                // Return array if JSON, otherwise string
                $raw = $record->getRawOriginal('value');
                $decoded = @json_decode($raw, true);
                $value = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : $record->value;
                $key = $record->type;
                $data->$key = $value;
            }

            // Defaults in case they don't exist in DB
            $data->is_active = $data->is_active ?? true;

            return $data;
        });
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::flush());
        static::updated(fn () => Cache::flush());
    }
}
