<?php

namespace Tests\Feature;

use App\Series;
use Faker\Factory;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateLessonTest extends TestCase
{
    use RefreshDatabase;
    protected $faker;

    public function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_a_user_can_create_a_lesson()
    {
        $this->loginAdmin();

        $series = factory(Series::class)->create();
        $lesson = [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(3),
            'series_id' => $series->id,
            'episode_number' => rand(1,200),
            'video_id' => Str::uuid()
        ];
        $this->postJson("/admin/{$series->id}/lessons", $lesson)
            ->assertStatus(201)
            ->assertJson($lesson);

        $this->assertDatabaseHas('lessons', [
            'title' => $lesson['title']
        ]);
    }
    public function test_a_title_is_required_to_create_a_lesson()
    {
        $this->loginAdmin();

        $series = factory(Series::class)->create();
        $lesson = [
            'description' => $this->faker->paragraph(3),
            'series_id' => $series->id,
            'episode_number' => rand(1,200),
            'video_id' => Str::uuid()
        ];
        $this->postJson("/admin/{$series->id}/lessons", $lesson)
            ->assertJsonValidationErrors('title');

    }
    public function test_a_description_is_required_to_create_a_lesson()
    {
        $this->loginAdmin();

        $series = factory(Series::class)->create();
        $lesson = [
            'title' => $this->faker->sentence(3),
            'series_id' => $series->id,
            'episode_number' => rand(1,200),
            'video_id' => Str::uuid()
        ];
        $this->postJson("/admin/{$series->id}/lessons", $lesson)
            ->assertJsonValidationErrors('description');
    }
    public function test_a_episode_number_is_required_to_create_a_lesson()
    {
        $this->loginAdmin();

        $series = factory(Series::class)->create();
        $lesson = [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(3),
            'series_id' => $series->id,
            'video_id' => Str::uuid()
        ];
        $this->postJson("/admin/{$series->id}/lessons", $lesson)
            ->assertJsonValidationErrors('episode_number');
    }
    public function test_a_video_id_is_required_to_create_a_lesson()
    {
        $this->loginAdmin();

        $series = factory(Series::class)->create();
        $lesson = [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(3),
            'series_id' => $series->id,
            'episode_number' => rand(1,200)
        ];
        $this->postJson("/admin/{$series->id}/lessons", $lesson)
            ->assertJsonValidationErrors('video_id');
    }
}
