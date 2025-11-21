<?php

use App\Models\Cart;
use App\Models\MainUser;
use App\Models\OrderItem;
use App\Models\PhoneCountry;
use App\Models\PackageServiceItem;
use Illuminate\Support\Facades\Auth;



function countryCode()
{
    $countryCode = PhoneCountry::where('status', 1)
        ->orderBy('name_en', 'asc')
        ->get();
    return $countryCode;
}

function rand_Code($length)
{
    $chars = "0123456789";
    $size = strlen($chars);
    $final = "";
    for ($i = 0; $i < $length; $i++) {
        $str = $chars[rand(0, $size - 1)];
        $final = $final . $str;
    }
    return $final;
}

function rand_string($length)
{
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $size = strlen($chars);
    $final = "";
    for ($i = 0; $i < $length; $i++) {
        $str = $chars[rand(0, $size - 1)];
        $final = $final . $str;
    }

    return $final;
}

function UserInfoPublic()
{
    $userInfo = MainUser::where('id', Auth::guard('mainUsers')->user()->id)->with('InfoActivity')->first();
    return $userInfo;
}

function CartInfo()
{
    $cartInfo = Cart::where('user_id', Auth::guard('mainUsers')->user()->id)->where('status', 0)->first();

    if ($cartInfo) {
        $packageServiceItems = PackageServiceItem::where('package_service_id', $cartInfo->package_service_id)->get();

        foreach ($packageServiceItems as $packageServiceItem) {

            $packageServiceItem->orderItem = OrderItem::where('id', $packageServiceItem->orderitem_id)->first();
        }
    }else{
        $packageServiceItems = [];
        $cartInfo = null;
    }

    return ['cartInfo' => $cartInfo, 'packageServiceItems' => $packageServiceItems ?? []];
}
function CartCount()
{
    $cartCount = Cart::where('user_id', Auth::guard('mainUsers')->user()->id)->where('status', 0)->count();
    return $cartCount;
}