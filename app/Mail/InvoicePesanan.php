<?php

namespace App\Mail;

use App\Models\Pesanan;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use Barryvdh\DomPDF\Facade\Pdf;

class InvoicePesanan extends Mailable
{
    use Queueable, SerializesModels;

    public $pesanan;

    public function __construct(Pesanan $pesanan)
    {
        $this->pesanan = $pesanan;
    }

    public function build()
    {
        $pdf = Pdf::loadView(
            'pdf.invoice-pesanan',
            [
                'pesanan' => $this->pesanan
            ]
        );

        return $this
            ->subject('Invoice Pesanan')

            ->view('emails.invoice')

            ->attachData(
                $pdf->output(),
                'invoice-pesanan.pdf'
            );
    }
}