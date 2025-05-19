<?php

namespace App\Mail;

use App\Models\Seller;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailySalesReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $seller;
    public $sales;
    public $totalSales;
    public $totalCommission;
    public $date;

    public function __construct(Seller $seller, $sales, $totalSales, $totalCommission, $date)
    {
        $this->seller = $seller;
        $this->sales = $sales;
        $this->totalSales = $totalSales;
        $this->totalCommission = $totalCommission;
        $this->date = $date;
    }

    public function build()
    {
        return $this->subject('Relatório Diário de Vendas - ' . $this->date)
            ->markdown('emails.daily_sales_report')
            ->with([
                'seller' => $this->seller,
                'sales' => $this->sales,
                'totalSales' => $this->totalSales,
                'totalCommission' => $this->totalCommission,
                'date' => $this->date,
            ]);
    }
}