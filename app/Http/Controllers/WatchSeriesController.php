<?php

namespace App\Http\Controllers;

use App\Lesson;
use App\Series;
use Illuminate\Http\Request;

class WatchSeriesController extends Controller
{
    public function index(Series $series)
    {
        return redirect()->route('series.watch',[
            'series' => $series->slug,
            'lesson' => $series->lessons()->first()->id
        ]);
    }
    public function showLesson(Series $series, Lesson $lesson)
    {
        return view('watch', compact('series','lesson'));
    }
}
