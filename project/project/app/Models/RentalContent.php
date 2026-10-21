<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalContent extends Model
{
    protected $table = 'rental_content';
    protected $fillable = ['kind', 'slug', 'title', 'body', 'url', 'published', 'position'];
    protected $casts = ['published' => 'boolean'];

    public static function visibleFor(RentalPanel $panel, array $kinds)
    {
        return static::where('panel_id', $panel->id)->where('published', true)
            ->whereIn('kind', $kinds)->orderBy('position')->orderBy('id');
    }
}
