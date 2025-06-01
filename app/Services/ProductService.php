<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Events\BidPlaced;
use Illuminate\Support\Facades\DB;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository) {
        $this->productRepository = $productRepository;
    }

    public function saveProduct(array $productData)
    {
        return DB::transaction(function () use ($productData) {
            $product = $this->productRepository->saveProduct($productData);
            return $product;
        });
    }

    public function deleteProduct(int $productId): bool
    {
        return DB::transaction(function () use ($productId) {
            return $this->productRepository->deleteProduct($productId);
        });
    }

    public function findProduct(int $productId)
    {
        return $this->productRepository->find($productId);

    }
}
