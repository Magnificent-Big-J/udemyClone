<?php

namespace App\Entities;

use App\Lesson;
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
        return count($this->getCompletedLessonsForASeries($series));
    }
    public function hasStartedSeries($series)
    {
        return $this->getNumerOfCompletedLessonsForASeries($series) > 0 ;
    }
    public function getCompletedLessonsForASeries($series)
    {
        return Redis::smembers("user:{$this->id}:series:{$series->id}");
    }
    public function getCompletedLessons($series)
    {
        return Lesson::whereIn('id',
            $this->getCompletedLessonsForASeries($series))
            ->get();


    }

}
