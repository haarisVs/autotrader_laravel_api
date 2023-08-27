<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    use HasFactory;

    protected $fillable = [
        'webhook_id',
        'type',
        'body',
    ];

    protected $casts = [
        'body' => 'json',
    ];
}
