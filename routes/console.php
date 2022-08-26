<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('test-user', function () {
    $faker = Faker\Factory::create();

    \App\Models\User::query()
        ->create([
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => \Illuminate\Support\Facades\Hash::make('123456789'),
            'email_verified_at' => now()->toString(),
        ]);
});
