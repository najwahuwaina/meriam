<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function payment($id)
    {
        $pesanan = Pesanan::with('pelanggan')->findOrFail($id);

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // ORDER ID unik (wajib)
        $orderId = 'ORDER-' . $pesanan->id_pesanan . '-' . uniqid();

        // Parameter transaksi
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $pesanan->total_harga,
            ],
            'customer_details' => [
                'first_name' => $pesanan->pelanggan->nama_pelanggan ?? 'Customer',
            ],
        ];

        // Snap Token
        $snapToken = Snap::getSnapToken($params);

        // Simpan pembayaran
        Pembayaran::updateOrCreate(
            [
                'id_pesanan' => $pesanan->id_pesanan,
            ],
            [
                'order_id' => $orderId,
                'tgl_bayar' => now(),
                'subtotal' => $pesanan->total_harga,
                'ppn' => $pesanan->total_harga * 0.11,
                'total_bayar' => $pesanan->total_harga + ($pesanan->total_harga * 0.11),
                'snap_token' => $snapToken,
                'transaction_status' => 'pending',
            ]
        );

        return view('midtrans.payment', compact('snapToken', 'pesanan'));
    }

    // 🔥 WEBHOOK MIDTRANS (AUTO UPDATE STATUS)
    public function notification(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $notification = new Notification();

        $transactionStatus = $notification->transaction_status;
        $orderId = $notification->order_id;

        $pembayaran = Pembayaran::where('order_id', $orderId)->first();

        if ($pembayaran) {
            if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                $pembayaran->transaction_status = 'success';
            } elseif ($transactionStatus == 'pending') {
                $pembayaran->transaction_status = 'pending';
            } elseif (in_array($transactionStatus, ['expire', 'cancel'])) {
                $pembayaran->transaction_status = 'failed';
            }

            $pembayaran->save();
        }

        return response()->json(['status' => 'ok']);
    }
}