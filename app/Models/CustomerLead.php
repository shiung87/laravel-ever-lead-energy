<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerLead extends Model
{
    use SoftDeletes;

    public const STATUSES = [
        'new' => 'New',
        'contacted' => 'Contacted',
        'follow_up' => 'Follow-up',
        'quotation_sent' => 'Quotation Sent',
        'converted' => 'Converted',
        'lost' => 'Lost',
    ];

    public const ENQUIRY_TYPES = [
        'product_enquiry' => 'Product Enquiry',
        'battery_replacement' => 'Battery Replacement',
        'price_enquiry' => 'Price Enquiry',
        'warranty_enquiry' => 'Warranty Enquiry',
        'general_enquiry' => 'General Enquiry',
    ];

    protected $fillable = [
        'reference_number',
        'full_name',
        'mobile_number',
        'email',
        'state',
        'city',
        'vehicle_brand',
        'vehicle_model',
        'vehicle_year',
        'battery_requirement',
        'product_id',
        'promotion_id',
        'preferred_service_location',
        'enquiry_type',
        'remarks',
        'marketing_consent',
        'status',
        'assigned_to',
        'follow_up_date',
        'internal_notes',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_content',
        'referrer',
        'landing_page',
        'first_touch_source',
        'latest_touch_source',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'marketing_consent' => 'boolean',
            'follow_up_date' => 'date',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(LeadActivity::class)->latest();
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(LeadAssignment::class)->latest();
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'new' => 'bg-primary',
            'contacted' => 'bg-info text-dark',
            'follow_up' => 'bg-warning text-dark',
            'quotation_sent' => 'bg-secondary',
            'converted' => 'bg-success',
            'lost' => 'bg-danger',
            default => 'bg-dark',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function getEnquiryTypeLabelAttribute(): string
    {
        return self::ENQUIRY_TYPES[$this->enquiry_type] ?? ucfirst(str_replace('_', ' ', $this->enquiry_type));
    }

    public static function generateReference(): string
    {
        return 'EL-' . now()->format('ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
    }
}
