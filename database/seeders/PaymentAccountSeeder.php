<?php

namespace Database\Seeders;

use App\Models\PaymentAccount;
use Illuminate\Database\Seeder;

class PaymentAccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            [
                'bank_name' => 'BCA',
                'account_number' => '1234567890',
                'account_name' => 'PT Essense Lux Indonesia',
                'is_active' => 1,
            ],
            [
                'bank_name' => 'Mandiri',
                'account_number' => '9876543210',
                'account_name' => 'PT Essense Lux Indonesia',
                'is_active' => 1,
            ],
            [
                'bank_name' => 'BNI',
                'account_number' => '5556667777',
                'account_name' => 'PT Essense Lux Indonesia',
                'is_active' => 1,
            ],
            [
                'bank_name' => 'BRI',
                'account_number' => '8889990000',
                'account_name' => 'PT Essense Lux Indonesia',
                'is_active' => 1,
            ],
        ];

        foreach ($accounts as $account) {
            PaymentAccount::create($account);
        }
    }
}
