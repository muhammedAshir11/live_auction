<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\User;


class Bid extends Model
{
    protected $fillable = ['product_id', 'user_id', 'amount'];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
