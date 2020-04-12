<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ConfirmEmailController extends Controller
{
    public function index()
    {
        $token =\request('token');

        $user = User::where('confirm_token', $token)->first();
        //dd($user);
        if($user) {
            $user->confirmed();
            Session::flash('success', 'Your email has been confirmed.');

        }
        else {
            Session::flash('error', 'Confirmation token not recognized.');
        }

        return redirect('/');
    }
}
