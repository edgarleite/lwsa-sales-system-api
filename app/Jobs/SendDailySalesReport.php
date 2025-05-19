<?php

namespace App\Jobs;

use App\Models\Seller;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendDailySalesReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $seller;
    protected $date;

    /**
     * Create a new job instance.
     */
    public function __construct(Seller $seller, string $date)
    {
        $this->seller = $seller;
        $this->date = $date;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Lógica para enviar e-mail de relatório diário
    }

    /**
     * Getter para a propriedade seller (para testes)
     */
    public function getSeller()
    {
        return $this->seller;
    }

    /**
     * Getter para a propriedade date (para testes)
     */
    public function getDate()
    {
        return $this->date;
    }
}
