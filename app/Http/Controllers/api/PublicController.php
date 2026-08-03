<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\MainUser;
use App\Models\PhoneCountry;
use App\Models\Province;
use App\Models\UserReferral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class PublicController extends Controller
{


    public function getCountries()
    {
        $countryCode = PhoneCountry::where('status', 1)
            ->orderBy('name_en', 'asc')
            ->get();
        return $countryCode;
    }

    public function getProvinces($country_id)
    {
        $provinces = Province::where('status', 1)->where('phone_country_id', $country_id)
            ->orderBy('name_en', 'asc')
            ->get();
        return $provinces;
    }

    public function getCities($city_id)
    {
        $cities = City::where('status', 1)->where('province_id', $city_id)
            ->orderBy('name_en', 'asc')
            ->get();
        return $cities;
    }
    public function user_Referral($phone)
    {

        return  MainUser::where('mobile', $phone)->first();
    }
    // public function getUserInfo(Request $request, $main_user_id)
    // {
    //     $user = Auth::user();
    //      $mainUser = MainUser::select('id', 'fl_name', 'mobile')->find($main_user_id);
    //     // $mainUser = MainUser::find($main_user_id);

    //      $ref = self::user_Referral($mainUser->mobile);
    //     // referral
    //  $referral = UserReferral::where('referrer_id', $ref->id)->where('referred_user_id', $user->id)->where('source', 'vascular')->exists();

    //     return response()->json([
    //         'success' => true,
    //         'user' => $user,
    //         'mainUser' => $mainUser,
    //     ], 200);
    // }
public function getUserInfo(Request $request, $main_user_id)
{
    $user = Auth::user();

    $mainUser = MainUser::select('id', 'fl_name', 'mobile')
        ->find($main_user_id);

    $userReferral = UserReferral::where('referred_user_id', $user->id)
        ->where('source', 'vascular')
        ->latest()
        ->first();

    $referral = null;

    if ($userReferral) {
        $referral = MainUser::select('id', 'fl_name', 'mobile')
            ->find($userReferral->referrer_id);
    }

    return response()->json([
        'success' => true,
        'user' => $user,
        'mainUser' => $mainUser,
        'referral' => $referral,
    ], 200);
}

    public function checkReferral(Request $request)
    {

        $phone = $request->phone;
        return  MainUser::where('mobile', $phone)->first();
    }

    public function updateUserInfo(Request $request)
    {
        $userId = Auth::user()->id;

        if (!$userId) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }


        $user = MainUser::find($userId);

        $user->fl_name = $request->fl_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->melli_code = $request->ssn;
        $user->birthday = $request->birthday;
        $user->gender = $request->gender;
        $user->country_id = $request->country_id;
        $user->id_province = $request->id_province;
        $user->id_city = $request->city_id;
        $user->postal_code = $request->postal_code;
        $user->address = $request->address;
        $user->neighbourhood = $request->apt;
        $user->mobile_moaref = $request->ref_phone;
        $user->fl_moaref = $request->ref_name;
        $user->social_netword = $request->website;
        $user->pc_id = $request->phone_country_code;

        $user->save();
        $ref = self::user_Referral($request->ref_phone);

        $exists = UserReferral::where('referrer_id', $ref->id)->where('referred_user_id', $user->id)->where('source', 'vascular')->exists();

        if (! $exists) {

            UserReferral::create([
                'referrer_id'      => $ref->id,
                'referred_user_id' => $user->id,
                'source'           => 'vascular',
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully',
        ]);
    }



    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $userId = Auth::user()->id;
        $user = MainUser::findOrFail($userId);

        if ($user->avatar) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
        }

        $file = $request->file('avatar');
        $fileName = $file->hashName();

        $file->storeAs('avatars', $fileName, 'public');

        $user->avatar = $fileName;
        $user->save();

        return response()->json([
            'success'    => true,
            'message'    => 'Avatar uploaded successfully.',
            'avatar'     => $fileName,
            'avatar_url' => asset('storage/avatars/' . $fileName),
        ]);
    }
}
