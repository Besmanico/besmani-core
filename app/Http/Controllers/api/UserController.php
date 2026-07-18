<?php

namespace App\Http\Controllers\Api;

use App\Models\Clinic;
use App\Models\MainUser;
use App\Models\PhoneCountry;
use Illuminate\Http\Request;
use App\Models\ClinicReserve;
use App\Models\ClinicService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     //
    // }

    public function signup(Request $request)
    {

        $cleanPhone = preg_replace('/[^0-9]/', '', $request->phone_number);


        // check email and phone is unique has already exist
        $user = MainUser::where('email', $request->signup_email)->orWhere('mobile', $cleanPhone)->first();

        // remove space from$request->signup_password
        $password = str_replace(' ', '', $request->password);

        if (!$user) {
            // try {
            $code_confirm = '6630';
            $code = rand_Code(5);
            $str_code = rand_string(6);
            $child = $request->refrence;

            $user = new MainUser();
            $user->fl_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->pc_id = $request->phone_country_code;
            $user->mobile = $cleanPhone;
            $user->email = $request->email;
            $user->confirm_code = $code_confirm;
            $user->code = $code;
            $user->password = Hash::make($password); // Use Hash::make() directly for consistency
            $user->str_code = $str_code;
            $user->profile = 1;
            $user->child = $child;
            $user->save();

            // Access-Control-Allow-Headers: Content-Type, Authorization
            // $accessToken = $user->createToken('authToken')->plainTextToken;
            // $refreshToken = $user->createToken('refreshToken')->plainTextToken;

            return response()->json([
                'success' => true,
                'access_token' => $user->createToken('API TOKEN')->plainTextToken,

                'message' => 'Account created successfully!',
                'user' => $user,
            ], 200);
        } else {



            // again check confirm code for user
            // if ($user->confirm == 0) {
            //     return 0;
            // } else {
            //     // User is already confirmed, log them in
            //     Auth::guard('mainUsers')->login($user);
            //     return response()->json([
            //         'success' => true,
            //         'message' => 1,
            //         'userName' => $user->fl_name,
            //     ]);
            // }
            return response()->json([
                'error' => false,
                'message' => 'Email or phone already exists!'
            ]);
        }
    }



    public function login(Request $request)
    {
        $user = MainUser::where('email', $request->email)->orWhere('mobile', $request->email)->first();

        $password = str_replace(' ', '', $request->password);


        if (!$user) {
            return response()->json([
                'error' => true,
                'message' => 'User not found!'
            ], 404);
        }

        // Check password - try both Hash::check and password_verify for compatibility
        $hashedPassword = $user->password;
        if (Hash::check($password, $hashedPassword) || password_verify($password, $hashedPassword)) {
            return response()->json([
                'success' => true,
                'access_token' => $user->createToken('API TOKEN')->plainTextToken,
                'message' => 'Login successful!',
                'user' => $user,
            ], 200);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Invalid password!'
            ], 401);
        }
    }


    public function checkReferenseApi($id)
    {

        // https://beauty.besmani.com/detail/info/102?providerId=286&refTitle=clinic_beauty
        $user = MainUser::find($id);
        if (!$user) {
            return 'User not found!';
        } else {
            return $user->fl_name;
        }
    }
    public function getUsers(Request $request)
    {
        $users = MainUser::where('child', 'vascular')->get();
        return response()->json([
            'success' => true,
            'users' => $users,
        ], 200);
    }
    public function getUser(Request $request, $id)
    {
        $user = MainUser::find($id);
        return response()->json([
            'success' => true,
            'user' => $user,
        ], 200);
    }

    public function getAppointments(Request $request, $id)
    {


        $appointments = ClinicReserve::where('main_user_id', $request->id)->with('clinic')->orderBy('id', 'desc')->get();

        // colleague info

        foreach ($appointments as $item) {
            $colleague = MainUser::where('id', $item->personel_user_id)->first();

            $item->colleague_info = [
                'name' => $colleague->fl_name . ' ' . $colleague->last_name,
                'mobile' => $colleague->mobile,
                'email' => $colleague->email,
            ];
        }
        return response()->json([
            'success' => true,
            'appointments' => $appointments,
        ], 200);
    }

    public function getUserInfo(Request $request, $id)
    {
        $user = MainUser::find($id);
        // country_id to country name
        $user->country_name = PhoneCountry::find($user->country_id)->name_en;

        return response()->json([
            'success' => true,
            'user' => $user,
        ], 200);
    }

    public function getClinicServices(Request $request, $id)
    {

        $res = ClinicService::where('user_id', $id)->where('active', 1)->get();
        // if service_id is not null
        foreach ($res as $key => $val) {
            if ($val['service_id']) {
                $info = Clinic::where('id', $val['service_id'])->first();
                if ($info) {
                    $res[$key]['service_name'] = $info->title;
                    $res[$key]['service_img'] = $info->img;
                    $res[$key]['idClinic'] = $info->id;
                } else {
                    $res[$key]['service_name'] = null;
                    $res[$key]['service_img'] = null;
                    $res[$key]['idClinic'] = null;
                }
            } else {
                $res[$key]['service_name'] = null;
                $res[$key]['service_img'] = null;
                $res[$key]['idClinic'] = null;
            }
        }
        return response()->json([
            'success' => true,
            'services' => $res,
        ], 200);
    }


    public function totalProvider()
    {

        $count = MainUser::count();
        return $count;
    }


     public function allServicesClinic(Request $request, $id)
{
    $infoproducts = Clinic::where('status', 1)
        ->orderBy('id', 'desc')
        ->get();

    foreach ($infoproducts as $item) {
        $res = ClinicService::where('service_id', $item->id)
            ->where('user_id', $id)
            ->first();

        $item->price = $res->price ?? null;
        $item->maxprice = $res->maxprice ?? null;
        $item->time_work = $res->time_work ?? null;
        $item->capacity = $res->capacity ?? null;
    }

    return response()->json([
        'success' => true,
        'services' => $infoproducts,
    ], 200);
}

}
