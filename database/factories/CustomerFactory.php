<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->firstName.' '.$this->faker->lastName,
            'phone' => $this->getPhone(),
            'email' => $this->faker->email,
            'address' => $this->faker->address,
        ];
    }

    public function getPhone(){
        $code = '+99891';
        $number = (string) rand(1, 9999999);
        while(strlen($number) != 7) {
            $number = '0'.$number;
        }
        return $code.$number;
    }
}
