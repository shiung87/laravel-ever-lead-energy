<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DealerDocument extends Model
{
    protected $fillable = ['dealer_application_id', 'original_name', 'file_path', 'mime_type', 'file_size'];

    public function application(): BelongsTo
    {
        return $this->belongsTo(DealerApplication::class, 'dealer_application_id');
    }
}
