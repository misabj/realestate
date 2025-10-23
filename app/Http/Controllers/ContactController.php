<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageMailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $data = request()->validate([
            'name'    => ['required','string','max:120'],
            'email'   => ['required','email','max:255'],
            'phone'   => ['nullable','string','max:50'],
            'message' => ['required','string','max:5000'],
        ]);
        
        Mail::to(config('mail.from.address'))->send(new ContactMessageMailable($data));
        
        return back()->with('success', __('Your inquiry has been sent successfully.'));
    }
}
