<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Bid;


class Product extends Model
{
    protected $fillable = ['name', 'price', 'end_time'];

    public function bids() {
        return $this->hasMany(Bid::class);
    }
}
