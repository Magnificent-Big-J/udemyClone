<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redis;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function loginAdmin()
    {
        $user = \factory(User::class)->create();

        Config::push('elearning.administrators', $user->email);
        $this->actingAs($user);

    }
    public function flushRedis()
    {
        Redis::flushall();
    }
}
