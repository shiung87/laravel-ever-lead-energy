<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DealerActivity extends Model
{
    protected $fillable = ['dealer_application_id', 'user_id', 'action', 'old_status', 'new_status', 'notes'];

    public function application(): BelongsTo
    {
        return $this->belongsTo(DealerApplication::class, 'dealer_application_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
