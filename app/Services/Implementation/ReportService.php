<?php

namespace App\Services\Implementation;

use App\Jobs\SendAdminDailyReport;
use App\Jobs\SendDailySalesReport;
use App\Models\Seller;
use App\Services\Contracts\ReportServiceInterface;

class ReportService implements ReportServiceInterface
{
    public function sendDailyReports(string $date = null): void
    {
        $date = $date ?: now()->format('Y-m-d');
        
        // Enviar relatório para o administrador
        SendAdminDailyReport::dispatch($date);

        // Enviar relatório para cada vendedor
        Seller::chunk(100, function ($sellers) use ($date) {
            foreach ($sellers as $seller) {
                SendDailySalesReport::dispatch($seller, $date);
            }
        });
    }

    public function resendReport(int $sellerId, string $date = null): void
    {
        $date = $date ?: now()->format('Y-m-d');
        $seller = Seller::findOrFail($sellerId);
        
        SendDailySalesReport::dispatch($seller, $date);
    }
}