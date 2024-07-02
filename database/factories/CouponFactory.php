<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    protected $model = \App\Models\Coupon::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->lexify('??????')),
            'discount' => $this->faker->numberBetween(5, 50), // Random discount between 5 and 50
            'discount_type' => $this->faker->randomElement(['percentage', 'fixed']),
            'expires_at' => $this->faker->dateTimeBetween('now', '+1 year'), // Valid for up to one year from now
            'is_active' => $this->faker->boolean(80) // 80% chance of being active
        ];
    }
}
