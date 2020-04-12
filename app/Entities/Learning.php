<?php

namespace App\Entities;

use App\Lesson;
use Illuminate\Support\Facades\Redis;

trait Learning {

    /**
     * @param $lesson
     */
    public function completeLesson($lesson)
    {
        Redis::sadd("user:{$this->id}:series:{$lesson->series->id}", $lesson->id);
    }

    /**
     * @param $series
     * @return float|int
     */
    public function percentageCompleteForSeries($series)
    {
        $numberOfLessonsInSeries = $series->lessons->count();
        $numberOfCompletedLesson = $this->getNumerOfCompletedLessonsForASeries($series);

        return ($numberOfCompletedLesson / $numberOfLessonsInSeries) * 100;
    }

    /**
     * @param $series
     * @return int
     */
    public function getNumerOfCompletedLessonsForASeries($series)
    {
        return count($this->getCompletedLessonsForASeries($series));
    }

    /**
     * @param $series
     * @return bool
     */
    public function hasStartedSeries($series)
    {
        return $this->getNumerOfCompletedLessonsForASeries($series) > 0 ;
    }

    /**
     * @param $series
     * @return mixed
     */
    public function getCompletedLessonsForASeries($series)
    {
        return Redis::smembers("user:{$this->id}:series:{$series->id}");
    }

    /**
     * @param $series
     * @return mixed
     */
    public function getCompletedLessons($series)
    {
        return Lesson::whereIn('id',
            $this->getCompletedLessonsForASeries($series))
            ->get();
    }
    public  function hasCompletedLesson($lesson)
    {
        return in_array($lesson->id, $this->getCompletedLessonsForASeries($lesson->series));
    }

}
