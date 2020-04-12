<?php

use App\Lesson;
use App\Series;
use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'email' => 'joel@mnisi.com',
            'name' => 'Joel Mnisi',
            'password' => bcrypt('password')
        ]);

        factory(Series::class, 5)
            ->create()
            ->each(function($series) {
                factory(Lesson::class, 10)->create([
                    'series_id' => $series->id
                ]);
            });
    }
}
