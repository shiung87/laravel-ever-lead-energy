<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleCompatibility extends Model
{
    protected $fillable = [
        'vehicle_model_id',
        'product_id',
        'year_from',
        'year_to',
        'engine_capacity',
        'fuel_type',
        'notes',
        'is_recommended',
    ];

    protected function casts(): array
    {
        return ['is_recommended' => 'boolean'];
    }

    public function vehicleModel(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
