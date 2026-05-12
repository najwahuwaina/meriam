<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran Midtrans</title>

    <script
        type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}">
    </script>
</head>

<body>

    <h2>Pembayaran Pesanan</h2>

    <h3>ID Pesanan: {{ $pesanan->id_pesanan }}</h3>

    <h3>Total Bayar: Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</h3>

    <button id="pay-button">
        Bayar Sekarang
    </button>

    <script type="text/javascript">

        document.getElementById('pay-button').onclick = function () {

            snap.pay('{{ $snapToken }}', {

                onSuccess: function(result) {

                    alert("Pembayaran berhasil!");

                    window.location.href = "/admin/pesanans";
                },

                onPending: function(result) {

                    alert("Menunggu pembayaran!");
                },

                onError: function(result) {

                    alert("Pembayaran gagal!");
                }

            });

        };

    </script>

</body>
</html>