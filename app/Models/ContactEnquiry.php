<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactEnquiry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference_number',
        'name',
        'mobile_number',
        'email',
        'subject',
        'message',
        'status',
        'admin_notes',
        'ip_address',
    ];

    public static function generateReference(): string
    {
        return 'MSG-' . now()->format('ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
    }
}
