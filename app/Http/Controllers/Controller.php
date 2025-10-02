<?php

namespace App\Http\Controllers;

use App\Models\Career;
use App\Models\Contact;
use App\Models\MainUser;
use App\Models\Subscribe;
use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function AddContact(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'msg_subject' => 'required',
            'message' => 'required'
        ]);
        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->msg_subject = $request->msg_subject;
        $contact->message = $request->message;
        $contact->save();
        return response()->json(['success' => true]);
    }
    public function AddRequest(Request $request)
    {
        $request->validate([
            'reqDescription' => 'required',
            'service' => 'required'
        ]);
        $serviceRequest = new ServiceRequest();
        $serviceRequest->body = $request->reqDescription;
        $serviceRequest->service_id = $request->service;
        $serviceRequest->save();
        return response()->json(['success' => true]);
    }
    public function AddSubscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        $ip = $_SERVER['REMOTE_ADDR'];

        $subscribe = Subscribe::where('email', $request->email)->first();
        if (!$subscribe) {
            $subscribe = new Subscribe();
            $subscribe->email = $request->email;
            $subscribe->ip = $ip;
            $subscribe->save();
        }
        session()->flash('message', 'Post successfully updated.');
    }

    public function signup(Request $request)
    {
        // Log the incoming request data for debugging
        // Log::info('Signup request data: ', $request->all());

        // try {
        // $request->validate([
        //     'fname' => 'required|string|max:255',
        //     'lname' => 'required|string|max:255',
        //     'email' => 'required|email|unique:main_users,email',
        //     'country_code' => 'required',
        //     'phone' => 'required|string|max:20',
        //     'password' => 'required|string|min:6',
        // ]);
        // } catch (\Illuminate\Validation\ValidationException $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => $e->validator->errors()->first(),
        //         'errors' => $e->validator->errors()
        //     ], 422);
        // }


        // Clean phone number (remove formatting)
        $cleanPhone = preg_replace('/[^0-9]/', '', $request->phone);

        // check email and phone is unique has already exist
        $user = MainUser::where('email', $request->email)->orWhere('mobile', $cleanPhone)->first();

        if (!$user) {
            // try {
            $code_confirm = '6630';
            $code = rand_Code(5);
            $str_code = rand_string(6);
            $child = 'besmani';

            $user = new MainUser();
            $user->fl_name = $request->fname;
            $user->last_name = $request->lname;
            $user->pc_id = $request->country_code;
            $user->mobile = $cleanPhone;
            $user->email = $request->email;
            $user->confirm_code = $code_confirm;
            $user->code = $code;
            $user->password = Hash::make($request->password);
            $user->str_code = $str_code;
            $user->child = $child;
            $user->save();

            return 0;
            // return response()->json([
            //     'success' => true,
            //     'message' => 'Account created successfully! Welcome to BESMANI!'
            // ]);
            // } catch (\Exception $e) {
            //     Log::error('Signup error: ' . $e->getMessage());

            //     return response()->json([
            //         'success' => false,
            //         'message' => 'An error occurred while creating your account. Please try again.'
            //     ], 500);
            // }
        } else {

            // again check confirm code for user
            if ($user->confirm == 0) {
                return 0;
            } else {
                // User is already confirmed, log them in
                Auth::guard('mainUsers')->login($user);
                return response()->json([
                    'success' => true,
                    'message' => 1,
                    'userName' => $user->fl_name,
                ]);

            }
            // return response()->json([
            //     'error' => false,
            //     'message' => 'Email or phone already exists!'
            // ]);
        }
    }


    public function confirmCode(Request $request)
    {
        $request->validate([
            'confirmCode' => 'required',
            'phone' => 'required',
        ]);

        // Clean phone number (remove formatting)
        $cleanPhone = preg_replace('/[^0-9]/', '', $request->phone);

        $user = MainUser::where('confirm_code', $request->confirmCode)->where('mobile', $cleanPhone)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid code!'
            ]);
        }
        if ($user) {
            $user->confirm = 1;
            $user->save();

            //Auth login from MainUser - directly login the user
            Auth::guard('mainUsers')->login($user);

            return response()->json([
                'success' => true,
                'message' => 'Code confirmed!',
                'userName' => $user->fl_name,
            ]);
        }
    }

    public function logout()
    {
        Auth::guard('mainUsers')->logout();
        return redirect('/');
    }
}
