<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\WithdrawalStatus;
use Illuminate\Support\Facades\DB;

class WithdrawalRepository
{
    public function getWithdrawalls(User $user){
        $withdrawals = DB::table('withdrawals')
            ->select(DB::raw('
                SUM(withdrawals.amount_paid) as sum_amount_rub,
                SUM(withdrawals.amount_usdt_paid) as sum_amount_usdt
                ',
            ))
            ->where('withdrawals.user_id','=',$user->id)
            ->where('withdrawals.status','=',WithdrawalStatus::PAID)
            ->get()
        ;
        return $withdrawals;
    }

}
