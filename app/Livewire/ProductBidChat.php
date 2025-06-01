<?php

namespace App\Livewire;
use App\Models\Product;
use App\Models\Bid;
use App\Models\Message;
use Livewire\Component;
use App\Services\BidService;
use App\Services\ProductService;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;


class ProductBidChat extends Component
{
    public $productId;
    public $product;
    public $newMessage = '';
    public $newBidAmount;
    public $bids = [];

    protected $rules = [
        'newBidAmount' => 'nullable|numeric|min:0',
        'newMessage' => 'nullable|string|max:255',
    ];

    protected ?BidService $bidService = null;

    protected function getBidService(): BidService
    {
        if (!$this->bidService) {
            $this->bidService = app(BidService::class);
        }

        return $this->bidService;
    }

    protected ?ProductService $productService = null;

    protected function getProductService(): ProductService
    {
        if (!$this->productService) {
            $this->productService = app(ProductService::class);
        }

        return $this->productService;
    }

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function createBid()
    {
        $this->validate();

        try {
            $user = auth()->user();


            if ($this->product->bid_end_time->isPast()) {
                Session::flash('error', 'Bidding time has ended for this product.');
                return;
            }
            
            if(!$this->newBidAmount){
                Session::flash('error', 'Amount field required.');
                return;
            }


            if ($this->newBidAmount && $this->newBidAmount > $this->product->display_bid_amount) {
                $this->getBidService()->createBid([
                    'product_id' => $this->product->id,
                    'user_id' => $user->id,
                    'amount' => $this->newBidAmount,
                ]);

                $remainingSeconds = Carbon::now()->diffInSeconds($this->product->bid_end_time, false);
                if ($remainingSeconds <= 60 && $remainingSeconds > 0) {
                    $this->product->bid_end_time = $this->product->bid_end_time->addMinutes(2);
                    $this->product->save();
                }

                Session::flash('success', 'Your bid has been placed successfully.');
            } else {
                Session::flash('error', 'Bid amount must be higher than current highest bid.');
            }

            $this->reset(['newBidAmount']);

        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong while placing your bid.');
        }
    }


    public function sendMessage()
    {
        $this->validate();

        try {

            if(!$this->newMessage){
                Session::flash('error', 'Message field required.');
                return;
            }

            $user = auth()->user();

            Message::insert([
                'product_id' => $this->product->id,
                'user_id' => $user->id,
                'message' => $this->newMessage,
                'created_at' => Carbon::now(),
            ]);
            Session::flash('success', 'Message sented successfully.');
            $this->reset(['newMessage']);

        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong while sending message.');
        }
    }

    public function loadBids()
    {
        $this->bids = $this->getBidService()->getBidsForProduct($this->productId);
    }

    public function render()
    {
        $this->loadBids();
        $this->product = $this->getProductService()->findProduct($this->productId);
        return view('livewire.product-bid-chat');
    }
}
