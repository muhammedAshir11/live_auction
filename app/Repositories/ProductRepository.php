<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function all() {
       return Product::with(['bids.user'])
        ->withMax('bids', 'amount')
        ->orderby('id','desc')
        ->paginate(10);
    }

    public function find($id) {
        return Product::with('bids')->findOrFail($id);
    }

    public function saveProduct(array $productData)
    {
        $productId = $productData['id'] ?? null;
        if($productId && $this->find($productId)){
            Product::where('id', $productId)->update($productData);
        }else{
            Product::create($productData);
        }
        return true;
    }

    public function deleteProduct(int $productId): bool
    {
        return Product::where('id', $productId)->delete();
    }
}
