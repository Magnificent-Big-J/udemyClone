<?php

namespace App\Entities;

use App\Lesson;
use App\Series;
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

    /**
     * @param $lesson
     * @return bool
     */
    public  function hasCompletedLesson($lesson)
    {
        return in_array($lesson->id, $this->getCompletedLessonsForASeries($lesson->series));
    }
    public function seriesBeingWatched()
    {
        return collect($this->seriesBeingWatchedIds())->map(function ($id){
            return Series::find($id);
        })->filter();
    }
    public function seriesBeingWatchedIds()
    {
        $keys = Redis::keys("user:{$this->id}:series:*");
        $seriedIds = array();
        foreach ($keys as $key) {
            $seriedId = explode(':', $key)[3];
            array_push($seriedIds, $seriedId);
        }

        return $seriedIds;
    }

    /**
     * @return int
     */
    public function getTotalNumberOfCompletedLessons() {
        return count(Redis::keys("user:{$this->id}:series:*"));
    }

    /**
     * @param $series
     * @return mixed
     */
    public function getNextLessonToWatch($series)
    {
        $lessonIds = $this->getCompletedLessonsForASeries($series);
        $lessonId = end($lessonIds);
        return Lesson::find($lessonId)->getNextLesson();
    }






}
