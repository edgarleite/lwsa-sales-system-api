<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaleRequest;
use App\Http\Resources\SaleResource;
use App\Services\Contracts\SaleServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SaleController extends Controller
{
    protected $saleService;

    public function __construct(SaleServiceInterface $saleService)
    {
        $this->saleService = $saleService;
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->get('per_page', 15);
        $sales = $this->saleService->paginateSales($perPage);
        return SaleResource::collection($sales);
    }

    public function store(SaleRequest $request): JsonResponse|SaleResource
    {
        try {
            $sale = $this->saleService->createSale($request->validated());
            return new SaleResource($sale);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function show(int $id): SaleResource
    {
        $sale = $this->saleService->getSaleById($id);
        return new SaleResource($sale);
    }

    public function update(SaleRequest $request, int $id): JsonResponse|SaleResource
    {
        try {
            $sale = $this->saleService->updateSale($id, $request->validated());
            return new SaleResource($sale);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $this->saleService->deleteSale($id);
        return response()->json(null, 204);
    }

    public function getBySeller(int $sellerId): AnonymousResourceCollection
    {
        $sales = $this->saleService->getSalesBySeller($sellerId);
        return SaleResource::collection($sales);
    }
}
