<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SellerRequest;
use App\Http\Resources\SellerResource;
use App\Services\Contracts\SellerServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SellerController extends Controller
{
    protected $sellerService;

    public function __construct(SellerServiceInterface $sellerService)
    {
        $this->sellerService = $sellerService;
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->get('per_page', 15);
        $sellers = $this->sellerService->paginateSellers($perPage);
        return SellerResource::collection($sellers);
    }

    public function store(SellerRequest $request): JsonResponse|SellerResource
    {
        try {
            $seller = $this->sellerService->createSeller($request->validated());
            return new SellerResource($seller);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function show(int $id): SellerResource
    {
        $seller = $this->sellerService->getSellerById($id);
        return new SellerResource($seller);
    }

    public function update(SellerRequest $request, int $id): JsonResponse|SellerResource
    {
        try {
            $seller = $this->sellerService->updateSeller($id, $request->validated());
            return new SellerResource($seller);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $this->sellerService->deleteSeller($id);
        return response()->json(null, 204);
    }

    public function sales(int $sellerId): AnonymousResourceCollection
    {
        $seller = $this->sellerService->getSellerWithSales($sellerId);
        return SellerResource::collection([$seller]);
    }
}
