<?php

namespace Tests\Unit\Services;

use App\Jobs\SendAdminDailyReport;
use App\Jobs\SendDailySalesReport;
use App\Models\Seller;
use App\Services\Implementation\ReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ReportServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ReportService $reportService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reportService = new ReportService();
    }

    /** @test */
    public function it_can_send_daily_reports()
    {
        // Arrange
        Queue::fake();
        $sellers = Seller::factory()->count(3)->create();
        $date = now()->format('Y-m-d');

        // Act
        $this->reportService->sendDailyReports($date);

        // Assert
        Queue::assertPushed(SendAdminDailyReport::class, function ($job) use ($date) {
            return $job->getDate() === $date; // Usando o getter público
        });

        Queue::assertPushed(SendDailySalesReport::class, 3); // Um para cada vendedor
        
        foreach ($sellers as $seller) {
            Queue::assertPushed(SendDailySalesReport::class, function ($job) use ($seller, $date) {
                return $job->getSeller()->id === $seller->id && $job->getDate() === $date; // Usando os getters públicos
            });
        }
    }

    /** @test */
    public function it_can_resend_report_to_specific_seller()
    {
        // Arrange
        Queue::fake();
        $seller = Seller::factory()->create();
        $date = now()->format('Y-m-d');

        // Act
        $this->reportService->resendReport($seller->id, $date);

        // Assert
        Queue::assertPushed(SendDailySalesReport::class, function ($job) use ($seller, $date) {
            return $job->getSeller()->id === $seller->id && $job->getDate() === $date; // Usando os getters públicos
        });
        
        // Garantir que apenas um job foi enviado
        Queue::assertPushed(SendDailySalesReport::class, 1);
        
        // Garantir que o relatório de admin não foi enviado
        Queue::assertNotPushed(SendAdminDailyReport::class);
    }

    /** @test */
    public function it_uses_current_date_when_no_date_provided()
    {
        // Arrange
        Queue::fake();
        Seller::factory()->create();
        $today = now()->format('Y-m-d');

        // Act
        $this->reportService->sendDailyReports();

        // Assert
        Queue::assertPushed(SendAdminDailyReport::class, function ($job) use ($today) {
            return $job->getDate() === $today; // Usando o getter público
        });
        
        Queue::assertPushed(SendDailySalesReport::class, function ($job) use ($today) {
            return $job->getDate() === $today; // Usando o getter público
        });
    }

    /** @test */
    public function it_handles_empty_sellers_table()
    {
        // Arrange
        Queue::fake();
        // Não criar nenhum vendedor

        // Act
        $this->reportService->sendDailyReports();

        // Assert
        Queue::assertPushed(SendAdminDailyReport::class, 1);
        Queue::assertNotPushed(SendDailySalesReport::class);
    }
}
