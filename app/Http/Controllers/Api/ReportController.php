<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Contracts\ReportServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportServiceInterface $reportService)
    {
        $this->reportService = $reportService;
    }

    public function sendDailyReports(Request $request): JsonResponse
    {
        $date = $request->get('date') ?: now()->format('Y-m-d');
        
        $this->reportService->sendDailyReports($date);

        return response()->json(['message' => 'Daily reports dispatched successfully']);
    }

    public function resendReport(int $sellerId, Request $request): JsonResponse
    {
        $date = $request->get('date') ?: now()->format('Y-m-d');
        
        $this->reportService->resendReport($sellerId, $date);

        return response()->json(['message' => 'Report resent successfully']);
    }
}
