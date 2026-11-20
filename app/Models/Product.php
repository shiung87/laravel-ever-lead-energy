<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_brand_id',
        'battery_type_id',
        'name',
        'slug',
        'model_number',
        'sku',
        'image',
        'gallery',
        'capacity_ah',
        'cca',
        'voltage',
        'dimensions',
        'terminal_type',
        'warranty_months',
        'warranty_label',
        'price_range',
        'short_description',
        'description',
        'features',
        'suitable_vehicles_text',
        'is_featured',
        'status',
        'meta_title',
        'meta_description',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'features' => 'array',
            'is_featured' => 'boolean',
        ];
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(ProductBrand::class, 'product_brand_id');
    }

    public function batteryType(): BelongsTo
    {
        return $this->belongsTo(BatteryType::class);
    }

    public function compatibilities(): HasMany
    {
        return $this->hasMany(VehicleCompatibility::class);
    }

    public function promotions(): BelongsToMany
    {
        return $this->belongsToMany(Promotion::class, 'promotion_products');
    }

    public function leads(): HasMany
    {
        return $this->hasMany(CustomerLead::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true)->active()->orderBy('sort_order');
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        if ($this->image && file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }

        return asset('images/battery-placeholder.svg');
    }

    public function getWhatsappTextAttribute(): string
    {
        return "Hello Everlead Energy, I would like to enquire about the {$this->name} (Model: {$this->model_number}, {$this->capacity_ah}Ah / {$this->cca} CCA).";
    }
}
