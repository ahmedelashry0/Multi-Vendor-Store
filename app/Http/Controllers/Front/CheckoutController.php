<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\Cart\CartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Intl\Countries;

class CheckoutController extends Controller
{
    public function create(CartRepository $cart)
    {
        if ($cart->get()->count() == 0) {
            return redirect()->route('home');
        }
        return view('front.checkout', [
            'cart' => $cart,
            'countries' => Countries::getNames(),
        ]);
    }


    public function store(Request $request, CartRepository $cart)
    {
        $request->validate([
//            'addr.billing.first_name' => ['required', 'string', 'max:255'],
//            'addr.billing.last_name' => ['required', 'string', 'max:255'],
//            'addr.billing.email' => ['required', 'string', 'max:255'],
//            'addr.billing.phone_number' => ['required', 'string', 'max:255'],
//            'addr.billing.city' => ['required', 'string', 'max:255'],
        ]);
        $items = $cart->get()->groupBy('product.store_id');

        DB::beginTransaction();
        try {
            foreach ($items as $store_id => $cart_items) {
                $order = Order::create([
                    'store_id' => $store_id,
                    'user_id' => Auth::id(),
                    'payment_method' => 'cod',
                ]);

                foreach ($cart_items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'quantity' => $item->quantity,
                        'price' => $item->product->price,
                    ]);
                }

                foreach ($request->post('addr') as $type => $address) {
                    $address['type'] = $type;
                    $order->address()->create($address);
                }
            }
            $cart->empty();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
        }
        redirect()->route('home');
    }


}
