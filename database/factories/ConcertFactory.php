<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Concert;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Concert::class, function (Faker $faker) {
    return [
        'title' => 'Example singer',
        'subtitle' => 'with the Fake Openers',
        'date' => Carbon::parse('+2 weeks'),
        'ticket_price' => 2000,
        'venue' => 'The Example Place',
        'venue_address' => '123 Example Lane',
        'city' => 'Fakeville',
        'state' => 'FAKE',
        'zip' => '90210',
        'additional_information' => 'Example information'
    ];
});
