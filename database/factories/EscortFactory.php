<?php

namespace Database\Factories;

use App\Models\Escort;
use Illuminate\Database\Eloquent\Factories\Factory;

class EscortFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Escort::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name_en' => $this->faker->name,
            'name_ar' => $this->faker->name,
            'military_number' => $this->faker->unique()->numerify('######'),
        ];
    }
}
