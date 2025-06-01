<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Bid;


class Product extends Model
{
    protected $fillable = ['name', 'description', 'bid_end_time','starting_bid'];
    protected $casts = [
        'bid_end_time' => 'datetime',
    ];

    public function bids() {
        return $this->hasMany(Bid::class);
    }

    public function getDisplayBidAmountAttribute()
    {
       $maxBid = $this->bids()->max('amount');
       return $maxBid ?? $this->starting_bid;
    }

    public function highestBid()
    {
        return $this->hasOne(Bid::class)->orderByDesc('amount');
    }

    public function getIsEndedAttribute()
    {
        $now = now()->setTimezone(config('app.timezone'));
        $endTime = $this->bid_end_time->setTimezone(config('app.timezone'));
        return $now->gt($endTime);
    }

    public function getHasWinnerAttribute()
    {
        return $this->bids->isNotEmpty();
    }

    public function getWinnerNameAttribute()
    {
        return optional($this->highestBid?->user)->name;
    }

}
