<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Property extends Model
{
    protected $table = 'properties';

    protected $fillable = [
        'category_id',
        'title', 'title_sr', 'title_ru',
        'slug',
        'price', 'area', 'rooms', 'floor',
        'city', 'address',
        'lat', 'lng',
        'description', 'description_sr', 'description_ru',
        'images',
        'is_published',
    ];

    protected $casts = [
        'images'       => 'array',
        'is_published' => 'boolean',
        'price'        => 'decimal:2',
        'lat'          => 'float',
        'lng'          => 'float',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id');
    }

    protected static function booted(): void
    {
        // Auto-slug samo ako je prazan
        static::saving(function (Property $p) {
            if (blank($p->slug) && filled($p->title)) {
                $p->slug = Str::slug($p->title);
            }
        });
    }

    // Lokalizovani accessor-i (ručni prevod: *_sr / *_ru)
    public function getTitleLocalizedAttribute(): string
    {
        return match (app()->getLocale()) {
            'sr' => $this->title_sr ?: ($this->title ?? ''),
            'ru' => $this->title_ru ?: ($this->title ?? ''),
            default => $this->title ?? '',
        };
    }

    public function getDescriptionLocalizedAttribute(): string
    {
        return match (app()->getLocale()) {
            'sr' => $this->description_sr ?: ($this->description ?? ''),
            'ru' => $this->description_ru ?: ($this->description ?? ''),
            default => $this->description ?? '',
        };
    }
}
