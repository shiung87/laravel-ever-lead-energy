<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadActivity extends Model
{
    protected $fillable = [
        'customer_lead_id',
        'user_id',
        'action',
        'old_status',
        'new_status',
        'notes',
        'follow_up_date',
    ];

    protected function casts(): array
    {
        return ['follow_up_date' => 'date'];
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(CustomerLead::class, 'customer_lead_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
