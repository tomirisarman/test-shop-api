<?php

namespace App\Http\Controllers;

use App\Cart;
use App\CartItem;
use App\Http\Resources\OrderResource;
use App\Order;
use App\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $orders = Order::where('user_id', auth('sanctum')->id())->with('orderItems')->get();
        return response()->json([
            'data' => OrderResource::collection($orders),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if (auth('sanctum')->check()) {
            $newOrder = Order::create([
                'user_id' => auth('sanctum')->id(),
                'email' => auth('sanctum')->user()->email,
                'phone' => auth('sanctum')->user()->phone,
                'status' => 'created',
            ]);

            $cartId = Cart::where('user_id', auth('sanctum')->id())->first()->id ?? null;

        } else {
            $request->validate([
                'cart_id' => 'required|numeric',
                'phone' => 'required|/^(\+7|8)?[0-9]{10}$/',
                'email' => 'required|string|email',
            ]);

            $newOrder = Order::create([
                'email' => $request->email,
                'phone' => $request->phone,
                'status' => 'created',
            ]);

            $cartId =  Cart::find($request->cart_id) !== null ? $request->cart_id : null;

        }

        if (!isset($cartId)){
            return response()->json([
                'errors' => true,
                'message' => 'Корзина не найдена'
            ], Response::HTTP_BAD_REQUEST);
        }

        $cartItems = CartItem::where('cart_id', $cartId)->get();

        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $newOrder->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
            ]);
        }
        CartItem::where('cart_id', $cartId)->delete();
        Cart::where('id', $cartId)->delete();


        return response()->json([
            'message' => 'Заказ создан',
            'data' => new OrderResource(Order::where('id', $newOrder->id)->with('orderItems')->first()),
        ], Response::HTTP_CREATED);
    }

}
