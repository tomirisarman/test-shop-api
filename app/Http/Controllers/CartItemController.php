<?php

namespace App\Http\Controllers;

use App\Cart;
use App\CartItem;
use App\Http\Resources\CartItemResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CartItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'cart_id'=>'numeric',
            'product_id'=>'required|numeric',
            'quantity'=>'numeric',
        ]);

        if(auth('sanctum')->check()){
            $cart = Cart::where('user_id', auth('sanctum')->id())->first();
            if (!isset($cart)){
                $cart = Cart::create([
                    'user_id' => auth('sanctum')->id(),
                ]);
            }
        }else{
            $cart = $request->has('cart_id') ? Cart::find($request->cart_id) : Cart::create();
        }

        if (!isset($cart)){
            return response()->json([
                'errors' => true,
                'message' => 'Корзина не найдена'
            ], Response::HTTP_BAD_REQUEST);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
                    ->where('product_id', $request->product_id)->first();

        if (isset($cartItem)){
            CartItem::where('cart_id', $cart->id)
                ->where('product_id', $request->product_id)
                ->update([
                    'quantity' => $cartItem->quantity += 1 ,
                ]);
        }else{
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity ?? 1,
            ]);
        }

        $cartItemProducts = CartItem::where('id', $cartItem->id)->with('product')->first();
        return response()
            ->json([
                'message' => 'Товар добавлен в корзину',
                'cart_id' => $cart->id,
                'data' => new CartItemResource($cartItemProducts)
            ], Response::HTTP_CREATED);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'quantity'=>'required|numeric',
        ]);

        if (CartItem::find($id) == null){
            return response()->json([
                'errors' => true,
                'message' => 'Такого товара нет в корзине'
            ], Response::HTTP_BAD_REQUEST);
        }

        CartItem::where('id', $id)->update([
            'quantity' => $request->quantity,
        ]);

        $cartItem = CartItem::where('id', $id)->with('product')->first();

        return response()->json([
            'message' => 'Количество товара в корзине обновлено.',
            'data' => new CartItemResource($cartItem),
        ], Response::HTTP_OK);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        CartItem::where('id', $id)->delete();

        return response()->json([
            'message' => 'Товар удален из корзины.',
        ], Response::HTTP_OK);
    }
}
