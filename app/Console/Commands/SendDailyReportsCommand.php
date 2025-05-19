<?php

namespace App\Console\Commands;

use App\Jobs\SendAdminDailyReport;
use App\Jobs\SendDailySalesReport;
use App\Models\Seller;
use Illuminate\Console\Command;

class SendDailyReportsCommand extends Command
{
    protected $signature = 'reports:daily {date?}';
    protected $description = 'Send daily sales reports to sellers and admin';

    public function handle()
    {
        $date = $this->argument('date') ?: now()->format('Y-m-d');
        
        $this->info("Sending daily reports for {$date}...");
        
        // Send to admin
        SendAdminDailyReport::dispatch($date);
        $this->info('Admin report dispatched.');

        // Send to each seller
        Seller::chunk(100, function ($sellers) use ($date) {
            foreach ($sellers as $seller) {
                SendDailySalesReport::dispatch($seller, $date);
                $this->info("Report dispatched for seller: {$seller->name}");
            }
        });

        $this->info('Daily reports dispatched successfully!');
    }
}