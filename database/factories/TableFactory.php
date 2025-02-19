<?php

namespace Database\Factories;
use App\Models\Table;
 use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Table>
 */
class TableFactory extends Factory {
    protected $model = Table::class;
    public function definition() {
        return [
            'number' => $this->faker->unique()->numberBetween(1, 100),
            'status' => $this->faker->randomElement(['available', 'occupied']),
        ];
    }
}
