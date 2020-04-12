<?php

namespace Tests\Feature;

use App\Lesson;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WatchSeriesTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_a_user_can_complete_a_series()
    {
        $this->flushRedis();
     //user
        $user = factory(User::class)->create();
        //authenticate user
        $this->actingAs($user);
     //series with 2 lessons
        $lesson = factory(Lesson::class)->create();
        $lesson2 = factory(Lesson::class)->create(['series_id'=> 1]);
     //post -> complete-lesson
       $response = $this->post("/series/complete-lesson/{$lesson->id}",[]);
     //assert series is completed
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'ok'
        ]);

        $this->assertTrue(
            $user->hasCompletedLesson($lesson)
        );
        $this->assertFalse(
            $user->hasCompletedLesson($lesson)
        );
    }
}
