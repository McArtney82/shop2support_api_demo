<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retailer extends Model
{
    use HasFactory;

    /**
     * Retailer table
     *
     * @var string
     */
    protected $table = 'retailers';

    protected $fillable = [
        'name',
        'url',
        'affiliate_network',
        'short_text',
        'long_text',
        'link_status',
        'last_verified',
        'featured'
    ];

    /*public function categories()
    {
        return $this->belongsToMany(RetailCategories::class);
    }*/

    public function benificiaries()
    {
        return $this->belongsToMany(Benificiary::class);
    }
}
