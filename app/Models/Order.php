<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'user_id',
        'payment_method',
        'status',
        'payment_status',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Gust',
        ]);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items' , 'order_id', 'product_id' , 'id', 'id')
            ->using(OrderItem::class)
            ->as('order_item')
            ->withPivot([
                'quantity',
                'price',
            ]);
    }

    public function address()
    {
        return $this->hasMany(OrderAddress::class);
    }
    public function billingAddress()
    {
        //return $this->address()->where('type', 'billing'); returns collection
        return $this->hasOne(OrderAddress::class , 'order_id' , 'id')->where('type', 'billing'); //returns model
    }
    public function shippingAddress()
    {
        return $this->hasOne(OrderAddress::class , 'order_id' , 'id')->where('type', 'shipping');
    }

    protected static function booted()
    {
        static::creating(function (Order $order) {
            $order->number = $order->getNextOrderNumber();

        });
    }

    public function getNextOrderNumber()
    {
        $year = Carbon::now()->year;
        $number = Order::whereYear('created_at', $year)->max('number') ;
        if ($number) {
            return $number + 1;
        }
        return $year . '0001';
    }
}
