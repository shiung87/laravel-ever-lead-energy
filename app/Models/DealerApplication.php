<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DealerApplication extends Model
{
    use SoftDeletes;

    public const STATUSES = [
        'new' => 'New',
        'under_review' => 'Under Review',
        'contacted' => 'Contacted',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ];

    protected $fillable = [
        'reference_number',
        'business_name',
        'registration_number',
        'contact_person',
        'mobile_number',
        'email',
        'business_address',
        'state',
        'city',
        'business_type',
        'years_in_operation',
        'existing_brands',
        'estimated_monthly_volume',
        'number_of_outlets',
        'partnership_type',
        'remarks',
        'consent',
        'status',
        'assigned_to',
        'follow_up_date',
        'internal_notes',
        'reviewed_by',
        'reviewed_at',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'landing_page',
    ];

    protected function casts(): array
    {
        return [
            'consent' => 'boolean',
            'follow_up_date' => 'date',
            'reviewed_at' => 'datetime',
        ];
    }

    public function documents(): HasMany
    {
        return $this->hasMany(DealerDocument::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(DealerActivity::class)->latest();
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'new' => 'bg-primary',
            'under_review' => 'bg-warning text-dark',
            'contacted' => 'bg-info text-dark',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst(str_replace('_', ' ', $this->status));
    }

    public static function generateReference(): string
    {
        return 'DLR-' . now()->format('ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
    }
}
