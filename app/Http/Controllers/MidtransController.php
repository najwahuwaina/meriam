<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pesanan;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransController extends Controller
{
    public function payment($id)
    {
        $pesanan = Pesanan::with('pelanggan')
            ->findOrFail($id);

        Config::$serverKey = config('midtrans.server_key');

        Config::$isProduction = config('midtrans.is_production');

        Config::$isSanitized = true;

        Config::$is3ds = true;

        $pesanan->update([
            'status' => 'pending'
        ]);

        $params = [

            'transaction_details' => [

                'order_id' => 'ORDER-' .
                    $pesanan->id_pesanan .
                    '-' .
                    time(),

                'gross_amount' => $pesanan->total_harga,

            ],

            'customer_details' => [

                'first_name' => $pesanan->pelanggan->nama_pelanggan,

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

        $snapToken = Snap::getSnapToken($params);

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

        $orderId = explode('-', $orderId);

        $idPesanan = $orderId[1];

        $pesanan = Pesanan::find($idPesanan);

        if (!$pesanan) {

            return response()->json([
                'message' => 'Pesanan tidak ditemukan'
            ]);
        }

        if ($transactionStatus == 'settlement') {

            $pesanan->update([
                'status' => 'paid'
            ]);
        }

        else if ($transactionStatus == 'pending') {

            $pesanan->update([
                'status' => 'pending'
            ]);
        }

        else if (
            $transactionStatus == 'expire' ||
            $transactionStatus == 'cancel'
        ) {

            $pesanan->update([
                'status' => 'failed'
            ]);
        }

        return response()->json([
            'message' => 'Notification berhasil'
        ]);
    }

    public function paymentSuccess($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        $pesanan->update([
            'status' => 'paid'
        ]);

        return redirect('/admin/pesanans');
    }
}