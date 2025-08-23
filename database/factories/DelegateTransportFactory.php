<?php

namespace Database\Factories;

use App\Models\DelegateTransport;
use App\Models\Delegate;
use App\Models\DropdownOption;
use Illuminate\Database\Eloquent\Factories\Factory;

class DelegateTransportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DelegateTransport::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'delegate_id' => Delegate::factory(),
            'type' => $this->faker->randomElement(['arrival', 'departure']),
            'mode' => $this->faker->randomElement(['flight', 'land', 'sea']),
            'airport_id' => DropdownOption::factory(),
            'flight_no' => $this->faker->optional()->word,
            'flight_name' => $this->faker->optional()->word,
            'date_time' => $this->faker->dateTime(),
            'status_id' => DropdownOption::factory(),
        ];
    }
}