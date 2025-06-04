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
        $messages = Message::with('user')->where('product_id', $productId)->orderBy('created_at')->get();

        $bidItems = $bids->map(fn($b) => [
            'user_id'  => $b->user_id,
            'username' => $b->user->name ?? 'Unknown',
            'text'     => $b->amount,
            'is_bid'   => true,
            'time'     => $b->created_at,
        ]);

        $msgItems = $messages->map(fn($m) => [
            'user_id'  => $m->user_id,
            'username' => $m->user->name ?? 'Unknown',
            'text'     => $m->message,
            'is_bid'   => false,
            'time'     => $m->created_at,
        ]);

        $chat = $bidItems->isNotEmpty() && $msgItems->isNotEmpty()
            ? $bidItems->merge($msgItems)->sortBy('time')->values()
            : ($bidItems->isNotEmpty() ? $bidItems : $msgItems);

        return $chat->map(fn($c) => [
            'user_id'  => $c['user_id'],
            'username' => $c['username'],
            'text'     => $c['text'],
            'is_bid'   => $c['is_bid'],
            'time'     => $c['time']->toDateTimeString(),
        ])->toArray();
    }

}
