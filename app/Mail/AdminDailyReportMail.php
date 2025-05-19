<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminDailyReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sales;
    public $totalSales;
    public $sellersCount;
    public $date;

    public function __construct($sales, $totalSales, $sellersCount, $date)
    {
        $this->sales = $sales;
        $this->totalSales = $totalSales;
        $this->sellersCount = $sellersCount;
        $this->date = $date;
    }

    public function build()
    {
        return $this->subject('Relatório Diário Administrativo - ' . $this->date)
            ->markdown('emails.admin_daily_report')
            ->with([
                'sales' => $this->sales,
                'totalSales' => $this->totalSales,
                'sellersCount' => $this->sellersCount,
                'date' => $this->date,
            ]);
    }
}