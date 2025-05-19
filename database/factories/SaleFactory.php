<?php

namespace Database\Factories;

use App\Models\Sale;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sale::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // A comissão será calculada automaticamente pelo modelo
        // através do método booted() com os hooks creating/updating
        return [
            'seller_id' => Seller::factory(),
            'amount' => $this->faker->randomFloat(2, 100, 10000),
            'sale_date' => $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the sale was made today.
     */
    public function today(): static
    {
        return $this->state(fn (array $attributes) => [
            'sale_date' => now()->format('Y-m-d'),
        ]);
    }

    /**
     * Indicate that the sale was made yesterday.
     */
    public function yesterday(): static
    {
        return $this->state(fn (array $attributes) => [
            'sale_date' => now()->subDay()->format('Y-m-d'),
        ]);
    }

    /**
     * Indicate that the sale was made this week.
     */
    public function thisWeek(): static
    {
        return $this->state(fn (array $attributes) => [
            'sale_date' => $this->faker->dateTimeBetween('-6 days', 'now')->format('Y-m-d'),
        ]);
    }

    /**
     * Indicate that the sale was made this month.
     */
    public function thisMonth(): static
    {
        return $this->state(fn (array $attributes) => [
            'sale_date' => $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
        ]);
    }
}
