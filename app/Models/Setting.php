<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value'
    ];

    public $timestamps = false;

    public static function get(string $key)
    {
        $setting = Setting::where('key', $key)->first();
        return $setting->value;
    }

    public static function set(string $key, string $value)
    {
        $setting = Setting::where('key', $key)->first();
        $setting->value = $value;
        $setting->save();
    }
}
