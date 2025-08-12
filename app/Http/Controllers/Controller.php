<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Models\ServiceRequest;
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
}
