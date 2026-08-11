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
use App\Models\ReferralInvitation;

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

    // public function signup(Request $request)
    // {

    //     try {
    //         $cleanPhone = preg_replace('/[^0-9]/', '', $request->phone ?? '');

    //         if (empty($request->email) || empty($cleanPhone) || empty($request->password) || empty($request->fname)) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Name, email, phone and password are required.',
    //             ], 422);
    //         }

    //         $user = MainUser::where('email', $request->email)->orWhere('mobile', $cleanPhone)->first();

    //         if (!$user) {

    //             $code_confirm = '6630';
    //             $code = rand_Code(5);
    //             $str_code = rand_string(6);
    //             $child = 'besmani';

    //             $user = new MainUser();
    //             $user->fl_name = $request->fname;
    //             $user->pc_id = $request->country_code ?? null;
    //             $user->mobile = $cleanPhone;
    //             $user->email = $request->email;
    //             $user->confirm_code = $code_confirm;
    //             $user->code = $code;
    //             $user->password = Hash::make($request->password);
    //             $user->str_code = $str_code;
    //             $user->child = $child;
    //             $user->save(); 

    //             return response()->json(0);

    //         }

    //         if ($user->confirm == 0) {
    //             return response()->json(0);
    //         }

    //         Auth::guard('mainUsers')->login($user);
    //         return response()->json([
    //             'success' => true,
    //             'message' => 1,
    //             'userName' => $user->fl_name,
    //         ]);
    //     } catch (\Throwable $e) {
    //         Log::error('Signup error: ' . $e->getMessage(), [
    //             'trace' => $e->getTraceAsString(),
    //             'request' => $request->except(['password']),
    //         ]);
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An error occurred while creating your account. Please try again.',
    //         ], 500);
    //     }
    // }
    
    public function signup(Request $request)
{
    try {
        $cleanPhone = preg_replace('/[^0-9]/', '', $request->phone ?? '');

        if (
            empty($request->email) ||
            empty($cleanPhone) ||
            empty($request->password) ||
            empty($request->fname)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Name, email, phone and password are required.',
            ], 422);
        }

        $user = MainUser::where('email', $request->email)
            ->orWhere('mobile', $cleanPhone)
            ->first();

        if (! $user) {

            $code_confirm = '6630';
            $code = rand_Code(5);
            $str_code = rand_string(6);
            $child = 'besmani';

            $user = new MainUser();

            $user->fl_name = $request->fname;
            $user->pc_id = $request->country_code ?? null;
            $user->country_id = 2;
            $user->mobile = $cleanPhone;
            $user->email = $request->email;
            $user->confirm_code = $code_confirm;
            $user->code = $code;
            $user->password = Hash::make($request->password);
            $user->str_code = $str_code;
            $user->child = $child;

            $invitation = null;

            $invitationId = $request->session()->get(
                'referral_invitation_id'
            );

            if ($invitationId) {

                $invitation = \App\Models\ReferralInvitation::query()
                    ->whereKey($invitationId)
                    ->where(function ($query): void {
                        $query->whereNull('expires_at')
                            ->orWhere('expires_at', '>', now());
                    })
                    ->first();

                if ($invitation) {

                    // Provider invitation
                    if ($invitation->party === 'provider') {
                        $user->service_pr = 1;
                    }

                    // Referrer information
                    $referrer = MainUser::find(
                        $invitation->invited_by_user_id
                    );

                    if ($referrer) {
                        $user->mobile_moaref = $referrer->mobile;

                        $user->fl_moaref = trim(
                            ($referrer->fl_name ?? '') . ' ' .
                            ($referrer->last_name ?? '')
                        );
                    }
                }
            }

            $user->save();

            return response()->json(0);
        }

        if ($user->confirm == 0) {

            $invitationId = $request->session()->get(
                'referral_invitation_id'
            );

            if ($invitationId) {

                $invitation = \App\Models\ReferralInvitation::query()
                    ->whereKey($invitationId)
                    ->where(function ($query): void {
                        $query->whereNull('expires_at')
                            ->orWhere('expires_at', '>', now());
                    })
                    ->first();

                if ($invitation) {

                    $changed = false;

                    if (
                        $invitation->party === 'provider' &&
                        (int) $user->service_pr !== 1
                    ) {
                        $user->service_pr = 1;
                        $changed = true;
                    }

                    $referrer = MainUser::find(
                        $invitation->invited_by_user_id
                    );

                    if ($referrer) {

                        $user->mobile_moaref = $referrer->mobile;

                        $user->fl_moaref = trim(
                            ($referrer->fl_name ?? '') . ' ' .
                            ($referrer->last_name ?? '')
                        );

                        $changed = true;
                    }

                    if ($changed) {
                        $user->save();
                    }
                }
            }

            return response()->json(0);
        }
 
        Auth::guard('mainUsers')->login($user);

        return response()->json([
            'success' => true,
            'message' => 1,
            'userName' => $user->fl_name,
            'isProvider' => (int) $user->service_pr === 1,
            'redirectUrl' => (int) $user->service_pr === 1
                ? '/panel/profile'
                : '/panel',
        ]);

    } catch (\Throwable $e) {

        Log::error('Signup error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'request' => $request->except(['password']),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'An error occurred while creating your account. Please try again.',
        ], 500);
    }
} 
    public function login(Request $request)
    {
        // $request->validate([
        //     'emailOrPhone' => 'required',
        //     'password' => 'required'
        // ]);

        $emailOrPhone = $request->emailOrPhone;

        if (filter_var($emailOrPhone, FILTER_VALIDATE_EMAIL)) {

            $user = MainUser::where('email', $emailOrPhone)->first();
        } elseif (is_numeric($emailOrPhone)) {

            $user = MainUser::where('mobile', $emailOrPhone)->first();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or phone number!'
            ], 422);
        }



        $id = $user->id;

        $hashed_password = $user->password;
        $profile = $user->profile;
        if (empty($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or phone number!'
            ], 422);
        }
        // check password is valid
        if (!Hash::check($request->password, $hashed_password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid password!'
            ], 422);
        }

        // login user
        Auth::guard('mainUsers')->login($user);

        return response()->json([
            'success' => true,
            'message' => 'Login successful!',
            'userName' => $user->fl_name,
        ]);
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

    // other site login or signup
    public function otherSiteLogin(Request $request) {}
    public function otherSiteSignup(Request $request)
    {

        $cleanPhone = preg_replace('/[^0-9]/', '', $request->phone_number);
        return $cleanPhone;


        // check email and phone is unique has already exist
        $user = MainUser::where('email', $request->signup_email)->orWhere('mobile', $cleanPhone)->first();


        if (!$user) {
            // try {
            $code_confirm = '6630';
            $code = rand_Code(5);
            $str_code = rand_string(6);
            $child = $request->refrence;

            $user = new MainUser();
            $user->fl_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->pc_id = $request->country_code;
            $user->mobile = $cleanPhone;
            $user->email = $request->signup_email;
            $user->confirm_code = $code_confirm;
            $user->code = $code;
            $user->password = Hash::make($request->signup_password);
            $user->str_code = $str_code;
            $user->child = $child;
            $user->save();

            // Access-Control-Allow-Headers: Content-Type, Authorization

            return response()->json([
                'success' => true,
                'message' => 'Account created successfully! Welcome to BESMANI!',
                'user' => $user,
            ]);
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
}
