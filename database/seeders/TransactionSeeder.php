<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i < 1001; $i++) {
            $startOfYear = Carbon::now()->startOfYear();
            $endOfYear = Carbon::now()->endOfYear();

            $randomDateTimeThisYear = Carbon::createFromTimestamp(
                rand($startOfYear->timestamp, $endOfYear->timestamp)
            );

            $transaction = Transaction::create([
                'user_id' => 1,
                'amount' => rand(10000, 50000),
                'description' => 'Transaction ' . $i,
                'transacted_at' => $randomDateTimeThisYear,
            ]);

            $point = $transaction->amount / 1000;

            $pointIn = floor($point);

            $transaction->point()->create([
                'points' => $pointIn,
            ]);
        }
    }
}
