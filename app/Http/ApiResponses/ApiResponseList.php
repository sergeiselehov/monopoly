<?php
declare(strict_types=1);

namespace App\Http\ApiResponses;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ApiResponseList extends ApiResponse
{
    protected int $statusCode = self::CODE_OK;

    /** @var array|Collection|Arrayable */
    protected $list;

    /**
     * Get response.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'data' => $this->list ?? [],
            'payload' => $this->payload ?? [],
            'pagination' => [
                'current_page' => $this->list->currentPage(),
                'last_page' => $this->list->lastPage(),
                'from' => $this->list->firstItem(),
                'to' => $this->list->lastItem(),
                'total' => $this->list->total(),
                'per_page' => $this->list->perPage(),
            ],
        ], $this->statusCode, $this->getHeaders());
    }

    /**
     * List items.
     *
     * @param array|Collection|Arrayable|AnonymousResourceCollection $list
     *
     * @return  $this
     */
    public function items($list): ApiResponseList
    {
        $this->list = $list;

        return $this;
    }
}
