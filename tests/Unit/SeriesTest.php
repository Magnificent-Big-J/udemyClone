<?php

namespace Tests\Unit;

use App\Lesson;
use App\Series;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SeriesTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_series_can_get_image_path()
    {
        $series = factory(Series::class)->create([
            'image_url' => 'series/series-slug.png'
        ]);

        $imagePath = $series->image_path;
        $this->assertEquals(asset('storage/series/series-slug.png'), $imagePath);
    }
    public function test_can_get_ordered_lessons_for_a_series()
    {
        //series, lessons

        $lesson = factory(Lesson::class)->create(['episode_number' => 200]);
        $lesson2 = factory(Lesson::class)->create(['episode_number' => 100, 'series_id' => 1]);
        $lesson3 = factory(Lesson::class)->create(['episode_number' => 300, 'series_id' => 1]);

        $lessons = $lesson->series()->getOrderedLessons();
        //make sure that the lessons are in the correct order
        $this->assertInstanceOf(Lesson::class, $lesson->random());
        $this->assertEquals($lessons->first()->id, $lesson2->id);
        $this->assertEquals($lessons->last()->id, $lesson3->id);

        //call the getOrderedLessons

        //make sure that the lessons are in correct order
    }
}
