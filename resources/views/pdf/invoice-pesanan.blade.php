<!DOCTYPE html>
<html>

<head>

    <title>Invoice Pembayaran</title>

    <style>

        body{
            font-family: sans-serif;
            padding: 20px;
        }

        h2{
            margin-bottom: 20px;
        }

        .info{
            margin-bottom: 25px;
        }

        .info p{
            margin: 5px 0;
        }

        table{
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td{
            border: 1px solid #ccc;
        }

        th{
            background: #f2f2f2;
        }

        th, td{
            padding: 10px;
            text-align: left;
        }

        .text-right{
            text-align: right;
        }

        .total{
            font-weight: bold;
        }

        .footer{
            margin-top: 40px;
        }

    </style>

</head>

<body>

    <h2>INVOICE PEMBAYARAN</h2>

    <div class="info">

        <p>
            <strong>No Faktur:</strong>
            F-000{{ $pesanan->id_pesanan }}
        </p>

        <p>
            <strong>Nama Pembeli:</strong>
            {{ $pesanan->pelanggan->nama_pelanggan }}
        </p>

        <p>
            <strong>Tanggal:</strong>
            {{ $pesanan->tgl_pesanan }}
        </p>

    </div>

    <table>

        <thead>

            <tr>

                <th>Barang</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>

            </tr>

        </thead>

        <tbody>

            @foreach ($pesanan->detailPesanan as $detail)

                <tr>

                    <td>
                        {{ $detail->menu->nama_menu }}
                    </td>

                    <td>
                        {{ $detail->jumlah }}
                    </td>

                    <td class="text-right">

                        Rp {{ number_format($detail->menu->harga,0,',','.') }}

                    </td>

                    <td class="text-right">

                        Rp {{ number_format($detail->subtotal,0,',','.') }}

                    </td>

                </tr>

            @endforeach

            <tr class="total">

                <td colspan="3" class="text-right">
                    Total
                </td>

                <td class="text-right">

                    Rp {{ number_format($pesanan->total_harga,0,',','.') }}

                </td>

            </tr>

        </tbody>

    </table>

    <div class="footer">

        <p>
            Terima kasih atas kepercayaan Anda!
        </p>

    </div>

</body>

</html>