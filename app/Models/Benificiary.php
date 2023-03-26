<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Benificiary extends Model
{
    use HasFactory;

    protected $table = 'benificiaries';

    protected $fillable = [
        'name',
        'about',
        'color',
        'logo'
    ];

    public function retailers()
    {
        return $this->belongsToMany(Retailer::class);
    }
}
