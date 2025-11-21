<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    public function user_id()
    {
        return Auth::guard('mainUsers')->user()->id;
    }
    
    public function saveNewProductCart($product_id, $cart_id)
    {
        $newProductCart = new CartProduct();
        $newProductCart->product_id = $product_id;
        $newProductCart->cart_id = $cart_id;

        $newProductCart->save();
        return $newProductCart;
    }
    public function addToCart(Request $request)
    {
        // $service_id = $request->service_id;
        $service_id = $request->service_id;
        $package_service_id = $request->package_service_id;
        $total = 100;
      
        

        $check = Cart::where('user_id', $this->user_id())
            ->where('status', 0)->first();
        if (!$check) {
            $newCart = new Cart();
            $newCart->service_id = $service_id;
            $newCart->package_service_id = $package_service_id;
            $newCart->user_id = $this->user_id();
            $newCart->total = $total;
            $newCart->save();
            // $this->saveNewProductCart($service_id, $newCart->id);
            return response()->json(['success' => true]);
        } else {
            // $this->saveNewProductCart($service_id, $check->id);
            return response()->json(['success' => true]);
        }
    }
}
