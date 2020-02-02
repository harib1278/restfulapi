<?php

namespace App;

use App\Product;
use Illuminate\Database\Eloquent\Model;

class Seller extends User
{
    public function products()
    {
      return $this->hasMany(Product::class);
    }
}
