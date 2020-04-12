<?php

namespace App\Http\Controllers;

use App\Series;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function welcome()
    {
        $series = Series::orderBy('created_at','DESC')->get();
        return view('welcome', compact('series'));
    }

}
