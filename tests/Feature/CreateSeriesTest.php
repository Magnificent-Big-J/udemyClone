<?php

namespace Tests\Feature;

use App\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateSeriesTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_a_user_can_create_a_series()
    {
        $this->withoutExceptionHandling();

        $this->loginAdmin();

        Storage::fake(config('filesystems.default'));

        $this->post('/admin/series', [
            'title' => 'vuejs for the best',
            'description' => 'the best vue casts ever',
            'image' => UploadedFile::fake()->image('image-series.png')
        ])->assertRedirect()
            ->assertSessionHas('success', 'Series created successfully.');

        Storage::disk(config('filesystems.default'))->assertExists(
            'public/series/' . Str::slug('vuejs for the best') . '.png'
        );

        $this->assertDatabaseHas('series', [
            'slug' => Str::slug('vuejs for the best')
        ]);
    }
    public function test_a_series_must_be_created_with_a_title()
    {
        $this->withExceptionHandling();
        $this->loginAdmin();
        Storage::fake(config('filesystems.default'));

        $this->post('/admin/series', [
            'description' => 'The best vue costs ever.',
            'image_url' => UploadedFile::fake()->image('image-name.png')
        ])->assertSessionHasErrors('title');

        Storage::disk(config('filesystems.default'));
    }
    public function test_a_series_must_be_created_with_a_description()
    {
        $this->withExceptionHandling();
        $this->loginAdmin();
        Storage::fake(config('filesystems.default'));

        $this->post('/admin/series', [
            'title' => 'vuejs for the best',
            'image_url' => UploadedFile::fake()->image('image-name.png')
        ])->assertSessionHasErrors('description');

        Storage::disk(config('filesystems.default'));
    }
    public function test_a_series_must_be_created_with_a_image()
    {
        $this->withExceptionHandling();
        $this->loginAdmin();
        $this->post('/admin/series', [
            'title' => 'vuejs for the best',
            'description' => 'The best vue costs ever.',
        ])->assertSessionHasErrors('image');

    }
    public function test_a_series_must_be_created_with_an_image_which_is_actually_an_image()
    {
        $this->loginAdmin();
        $this->post('/admin/series', [
            'title' => 'the best vue casts ever',
            'description' => 'course description',
            'image' => 'STRING_INVALID_IMAGE'
        ])->assertSessionHasErrors('image');
    }

    public function test_only_administrators_can_create_series()
    {
        $this->actingAs(
            factory(User::class)->create()
        );
        $this->post('admin/series')
            ->assertSessionHas('error', 'You are not authorized to perform this action');
    }
}
