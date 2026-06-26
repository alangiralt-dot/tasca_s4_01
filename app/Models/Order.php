<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    public $timestamps = false;
    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $currentYear = Carbon::now()->year;
            $lastOrder = static::whereYear('date', $currentYear)
                ->orderBy('id', 'desc')
                ->first();
            if ($lastOrder) {
                $lastSequence = (int) substr($lastOrder->code, -5);
                $nextSequence = $lastSequence + 1;
            } else {
                $nextSequence = 1;
            }
            $order->code = 'SERRA-' . $currentYear . '-' . str_pad($nextSequence, 5, '0', STR_PAD_LEFT);
        });
    }
}
