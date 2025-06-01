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

    public function saveProduct(array $productData): Product
    {
        return Product::updateOrCreate(
            ['id' => $productData['id'] ?? null],
            $productData
        );
    }

    public function deleteProduct(int $productId): bool
    {
        return Product::where('id', $productId)->delete();
    }
}
