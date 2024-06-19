<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\ApiRequests\ApiTransactionListRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiTransactionResource;
use App\Models\Payment;
use App\Models\PaymentStatus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    public function __construct(protected PaymentService $paymentService)
    {
    }

    /**
     * Display a listing of the resource.
     * @throws Exception
     */
    public function list(ApiTransactionListRequest $request)
    {
        $query_result = $this->getList($request);

        $list = $query_result->orderBy('created_at', 'DESC')->paginate(25, page: $request->input('page'));
        $paymentStatuses = PaymentStatus::all();

        return ApiResponse::list(payload: ['statuses' => $paymentStatuses])
            ->items(ApiTransactionResource::collection($list));
    }

    /**
     * @throws Exception
     */
    public function export(ApiTransactionListRequest $request)
    {
        $payments = $this->getList($request)->get();

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
            'comment',
        ];

        $payments->transform(function (Payment $payment) {
            return [
                'uuid' => $payment->uuid,
                'amount' => $payment->amount,
                'external_id' => $payment->external_id,
                'commission' => $payment->commission?->amount,
                'amount_with_commission' => $payment->amount + $payment->commission?->amount,
                'currency' => $payment->currency->name,
                'state' => $payment->status->name,
                'created_at' => $payment->created_at,
                'client_id' => $payment->user_id,
                'comment' => '',
            ];
        });
        $spreadsheet = new Spreadsheet();

        $spreadsheet->setActiveSheetIndex(0)->setTitle('Транзакции')->setShowRowColHeaders(true);

        $spreadsheet->getActiveSheet()->fromArray($titles, '—', 'A1');
        $spreadsheet->getActiveSheet()->fromArray($payments->toArray(), '—', 'A2');
        foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'] as $col) {
            $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }

        ob_start();
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        $export = ob_get_clean();

        return APIResponse::common([
            'file' => base64_encode($export),
            'file_name' => 'Payments ' . Carbon::now()->format('Y-m-d H:i'),
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

        $query_result = Payment::query()->with(['commission', 'currency', 'status']);

        $query_result->when(!$user->hasRole('Super Admin'), function ($query) use ($user) {
            $query->where('user_id', $user->id);
        });

        if ($request->input('date_from') !== null) {
            $query_result->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->input('date_to') !== null) {
            $query_result->whereDate('created_at', '<=', $request->input('date_to'));
        }
        if ($request->input('uuid')) {
            $uuid = $request->input('uuid');
            $query_result->where(function ($query) use ($uuid) {
                $query->where('uuid', 'LIKE', $uuid)
                    ->orWhere('external_id', 'LIKE', $uuid);
            });
        }

        if ($request->input('external_id') !== null) {
            $query_result->where('external_id', $request->input('external_id'));
        }
        if ($request->input('card_to') !== null) {
            $query_result->where('dest_card_number', $request->input('card_to'));
        }
        if ($request->input('card_from') !== null) {
            $query_result->where('customer_card_number', $request->input('card_from'));
        }

        if (!empty($request->input('selectedStatuses'))) {
            $query_result->whereIn('payment_status_id', $request->input('selectedStatuses'));
        }

        return $query_result;
    }

    public function chart(Request $request)
    {
        $year = now()->year;
        $day = now()->subHours(24);
        $week = now()->subDays(7);
        $month = now()->subDays(30);
        $isAdmin = Auth::user()->hasRole('Super Admin');

        if ($request->input('period') === 'year') {
            $payments = DB::table('payments')
                ->select(DB::raw('COUNT(*) as value'), DB::raw("TO_CHAR(MIN(created_at), 'Mon') as label"), 'user_id')
                ->when(!$isAdmin, function($query) {
                    $query->where('user_id', Auth::id());
                })
                ->whereYear('created_at', $year)
                ->whereIn('payment_status_id', [PaymentStatus::ACCEPTED_SUCCESSED, PaymentStatus::SUCCESSED])
                ->groupBy(DB::raw('EXTRACT(MONTH FROM created_at)'))
                ->orderBy(DB::raw("MIN(created_at)"))
                ->get();
        }

        if ($request->input('period') === 'day') {
            $payments = DB::table('payments')
                ->select(DB::raw('COUNT(*) as value'), DB::raw('EXTRACT(HOUR FROM created_at) as label'))
                ->when(!$isAdmin, function($query) {
                    $query->where('user_id', Auth::id());
                })
                ->where('created_at','>=', $day)
                ->whereIn('payment_status_id', [PaymentStatus::ACCEPTED_SUCCESSED, PaymentStatus::SUCCESSED])
                ->groupBy(DB::raw('EXTRACT(HOUR FROM created_at)'))
                ->orderBy('label')
                ->get();
        }
        if ($request->input('period') === 'week') {
            $payments = DB::table('payments')
                ->select(DB::raw('COUNT(*) as value'), DB::raw("TO_CHAR(created_at, 'DD.MM') as label"))
                ->when(!$isAdmin, function($query) {
                    $query->where('user_id', Auth::id());
                })
                ->whereDate('created_at', '>=', $week)
                ->whereIn('payment_status_id', [PaymentStatus::ACCEPTED_SUCCESSED, PaymentStatus::SUCCESSED])
                ->groupBy(DB::raw("TO_CHAR(created_at, 'DD.MM'), created_at::date"))
                ->orderBy(DB::raw("MIN(created_at)"))
                ->get();
        }
        if ($request->input('period') === 'month') {
            $payments = DB::table('payments')
                ->select(DB::raw('COUNT(*) as value'), DB::raw("TO_CHAR(created_at, 'DD.MM') as label"))
                ->when(!$isAdmin, function($query) {
                    $query->where('user_id', Auth::id());
                })
                ->whereDate('created_at', '>=', $month)
                ->whereIn('payment_status_id', [PaymentStatus::ACCEPTED_SUCCESSED, PaymentStatus::SUCCESSED])
                ->groupBy(DB::raw("TO_CHAR(created_at, 'DD.MM'), created_at::date"))
                ->orderBy(DB::raw("MIN(created_at)"))
                ->get();
        }
        if ($request->input('period') === 'all') {
            $payments = DB::table('payments')
                ->select(DB::raw('COUNT(*) as value'), DB::raw("TO_CHAR(MIN(created_at), 'Mon') as label"))
                ->when(!$isAdmin, function($query) {
                    $query->where('user_id', Auth::id());
                })
                ->whereIn('payment_status_id', [PaymentStatus::ACCEPTED_SUCCESSED, PaymentStatus::SUCCESSED])
                ->groupBy(DB::raw("EXTRACT(MONTH FROM created_at)"))
                ->orderBy(DB::raw("MIN(created_at)"))
                ->get();
        }

        return ApiResponse::common([
            'labels' => $payments->pluck('label')->toArray(),
            'values' => $payments->pluck('value')->toArray(),
        ]);
    }

    public function conversion(Request $request)
    {
        list($paymentsTotal, $paymentsSuccess) = $this->paymentService->getConversion($request->input('period'));
        return ApiResponse::common([
            'labels' => ['Успешные', 'Неуспешные'],
            'values' => [$paymentsSuccess, $paymentsTotal - $paymentsSuccess],
        ]);
    }

    public function conversionUser(Request $request, int $userId)
    {
        list($paymentsTotal, $paymentsSuccess) = $this->paymentService->getConversionUser($request->input('period'), $userId);
        return ApiResponse::common([
            'labels' => ['Успешные', 'Неуспешные'],
            'values' => [$paymentsSuccess, $paymentsTotal - $paymentsSuccess],
        ]);
    }
}
