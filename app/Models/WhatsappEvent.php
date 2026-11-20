<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappEvent extends Model
{
    protected $fillable = [
        'context_type',
        'context_id',
        'context_label',
        'page_url',
        'utm_source',
        'utm_campaign',
        'ip_address',
    ];
}
