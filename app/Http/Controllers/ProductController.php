<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use App\Repositories\ProductRepository;
use Carbon\Carbon;


class ProductController extends Controller
{
    protected ProductService $productService;
    protected ProductRepository $productRepository;


    public function __construct(ProductService $productService, ProductRepository $productRepository)
    {
        $this->productService = $productService;
        $this->productRepository = $productRepository;

    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        try {

            $validatedData = $request->validated();
            $validatedData['bid_end_time'] = Carbon::parse($validatedData['bid_end_time'])->format('Y-m-d H:i:s');

            $product = $this->productService->saveProduct($validatedData);

            if ($product) {
                return redirect()->route('dashboard')
                                 ->with('success', 'Product saved successfully!');
            } else {
                return back()->withInput()->withErrors(['product_creation_failed' => 'Failed to create product. Please try again.']);
            }

        } catch (\Exception $e) {
            Log::error("An unexpected error occurred during product saving: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withInput()->withErrors(['unexpected_error' => 'An unexpected error occurred. Please try again later.']);
        }
    }

    public function destroy($id)
    {
        try {

            $product = $this->productRepository->find($id);

            if(auth()->user()->cannot('delete', $product)){
                return back()->withInput()->withErrors(['permission_error' => 'You dont have permission to delete product.']);
            }
            $this->productService->deleteProduct($id);
            return redirect()->back()->with('success', 'Product deleted successfully.');

        } catch (\Exception $e) {
            Log::error("An unexpected error occurred during product deletion: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withInput()->withErrors(['unexpected_error' => 'An unexpected error occurred. Please try again later.']);
        }
    }

    public function show($id){
        $productId = $id;
        return view('product.product',  compact('productId'));
    }
}
