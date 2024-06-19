<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiWithdrawalPaymentResource;
use App\Models\WithdrawalPayment;
use App\Models\WithdrawalPaymentStatus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Services\WithdrawalPaymentService;

class WithdrawalPaymentController extends Controller
{

    public function __construct(protected WithdrawalPaymentService $withdrawalPaymentService)
    {
    }

    /**
     * @throws Exception
     */
    public function list(Request $request): ApiResponse
    {
        $query = $this->getList($request);

        $list = $query->orderBy('id', 'DESC')
            ->paginate(25, ['*'], '', $request->input('page'));

        $withdrawalStatuses = WithdrawalPaymentStatus::all();

        return ApiResponse::list(payload: ['statuses' => $withdrawalStatuses])
            ->items(ApiWithdrawalPaymentResource::collection($list));
    }

    /**
     * @throws Exception
     */
    public function export(Request $request)
    {
        $withdrawals = $this->getList($request)->get();

        $titles = [
            'uuid',
            'amount',
            'external_id',
            'commission',
            'amount_with_commission',
            'currency',
            'state',
            'created_at',
            'client_id',
            'card_number',
            'bank_name'
        ];

        $withdrawals->transform(function (WithdrawalPayment $withdrawal) {
            return [
                'uuid' => $withdrawal->uuid,
                'amount' => $withdrawal->amount,
                'external_id' => $withdrawal->external_id,
                'commission' => $withdrawal->commission,
                'amount_with_commission' => $withdrawal->amount + $withdrawal->commission,
                'currency' => $withdrawal->currency->name,
                'state' => $withdrawal->status?->name,
                'created_at' => $withdrawal->created_at,
                'client_id' => $withdrawal->user_id,
                'card_number' => $withdrawal->destination_card_number,
                'bank_name' => $withdrawal->bankBin?->bank_name
            ];
        });

        $spreadsheet = new Spreadsheet();

        $spreadsheet->setActiveSheetIndex(0)->setTitle('Транзакции')->setShowRowColHeaders(true);

        $spreadsheet->getActiveSheet()->fromArray($titles, '—', 'A1');
        $spreadsheet->getActiveSheet()->fromArray($withdrawals->toArray(), '—', 'A2');
        foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'] as $col) {
            $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }

        ob_start();
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        $export = ob_get_clean();

        return APIResponse::common([
            'file' => base64_encode($export),
            'file_name' => 'WithdrawalPayments ' . Carbon::now()->format('Y-m-d H:i'),
            'type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * @throws Exception
     */
    public function getList(Request $request)
    {
        $user = Auth::user();
        if ($user === null) {
            throw new Exception('Not authorized');
        }

        $query = WithdrawalPayment::query()->with(['status', 'currency', 'bankBin']);

        $query->when(!$user->hasRole('Super Admin'), function ($query) use ($user) {
            $query->where('user_id', $user->id);
        });

        if ($request->input('uuid')) {
            $uuid = $request->input('uuid');
            $query->where(function ($query) use ($uuid) {
                $query->where('uuid', 'LIKE', $uuid)
                    ->orWhere('external_id', 'LIKE', $uuid);
            });
        }

        if ($request->input('amount') !== null) {
            $query->where('amount', '=', $request->input('amount'));
        }

        if ($request->input('source_card_number')) {
            $card_number = $request->input('source_card_number');
            $query->whereHas('card', function ($query) use ($card_number) {
                $query->where('card_number', 'LIKE', '%' . $card_number . '%');
            });
        }

        if ($request->input('destination_card_number')) {
            $card_number = $request->input('destination_card_number');
            $query->where('destination_card_number', 'LIKE', '%' . $card_number . '%');
        }

        if ($request->input('status_id')) {
            $status_id = $request->input('status_id');
            $query->where('withdrawal_status_id', '=', $status_id);
        }

        if ($request->input('withdrawal_id')) {
            $query->where('id', '=', $request->input('withdrawal_id'));
        }

        if ($request->input('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->input('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        if (!empty($request->input('selectedStatuses'))){
            $query->whereIn('withdrawal_payment_status_id', $request->input('selectedStatuses'));
        }

        return $query;
    }

    public function getTotalCount(): ApiResponse
    {
        return ApiResponse::common($this->withdrawalPaymentService->getTotalCount());
    }
}
