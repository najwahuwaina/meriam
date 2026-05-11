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

    <p>

        Total Bayar :

        Rp {{ number_format(
            $pesanan->total_harga,
            0,
            ',',
            '.'
        ) }}

    </p>

    <button id="pay-button">

        Bayar Sekarang

    </button>

    <script type="text/javascript">

        document.getElementById(
            'pay-button'
        ).onclick = function () {

            snap.pay(
                '{{ $snapToken }}',
                {

                    onSuccess: function(result) {

                        alert(
                            'Pembayaran berhasil!'
                        );

                        window.location.href =
                            '/payment-success/{{ $pesanan->id_pesanan }}';
                    },

                    onPending: function(result) {

                        alert(
                            'Menunggu pembayaran!'
                        );

                        console.log(result);
                    },

                    onError: function(result) {

                        alert(
                            'Pembayaran gagal!'
                        );

                        console.log(result);
                    }

                }
            );
        };

    </script>

</body>

</html>