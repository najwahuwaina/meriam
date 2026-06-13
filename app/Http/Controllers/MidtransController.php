<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pesanan;
use App\Models\Pembayaran;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransController extends Controller
{
    public function payment($id)
    {
        $pesanan = Pesanan::with('pelanggan')
            ->findOrFail($id);

        Config::$serverKey =
            config('midtrans.server_key');

        Config::$isProduction =
            config('midtrans.is_production');

        Config::$isSanitized = true;

        Config::$is3ds = true;

        $pesanan->update([
            'status' => 'pending'
        ]);

        $orderId = 'ORDER-' .
            $pesanan->id_pesanan .
            '-' .
            time();

        $params = [

            'transaction_details' => [

                'order_id' => $orderId,

                'gross_amount' =>
                    $pesanan->total_setelah_ppn,

            ],

            'customer_details' => [

                'first_name' =>
                    $pesanan
                        ->pelanggan
                        ->nama_pelanggan,

            ],

            'callbacks' => [

                'finish' => route(
                    'payment.success',
                    $pesanan->id_pesanan
                ),

            ],

            'notification_url' => env(
                'MIDTRANS_NOTIFICATION_URL'
            ),

        ];

        $snapToken =
            Snap::getSnapToken($params);

        Pembayaran::create([

            'id_pesanan' =>
                $pesanan->id_pesanan,

            'tgl_bayar' => now(),

            'subtotal' =>
                $pesanan->total_harga,

            'tarif_ppn' => 11,

            'subtotal_stlh_ppn' =>
                $pesanan->total_setelah_ppn,

            'jumlah' =>
                $pesanan->total_setelah_ppn,

            'order_id' => $orderId,

            'snap_token' => $snapToken,

            'transaction_status' =>
                'pending',

        ]);

        return view(
            'midtrans.payment',
            compact(
                'snapToken',
                'pesanan'
            )
        );
    }

    public function notification(Request $request)
    {
        $orderId = $request->order_id;

        $transactionStatus =
            $request->transaction_status;

        $explodeOrderId =
            explode('-', $orderId);

        $idPesanan =
            $explodeOrderId[1];

        $pesanan = Pesanan::where(
            'id_pesanan',
            $idPesanan
        )->first();

        if (!$pesanan) {

            return response()->json([
                'message' =>
                    'Pesanan tidak ditemukan'
            ]);
        }

        if (
            $transactionStatus ==
            'settlement'
        ) {

            $pesanan->update([
                'status' => 'paid'
            ]);

            Pembayaran::where(
                'id_pesanan',
                $idPesanan
            )->latest()->first()?->update([

                'transaction_status' =>
                    'settlement'

            ]);
        }

        else if (
            $transactionStatus ==
            'pending'
        ) {

            $pesanan->update([
                'status' => 'pending'
            ]);

            Pembayaran::where(
                'id_pesanan',
                $idPesanan
            )->latest()->first()?->update([

                'transaction_status' =>
                    'pending'

            ]);
        }

        else if (
            $transactionStatus ==
            'expire' ||
            $transactionStatus ==
            'cancel'
        ) {

            $pesanan->update([
                'status' => 'failed'
            ]);

            Pembayaran::where(
                'id_pesanan',
                $idPesanan
            )->latest()->first()?->update([

                'transaction_status' =>
                    'failed'

            ]);
        }

        return response()->json([
            'message' =>
                'Notification berhasil'
        ]);
    }

    public function paymentSuccess($id)
    {
        $pesanan =
            Pesanan::findOrFail($id);

        $pesanan->update([
            'status' => 'paid'
        ]);

        return redirect(
            '/admin/pesanans'
        );
    }
}