<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'subtitle',
        'badge_text',
        'promo_type',
        'discount_label',
        'description',
        'terms_conditions',
        'banner_image',
        'starts_at',
        'ends_at',
        'cta_text',
        'cta_url',
        'is_featured',
        'is_active',
        'meta_title',
        'meta_description',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'promotion_products');
    }

    public function leads(): HasMany
    {
        return $this->hasMany(CustomerLead::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        $now = now();

        return $query->where('is_active', true)
            ->where(function (Builder $q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function (Builder $q) use ($now) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->orderBy('sort_order')
            ->orderByDesc('is_featured');
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->ends_at !== null && $this->ends_at->isPast();
    }

    public function getBannerImageUrlAttribute(): string
    {
        if ($this->banner_image && str_starts_with($this->banner_image, 'http')) {
            return $this->banner_image;
        }

        if ($this->banner_image && file_exists(public_path('storage/' . $this->banner_image))) {
            return asset('storage/' . $this->banner_image);
        }

        return asset('images/promo-placeholder.svg');
    }

    public function getWhatsappTextAttribute(): string
    {
        return "Hello Everlead Energy, I am interested in the promotion: \"{$this->title}\" ({$this->discount_label}). Please share more details.";
    }
}
