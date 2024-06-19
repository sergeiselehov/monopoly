<?php

namespace App\Console\Commands;

use App\Models\Currency;
use App\Models\PaymentStatus;
use App\Models\SupportRequestCategory;
use App\Models\SupportRequestStatus;
use App\Models\WithdrawalPaymentStatus;
use App\Models\WithdrawalStatus;
use Illuminate\Console\Command;
use ReflectionClass;
use Spatie\Permission\Models\Permission;

class FillVocabulary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill-vocabulary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */


    private $permission_list =  [
        "account.view",
        "user.view",
        "user.create",
        "user.read",
        "user.update",
        "user.delete",
        "role.view",
        "role.create",
        "role.read",
        "role.update",
        "role.delete",
        "permission.view",
        "permission.read",
        "permission.assign",
        "bank-card.create",
        "bank-card.update",
        "bank-card.view",
        "transaction.view",
        "caller.view",
        "sms.view",
        "transactions.change-status",
        "transaction.access_to_all",
        "emulator.create",
        "withdrawal_payments.retry",
        "withdrawal-payments.view",
        "cards.settings.view",
        "cards.settings.update",
        "transactions.access_only_not_closed",
        "sms.access_only_not_closed",
        "cards.can_pay_phone",
        "cards.change_status_to_ready",
        "withdrawal_payments.can_send_success",
        "withdrawal_payments.can_send_failed",
        "withdrawal_payments.see_only_not_closed",
        "withdrawal_payments.can_send_retry_with_exist_card_id",
        "dispute.view",
        "cards.can_do_withdrawal_usdt",
        "cards.access_to_statistic",
        "transaction.can_see_merchant_name",
        "cards.work_on_merchant",
        "usdt-withdrawals.view",
        "withdrawal_payments.can_mass_retry",
        "cards.can_create_loss",
        "cards.losses-confirm"
    ];



    public function handle()
    {

        $refl = new ReflectionClass(PaymentStatus::class);
        $list_of_statuses = $refl->getConstants();

        foreach ($list_of_statuses['NAMES'] as $key => $row) {
            PaymentStatus::query()->updateOrCreate(['id' => $key], ['name' => $row['name'],'name_ru'=>$row['name_ru']]);
        }

        $refl = new ReflectionClass(WithdrawalStatus::class);
        $list_of_statuses = $refl->getConstants();

        foreach ($list_of_statuses['NAMES'] as $key => $row) {
            WithdrawalStatus::query()->updateOrCreate(['id' => $key], ['name' => $row['name'],'name_ru'=>$row['name_ru']]);
        }

        $refl = new ReflectionClass(WithdrawalPaymentStatus::class);
        $list_of_statuses = $refl->getConstants();

        foreach ($list_of_statuses['NAMES'] as $key => $row) {
            WithdrawalPaymentStatus::query()->updateOrCreate(['id' => $key], ['name' => $row['name'],'name_ru'=>$row['name_ru']]);
        }

        $refl = new ReflectionClass(Currency::class);
        $list_of_statuses = $refl->getConstants();

        foreach ($list_of_statuses['NAMES'] as $key => $row) {
            Currency::query()->updateOrCreate(['id' => $key], ['name' => $row['name'],'name_ru'=>$row['name_ru']]);
        }

        foreach($this->permission_list as $permission){
            Permission::updateOrCreate([
                'name' => $permission,
                'guard_name' => 'sanctum'
            ]);
        }

        return Command::SUCCESS;
    }
}
