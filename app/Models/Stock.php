<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stock';

    protected $fillable = [
        'vehicle',
        'advertiser',
        'adverts',
        'metadata',
        'features',
        'media',
        'history',
        'check',
        'stockId',
        'searchId',
    ];

    protected $casts = [
        'vehicle' => 'json',
        'advertiser' => 'json',
        'adverts' => 'json',
        'metadata' => 'json', // JSON column for metadata
        'features' => 'json',
        'media' => 'json',
        'history' => 'json',
        'check' => 'json',
    ];
}
