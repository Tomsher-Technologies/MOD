<?php

namespace Database\Factories;

use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;

class DriverFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Driver::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'military_number' => $this->faker->unique()->numerify('######'),
            'title' => $this->faker->title,
            'name_ar' => $this->faker->name,
            'name_en' => $this->faker->name,
            'mobile_number' => $this->faker->phoneNumber,
            'driver_id' => $this->faker->unique()->numerify('########'),
            'car_type' => $this->faker->word,
            'car_number' => $this->faker->bothify('??####'),
            'capacity' => $this->faker->numberBetween(1, 10),
            'note1' => $this->faker->sentence,
        ];
    }
}
