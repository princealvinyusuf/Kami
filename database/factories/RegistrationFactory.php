<?php

namespace Database\Factories;

use App\Models\Registration;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Registration> */
class RegistrationFactory extends Factory
{
    protected $model = Registration::class;

    public function definition(): array
    {
        return [
            'registration_type' => 'consultation',
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => '+628'.fake()->numerify('##########'),
            'city' => fake()->city(),
            'province' => fake()->state(),
            'education' => 'S1',
            'institution' => fake()->company(),
            'occupation' => fake()->jobTitle(),
            'preferred_schedule' => 'Hari kerja',
            'status' => 'new',
        ];
    }
}
