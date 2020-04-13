<?php

namespace App\Http\Controllers;


use App\User;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    public function index(User $user)
    {
        $series = $user->seriesBeingWatched();

        return view('profile',compact('user','series'));
    }
    public function updateCard() {
        $this->validate(request(), [
            'stripeToken' => 'required'
        ]);
        $token = request('stripeToken');
        $user = auth()->user();

        $user->updateCard($token);
        return response()->json('ok');
    }
}
