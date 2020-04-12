<?php

namespace Tests\Feature;

use App\Mail\ConfirmYourEmail;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use Mail;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{

    use DatabaseMigrations, RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_a_user_has_a_default_username_after_registration()
    {
        $this->withExceptionHandling();

        $this->post('/register',[
            'name' => 'Joel Mnisi',
            'email' => 'joel@mnisi.com',
            'password' => 'password'
        ])->assertRedirect();

        $this->assertDatabaseHas('users', [
            'username' => Str::slug('Joel Mnisi')
        ]);
    }
    public function test_a_user_has_a_token_after_registration()
    {
        $this->withoutExceptionHandling();
        Mail::fake();

        //register new user
        $this->post('/register',[
            'name' => 'Joel Mnisi',
            'email' => 'joel@mnisi.com',
            'password' => 'password'
        ])->assertRedirect();

        $user = User::find(1);

        $this->assertNotNull($user->confirm_token);
        $this->assertFalse($user->isConfirmed());

    }
    public function test_an_email_is_sent_to_newly_registered_users()
    {
        $this->withoutExceptionHandling();
        Mail::fake();

        //register new user
        $this->post('/register',[
            'name' => 'Joel Mnisi',
            'email' => 'joel@mnisi.com',
            'password' => 'password'
        ])->assertRedirect();

        //assert that a particular email as sent
        Mail::assertSent(ConfirmYourEmail::class);
    }
}
