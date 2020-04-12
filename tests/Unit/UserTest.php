<?php

namespace Tests\Unit;

use App\Lesson;
use App\Series;
use App\User;
use Illuminate\Support\Collection;
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
        $this->flushRedis();
        $user = factory(User::class)->create();
        $lesson = factory(Lesson::class)->create();
        $lesson2 = factory(Lesson::class)->create([
            'series_id' => 1
        ]);
        $lesson3 = factory(Lesson::class)->create();

        //user watches a lesson in the 1st series
        $user->completeLesson($lesson2);
        //assert that the hasStartedSeries(1)
        $this->assertTrue($user->hasStartedSeries($lesson->series));
        $this->assertFalse($user->hasStartedSeries($lesson3->series));
    }

    public function test_can_get_completed_lessons_for_a_series()
    {
        $this->flushRedis();
        $user = factory(User::class)->create();
        $lesson = factory(Lesson::class)->create();
        $lesson2 = factory(Lesson::class)->create([
            'series_id' => 1
        ]);
        $lesson3 = factory(Lesson::class)->create(
            [
                'series_id' => 1
            ]
        );

        //complete lesson
        $user->completeLesson($lesson);
        $user->completeLesson($lesson2);
        //get completed lessons method
        $completedLessons = $user->getCompletedLessons($lesson->series);
        //assert that the lessons completed are in return value
        $this->assertInstanceOf(Collection::class, $completedLessons);
        $this->assertInstanceOf(Lesson::class, $completedLessons->random());
        $completedLessonsIds = $completedLessons->pluck('id')->all();
        $this->assertTrue(in_array($lesson->id,$completedLessonsIds));
        $this->assertTrue(in_array($lesson2->id,$completedLessonsIds));
        $this->assertFalse(in_array($lesson3->id,$completedLessonsIds));
    }
    public function test_can_check_if_user_has_completed_lesson()
    {
        //user
        $this->flushRedis();
        $user = factory(User::class)->create();
        //series => lessons
        $lesson = factory(Lesson::class)->create();
        $lesson2 = factory(Lesson::class)->create([
            'series_id' => 1
        ]);

        //complete a lesson
        $user->completeLesson($lesson);
        //assert true for hasCompletedLesson() method
        $this->assertTrue(hasCompletedLesson($lesson));
    }

}
