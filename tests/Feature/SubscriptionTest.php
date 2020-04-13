<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_a_user_without_a_plan_can_watch_free_lessons()
    {
        $user = factory(User::class)->create();

        $this->fakeSubscribe($user);
        dd($user->subscribed('yearly'));
    }

    public function fakeSubscribe($user)
    {
        $user->subscription()->create([
            'name'=> 'yearly',
            'stripe_id' => 'FAKE_STRIPE_ID',
            'stripe_plan' => 'yearly',
            'quantity' => 1
        ]);
    }

}
