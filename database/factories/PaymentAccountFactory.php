<?php

namespace Database\Factories;

use App\Models\PaymentAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentAccountFactory extends Factory
{
    protected $model = PaymentAccount::class;

    public function definition(): array
    {
        return [
            'bank_name' => fake()->randomElement(['BCA', 'Mandiri', 'BNI', 'BRI', 'CIMB Niaga']),
            'account_number' => fake()->bankAccountNumber(),
            'account_name' => fake()->name(),
            'is_active' => 1,
        ];
    }
}
