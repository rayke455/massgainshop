<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeColor extends Model
{
    use HasFactory;

    protected $fillable = [
        'light_green_primary_color',
        'light_green_secondary_color',
        'light_green_hero_color',
        'dark_violet_primary_color',
        'dark_violet_secondary_color',
        'minimal_primary_color',
        'minimal_secondary_color',
        'minimal_sub_heading_color',
        'minimal_bg_left_color',
        'minimal_bg_right_color',
        'minimal_button_left_color',
        'minimal_bg_left_two_color',
        'minimal_copy_right_bg_color',
        'deep_blue_primary_color',
        'deep_blue_secondary_color',
        'dark_mode_primary_color',
        'dark_mode_secondary_color',
        'light_orange_primary_color',
        'light_orange_theme_light_color',
        'light_orange_secondary_color',
    ];

    protected static function boot()
    {
        parent::boot();
        static::saved(function () {
            \Cache::forget('themeColorSet');
        });
    }
}
