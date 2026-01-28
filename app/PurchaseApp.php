<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseApp extends Model
{
    use HasFactory;

    protected $table = 'purchase_app';

    protected $fillable = [
        'email',
        'url',
        'licence_key',
    ];
}
