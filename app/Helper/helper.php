<?php

use App\Models\Cart;
use App\Models\MainUser;
use App\Models\Agreement;
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
    if (Auth::guard('mainUsers')->user()) {
        $userInfo = MainUser::where('id', Auth::guard('mainUsers')->user()->id)->with('InfoActivity')->first();

        // Check if userInfo exists before accessing properties
        if ($userInfo) {
            // get country name with null check
            if ($userInfo->country_id) {
                $phoneCountry = PhoneCountry::find($userInfo->country_id);
                $userInfo->country_name = $phoneCountry ? $phoneCountry->name_en : null;
            } else {
                $userInfo->country_name = null;
            }
        }

        return $userInfo;
    } else {
        return null;
    }
}

function CartInfo()
{
    if (Auth::guard('mainUsers')->user()) {

        $cartInfo = Cart::where('user_id', Auth::guard('mainUsers')->user()->id)
            ->where('status', 0)
            ->with([
                'cartServices.serviceInfo',
                'cartServices.packageServiceItems.customeDeleteItem',
                'cartServices.packageServiceItems.orderItem',
                'cartServices.customePackageItems.orderItem'
            ])
            ->first();

        if ($cartInfo && $cartInfo->cartServices) {
            foreach ($cartInfo->cartServices as $cartService) {
                if ($cartService->packageServiceItems) {
                    foreach ($cartService->packageServiceItems as $packageServiceItem) {
                        if (!$packageServiceItem->orderItem) {
                            $packageServiceItem->orderItem = OrderItem::where('id', $packageServiceItem->orderitem_id)->first();
                        }
                    }
                }
            }
        } else {
            $cartInfo = null;
        }
    } else {
        return $cartInfo = [];
    }
    return $cartInfo;
}
function CartCount()
{
    if (Auth::guard('mainUsers')->user()) {


        $cartCount = Cart::where('user_id', Auth::guard('mainUsers')->user()->id)
            ->where('status', 0)
            ->with('cartServices')->first();
        if ($cartCount) {
            return count($cartCount->cartServices);
        } else {
            return 0;
        }
    } else {
        return 0;
    }
}

function userTerms()
{
    $termsConditions = Agreement::where('agreement_category_id', 4)->first();
    return $termsConditions;
}

function userPrivacy()
{
    $privacy = Agreement::where('agreement_category_id', 5)->first();
    return $privacy;
}

function userServiceAgreement()
{
    $serviceAgreement = Agreement::where('agreement_category_id', 3)->first();
    return $serviceAgreement;
}