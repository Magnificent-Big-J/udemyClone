<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
class LoginTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_correct_response_after_user_logs_in()
    {
        //Create a user
        $user = factory(User::class)->create();

        $this->postJson('/login', [ // send post request to login
            'email' => $user->email,
            'password' => 'password'
        ])->assertStatus(200) // assert that status code is 422
        ->assertJson([ //assert that the return message is the below one
            'status' => 'ok'
        ])
        ->assertSessionHas('success');
    }


    public function test_a_user_receives_correct_message_when_passing_in_wrong_credentials()
    {
        //Create a user
        $user = factory(User::class)->create();

        $this->postJson('/login', [ // send post request to login
            'email' => $user->email,
            'password' => 'wrong-password'
        ])->assertStatus(422) // assert that status code is 422
        ->assertJson([ //assert that the return message is the below one
            'message' => 'These credentials do not match our records.'
        ]);
    }
}
