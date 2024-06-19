<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\PaymentStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;


class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = PaymentStatus::get();
        for($z=0;$z<500;$z++){
            $uuid = Str::uuid();
            $amount =  fake()->numberBetween(300,10000);
            $amount_balance = (int) ($amount - 0.2 * $amount);
            Payment::create([
                'uuid'=>$uuid,
                'external_id'=>fake()->uuid,
                'amount'=>$amount,
                'merchant_id'=>1,
                'payment_status_id'=>fake()->randomElement($list)->id,
                'callback_url'=>fake()->word,
                'redirect_url'=>fake()->word,
                'amount_balance'=>$amount_balance,
                'usdt_paid_balance'=> round((int) (100*($amount_balance/90))/100,2),
                'email'=>fake()->safeEmail,
                'customer_name'=>fake()->name,
                'customer_card_number'=>fake()->numberBetween(100000000,99999999999999),
                'dest_card_number'=>fake()->numberBetween(100000000,99999999999999),
                'ip_address'=>fake()->ipv4,
                'ip_address_to_int'=>ip2long(fake()->ipv4),
                'created_at'=>fake()->dateTimeBetween('-180 days')
            ]);
        }
    }
}
