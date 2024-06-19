<?php

namespace App\Repositories;


use App\Http\ApiRequests\ApiRequest;
use App\Models\Commission;
use App\Models\Payment;
use App\Models\PaymentStatus;
use App\Models\User;
use App\Models\WithdrawalPayment;
use App\Models\WithdrawalPaymentStatus;
use App\Models\WithdrawalStatus;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BalanceRepository
{
    public function balanceSql($user_id = null)
    {
        $user_additional_decision = ' TRUE ';
        if ($user_id !== null) {
            $user_additional_decision = " users.id = $user_id";
        }

        $sql = "
            WITH payment_balance AS (
                SELECT user_id,
                       SUM(payments.amount) AS payment_amount,
                       SUM(commissions.amount) AS commission_amount
                FROM payments
                LEFT JOIN commissions ON commissions.commissionable_type = 'App\Models\Payment'
                    AND commissions.commissionable_id = payments.id
                WHERE payment_status_id IN (" . PaymentStatus::SUCCESSED . ", " . PaymentStatus::ACCEPTED_SUCCESSED . ")
                GROUP BY user_id
            ),
            withdrawal_payments_balance AS (
                SELECT user_id,
                       SUM(withdrawal_payments.amount) AS withdrawal_amount,
                       SUM(commissions.amount) AS commission_amount
                FROM withdrawal_payments
                LEFT JOIN commissions ON commissions.commissionable_type = 'App\Models\WithdrawalPayment'
                    AND commissions.commissionable_id = withdrawal_payments.id
                WHERE withdrawal_payment_status_id IN (" . WithdrawalPaymentStatus::SUCCESSED . ")
                GROUP BY user_id
            ),
            credit_balance AS (
                SELECT user_id,
                       SUM(amount) AS credit
                FROM credit_payments
                GROUP BY user_id
            ),
            credit_withdrawals AS (
                SELECT user_id,
                       SUM(withdrawal_payments.amount) AS withdrawal_amount
                FROM withdrawal_payments
                WHERE withdrawal_payment_status_id IN (" . WithdrawalPaymentStatus::SUCCESSED . ")
                    AND is_credit = 1
                GROUP BY user_id
            ),
            transactions_last_day AS (
                SELECT user_id,
                       COUNT(*) AS transaction_count
                FROM payments
                WHERE created_at >= NOW() - INTERVAL '1 DAY'
                GROUP BY user_id
            ),
            successful_transactions_last_day AS (
                SELECT user_id,
                       COUNT(*) AS successful_transaction_count
                FROM payments
                WHERE created_at >= NOW() - INTERVAL '1 DAY'
                  AND payment_status_id IN (" . PaymentStatus::SUCCESSED . ", " . PaymentStatus::ACCEPTED_SUCCESSED . ")
                GROUP BY user_id
            ),
            withdrawals_last_day AS (
                SELECT user_id,
                       COUNT(*) AS withdrawal_count
                FROM withdrawal_payments
                WHERE created_at >= NOW() - INTERVAL '1 DAY'
                GROUP BY user_id
            ),
            successful_withdrawals_last_day AS (
                SELECT user_id,
                       COUNT(*) AS successful_withdrawal_count
                FROM withdrawal_payments
                WHERE created_at >= NOW() - INTERVAL '1 DAY'
                  AND withdrawal_payment_status_id IN (" . WithdrawalPaymentStatus::SUCCESSED . ")
                GROUP BY user_id
            )
            SELECT
                   users.id,
                   users.name,
                   payment_balance.payment_amount,
                   payment_balance.commission_amount AS payment_commission_amount,
                   withdrawal_payments_balance.withdrawal_amount,
                   withdrawal_payments_balance.commission_amount AS withdrawal_payment_commission_amount,
                   credit_withdrawals.withdrawal_amount AS credit_withdrawal_amount,
                   credit_balance.credit,
                   transactions_last_day.transaction_count AS transactions_last_day,
                   successful_transactions_last_day.successful_transaction_count AS successful_transactions_last_day,
                   withdrawals_last_day.withdrawal_count AS withdrawals_last_day,
                   successful_withdrawals_last_day.successful_withdrawal_count AS successful_withdrawals_last_day,
                   (
                       COALESCE(payment_balance.payment_amount, 0) +
                       COALESCE(credit_balance.credit, 0) -
                       COALESCE(payment_balance.commission_amount, 0) -
                       COALESCE(withdrawal_payments_balance.commission_amount, 0) -
                       COALESCE(withdrawal_payments_balance.withdrawal_amount, 0)
                   ) AS balance,
                   (
                       COALESCE(credit_balance.credit, 0) -
                       COALESCE(credit_withdrawals.withdrawal_amount, 0)
                   ) AS credit_balance
            FROM users
            LEFT JOIN payment_balance ON payment_balance.user_id = users.id
            LEFT JOIN withdrawal_payments_balance ON withdrawal_payments_balance.user_id = users.id
            LEFT JOIN credit_balance ON credit_balance.user_id = users.id
            LEFT JOIN credit_withdrawals ON credit_withdrawals.user_id = users.id
            LEFT JOIN transactions_last_day ON transactions_last_day.user_id = users.id
            LEFT JOIN successful_transactions_last_day ON successful_transactions_last_day.user_id = users.id
            LEFT JOIN withdrawals_last_day ON withdrawals_last_day.user_id = users.id
            LEFT JOIN successful_withdrawals_last_day ON successful_withdrawals_last_day.user_id = users.id
            WHERE users.integration_type_id IS NOT NULL AND $user_additional_decision
        ";
        return $sql;
    }

    public function summaryBalanceSql()
    {
        $sql = "
            with payment_balance as (
                SELECT user_id,
                       SUM(payments.amount) as payment_amount,
                       SUM(commissions.amount) as commisiion_amount
                FROM payments
                LEFT JOIN commissions ON commissions.commissionable_type ='App\Models\Payment' and commissions.commissionable_id=payments.id
                WHERE payment_status_id IN (" . PaymentStatus::SUCCESSED . ", " . PaymentStatus::ACCEPTED_SUCCESSED . ")
                GROUP BY user_id
            ),
            withdrawal_payments_balance as (
                SELECT user_id,
                       SUM(withdrawal_payments.amount) as withdrawal_amount,
                       SUM(commissions.amount) as commisiion_amount
                FROM withdrawal_payments
                LEFT JOIN commissions ON commissions.commissionable_type ='App\Models\WithdrawalPayment' and commissions.commissionable_id=withdrawal_payments.id
                WHERE withdrawal_payment_status_id IN (" . WithdrawalPaymentStatus::SUCCESSED . ")
                GROUP BY user_id
            ),
            credit_balance as (
                SELECT user_id,SUM(amount) as credit
                FROM credit_payments
                GROUP BY user_id
            ),
            credit_withdrawals as (
                SELECT user_id,
                       SUM(withdrawal_payments.amount) as withdrawal_amount
                FROM withdrawal_payments
                WHERE withdrawal_payment_status_id IN (" . WithdrawalPaymentStatus::SUCCESSED . ") and is_credit=1
                GROUP BY user_id
            )
                SELECT
                       SUM(payment_balance.payment_amount) as payment_balance,
                       SUM(payment_balance.commisiion_amount) as payment_commission_amount,
                       SUM(withdrawal_payments_balance.withdrawal_amount) as withdrawal_amount,
                       SUM(withdrawal_payments_balance.commisiion_amount) as withdrawal_payment_commission_amount,
                       SUM(credit_withdrawals.withdrawal_amount) as credit_withdrawal_amount,
                       SUM(credit_balance.credit) as summary_credit,
                       SUM(
                           payment_balance.payment_amount +
                           COALESCE(credit_balance.credit,0) -
                           payment_balance.commisiion_amount -
                           COALESCE(withdrawal_payments_balance.commisiion_amount,0) -
                           withdrawal_payments_balance.withdrawal_amount) as balance,
                       SUM(credit_balance.credit-COALESCE(credit_withdrawals.withdrawal_amount,0))
                FROM users
                         LEFT JOIN payment_balance ON payment_balance.user_id = users.id
                         LEFT JOIN withdrawal_payments_balance ON withdrawal_payments_balance.user_id=users.id
                         LEFT JOIN credit_balance ON credit_balance.user_id=users.id
                         LEFT JOIN credit_withdrawals ON credit_withdrawals.user_id=users.id
                WHERE users.integration_type_id IS NOT NULL


        ";
        return $sql;
    }

    public function getBalanceList()
    {
        $sql = $this->balanceSql();
        $query_result = DB::select($sql);
        return $query_result;
    }


    public function getCurrentBalance(User $user)
    {
        $sql = $this->balanceSql($user->id);
        $query_result = DB::select($sql);
        $balance = 0;
        if (
            $query_result !== null
            && !empty($query_result[0])
            && !empty($query_result[0]->balance)
        ) {
            $balance = $query_result[0]->balance;
        }
        return $balance;
    }

    public function getSummaryBalance()
    {
        $sql = $this->summaryBalanceSql();
        $query_result = DB::select($sql);
        $summaryBalance = 0;
        $payment_commission = 0;
        $withdrawal_payments_commission = 0;
        if (
            $query_result !== null
            && !empty($query_result[0])
            && !empty($query_result[0]->balance)
        ) {
            $summaryBalance = $query_result[0]->balance;
            $payment_commission = $query_result[0]->payment_commission_amount;
            $withdrawal_payments_commission = $query_result[0]->withdrawal_payment_commission_amount;
        }
        return [
            'summaryBalanceNotFormat'=>$summaryBalance,
            'summaryBalance' => number_format(round($summaryBalance), 2, ',', ' '),
            'summaryPaymentsCommission' => number_format(round($payment_commission), 2, ',', ' '),
            'summaryWithdrawalPaymentsCommission' => number_format(round($withdrawal_payments_commission), 2, ',', ' '),
        ];
    }

}
