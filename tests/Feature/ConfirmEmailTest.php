<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConfirmEmailTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_a_user_can_confirm_email()
    {
        $this->withExceptionHandling();
        //create user
        $user = factory(User::class)->create();
        //make a get request to confirm endpoint
        $this->get("/register/confirm/?token={$user->confirm_token}")
        ->assertRedirect('/')
        ->assertSessionHas('success', 'Your email has been confirmed.');
        //assert that the user is confirmed
        $this->assertTrue($user->fresh()->isConfirmed());
    }
    public function test_a_user_is_redirected_if_token_is_wrong()
    {
        $this->withExceptionHandling();
        $user = factory(User::class)->create();
        $this->get("/register/confirm/?token=WRONG_TOKEN")
            ->assertRedirect('/')
            ->assertSessionHas('error', 'Confirmation token not recognized.');
    }
}
