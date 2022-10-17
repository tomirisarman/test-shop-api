<?php

namespace App\Http\Controllers;

use App\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
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
        if(auth()->check()){
            $cart = Cart::create([
                'user_id' => auth()->id(),
            ]);
        }else{
            $cart = Cart::create();
        }

        return response()->json([
           'message'=>'Корзина создана',
           'cart_id'=>$cart->id,
        ]);
    }

}
