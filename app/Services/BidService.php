<?php

namespace App\Services;

use App\Repositories\BidRepository;
use Illuminate\Support\Facades\DB;
Use App\Models\Message;

class BidService
{
    protected $bidRepository;

    public function __construct(BidRepository $bidRepository) {
        $this->bidRepository = $bidRepository;
    }

    public function createBid(array $bidData)
    {
        return DB::transaction(function () use ($bidData) {
            return $this->bidRepository->createBid($bidData);
        });
    }

    public function getBidsForProduct($productId)
    {
        $bids = $this->bidRepository->getBidsForProduct($productId);
        $messages = Message::with('user')
        ->where('product_id', $productId)
        ->orderBy('created_at')
        ->get();
        $chat = $bids->map(fn($bid) => [
            'user_id'  => $bid->user_id,
            'username' => $bid->user->name ?? 'Unknown',
            'text'     => $bid->amount,
            'is_bid'   => true,
            'time'     => $bid->created_at,
        ])->merge(
            $messages->map(fn($msg) => [
                'user_id'  => $msg->user_id,
                'username' => $msg->user->name ?? 'Unknown',
                'text'     => $msg->message,
                'is_bid'   => false,
                'time'     => $msg->created_at,
            ])
        )->sortBy('time')->values();

        return $chat->map(fn($item) => [
            'user_id'  => $item['user_id'],
            'username' => $item['username'],
            'text'     => $item['text'],
            'is_bid'   => $item['is_bid'],
            'time'     => $item['time']->toDateTimeString(),
        ])->toArray();
    }
}
