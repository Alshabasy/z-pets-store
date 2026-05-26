<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_phone',
        'items_snapshot',
        'subtotal',
        'total',
        'notes',
        'status',
        'pdf_path',
        'whatsapp_sent_at',
    ];

    protected $casts = [
        'items_snapshot' => 'array',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'whatsapp_sent_at' => 'datetime',
    ];

    public static function generateOrderNumber(): string
    {
        $date  = now()->format('Ymd');
        $count = static::whereDate('created_at', today())->count() + 1;
        return 'ZP-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
