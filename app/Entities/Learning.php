<?php

namespace App\Entities;

use Illuminate\Support\Facades\Redis;

trait Learning {

    public function completeLesson($lesson)
    {
        Redis::sadd("user:{$this->id}:series:{$lesson->series->id}", $lesson->id);
    }
    public function percentageCompleteForSeries($series)
    {
        $numberOfLessonsInSeries = $series->lessons->count();
        $numberOfCompletedLesson = $this->getNumerOfCompletedLessonsForASeries($series);

        return ($numberOfCompletedLesson / $numberOfLessonsInSeries) * 100;
    }
    public function getNumerOfCompletedLessonsForASeries($series)
    {
        return count(Redis::smembers("user:{$this->id}:series:{$series->id}"));
    }

}
