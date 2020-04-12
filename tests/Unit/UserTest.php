<?php

namespace Tests\Unit;

use App\Lesson;
use App\Series;
use App\User;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_a_user_can_complete_a_lesson()
    {
        $this->flushRedis();
         //user
        $user = factory(User::class)->create();
        //series
        $series = factory(Series::class)->create();
        //Lesson
        $lesson = factory(Lesson::class)->create([
            'series_id' => $series->id
        ]);
        $lesson2 = factory(Lesson::class)->create([
            'series_id' => $series->id
        ]);
        //user complete lesson
        $user->completeLesson($lesson);
        $user->completeLesson($lesson2);
        $this->assertEquals(
            Redis::smembers('user:1:series:1'),
            [1,2]
        );
    }
    public function test_can_get_percentage_completed_for_series_for_a_user()
    {
        $this->flushRedis();
        //user
        $user = factory(User::class)->create();

        //Lesson
        $lesson = factory(Lesson::class)->create([
            'series_id' => 1
        ]);
        $lesson2 = factory(Lesson::class)->create([
            'series_id' => 1
        ]);
        factory(Lesson::class)->create([
            'series_id' => 1
        ]);
        factory(Lesson::class)->create([
            'series_id' => 1
        ]);
        //user complete lesson
        $user->completeLesson($lesson);
        $user->completeLesson($lesson2);

        $this->assertEquals(
            $user->percentageCompleteForSeries($lesson->series),
            50
        );

        $this->assertEquals(
            $user->getNumberOfCompletedLessonsForASeries($lesson->series),
            2
        );
    }

    public function test_can_know_if_a_user_has_started_a_series()
    {
        //user, 2 series
        $user = factory(User::class)->create();

        //user watches a lesson in the 1st series

        //assert that the hasStartedSeries(1)
    }
}
