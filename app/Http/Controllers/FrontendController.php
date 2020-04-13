<?php

namespace App\Http\Controllers;

use App\Series;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function welcome()
    {
        $series = Series::orderBy('created_at','DESC')->get();
        return view('welcome', compact('series'));
    }

    /**
     * @param Series $series
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function series(Series $series)
    {
        return view('series', compact('series'));
    }

    /**
     * @return mixed
     */
    public function showAllSeries() {
        return view('all-series')->withSeries(Series::all());
    }

}
