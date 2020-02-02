<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const UNAVAILABLE_PRODUCT = 'unavailable';
    const AVAILABLE_PRODUCT   = 'available';

    //
    protected $fillable = [
      'name',
      'description',
      'quantity'
      'status',
      'image',
      'seller_id',
    ];

    public function isAvailable()
    {
      return $this->status == Product::AVAILABLE_PRODUCT;
    }
}
