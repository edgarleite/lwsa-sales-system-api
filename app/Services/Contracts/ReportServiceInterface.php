<?php

namespace App\Services\Contracts;

use Illuminate\Http\JsonResponse;

interface ReportServiceInterface
{
    public function sendDailyReports(string $date = null): void;
    public function resendReport(int $sellerId, string $date = null): void;
}