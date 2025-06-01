<?php

namespace App\Repositories;

use App\Models\Bid;

class BidRepository
{
    public function all() {
       return bid::orderby('id','desc')->paginate(20);
    }

    public function find($id) {
        return Bid::findOrFail($id);
    }

    public function createBid(array $bidData): Bid
    {
        return Bid::create($bidData);
    }

    public function getBidsForProduct(int $productId)
    {
        return Bid::with('user')
            ->where('product_id', $productId)
            ->orderBy('created_at')
            ->get();
    }
}
