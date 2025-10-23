<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name', 'name_sr', 'name_ru',
        'slug',
        'type',
        'description', 'description_sr', 'description_ru',
    ];

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'category_id');
    }

    protected static function booted(): void
    {
        static::saving(function (Category $cat) {
            if (blank($cat->slug) && filled($cat->name)) {
                $cat->slug = Str::slug($cat->name);
            }
        });
    }

    public function getNameLocalizedAttribute(): string
    {
        return match (app()->getLocale()) {
            'sr' => $this->name_sr ?: ($this->name ?? ''),
            'ru' => $this->name_ru ?: ($this->name ?? ''),
            default => $this->name ?? '',
        };
    }

    public function getDescriptionLocalizedAttribute(): ?string
    {
        return match (app()->getLocale()) {
            'sr' => $this->description_sr ?: ($this->description ?? null),
            'ru' => $this->description_ru ?: ($this->description ?? null),
            default => $this->description ?? null,
        };
    }
}
