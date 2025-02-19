<?php

namespace Database\Factories;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory {
    protected $model = Payment::class;
    public function definition() {
        return [
            'order_id' => Order::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 500),
            'method' => $this->faker->randomElement(['bank_transfer', 'qr_code', 'cash']),
            'status' => $this->faker->randomElement(['pending', 'paid', 'failed']),
        ];
    }
}
