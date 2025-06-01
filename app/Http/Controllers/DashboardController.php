<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Repositories\ProductRepository;
use App\Events\ChatMessageSent;
use App\Models\Message;

class DashboardController extends Controller
{
    protected $service;
    public function __construct(ProductService $service) {
        $this->service = $service;
    }

    public function index(ProductRepository $repo) {
        $products = $repo->all();
        return view('dashboard', compact('products'));
    }
}
