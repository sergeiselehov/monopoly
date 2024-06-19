<?php

namespace Database\Seeders;

use App\Models\Withdrawal;
use App\Models\WithdrawalStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WithdrawalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<100; $i++){
            $withdrawal_id = Withdrawal::create([
                "user_id"=>1,
                "uuid"=>Str::uuid(),
                "amount"=>fake()->numberBetween(1000,500000),
                "wallet"=>fake()->word,
                "status"=>WithdrawalStatus::CREATED
            ]);
        }
    }
}
