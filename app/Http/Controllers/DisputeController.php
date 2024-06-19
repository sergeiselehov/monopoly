<?php

namespace App\Http\Controllers;

use App\Http\ApiResponses\ApiResponse;
use App\Http\Resources\DisputeResource;
use App\Models\Dispute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisputeController extends Controller
{
    public function list(Request $request)
    {
        $user = Auth::user();

        $list_query  = Dispute::query()->with(['payment']);

        $list_query->when(!$user->hasRole('Super Admin'), function ($query) use ($user) {
            $query->whereHas('payment', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        });
        $list = $list_query->orderBy('created_at', 'DESC')
            ->paginate(25, page: $request->input('page'));

        return ApiResponse::list()
            ->items(DisputeResource::collection($list));
    }
}

