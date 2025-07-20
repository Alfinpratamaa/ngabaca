<x-layouts.main :title="'Checkout'">
    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6">Checkout</h1>
        @livewire('checkout-page')
    </div>

    <script type="text/javascript"
        src="https://app.{{ config('services.midtrans.is_production') ? 'midtrans' : 'sandbox.midtrans' }}.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Listener ini akan menangkap event 'snap-redirect' yang dikirim dari backend
            Livewire.on('snap-redirect', ({
                token
            }) => {
                if (token) {
                    window.snap.pay(token, {
                        onSuccess: function(result) {
                            /* Anda bisa redirect ke halaman sukses atau menampilkan pesan */
                            console.log(result);
                            window.location.href =
                                '/order/success'; // Ganti dengan URL halaman sukses Anda
                        },
                        onPending: function(result) {
                            /* Biasanya untuk pembayaran pending seperti transfer bank */
                            console.log(result);
                            window.location.href =
                                '/order/pending'; // Ganti dengan URL halaman pending Anda
                        },
                        onError: function(result) {
                            /* Jika terjadi error */
                            console.log(result);
                            alert('Pembayaran Gagal!');
                        },
                        onClose: function() {
                            /* Jika customer menutup popup tanpa menyelesaikan pembayaran */
                            alert('Anda menutup popup pembayaran.');
                        }
                    });
                }
            });
        });
    </script>
</x-layouts.main>
