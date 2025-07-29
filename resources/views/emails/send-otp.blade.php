<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kode Verifikasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eeeeee;
        }

        .content {
            padding: 20px 0;
        }

        .otp-code {
            font-size: 36px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 10px;
            color: #333;
            background-color: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            user-select: all;
            -webkit-user-select: all;
            -moz-user-select: all;
            -ms-user-select: all;
            cursor: text;
        }

        .copy-button {
            background-color: #f5c754;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }

        .copy-button:hover {
            background-color: #eebd40;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #888888;
            padding-top: 20px;
            border-top: 1px solid #eeeeee;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="https://ngabaca.me/logo.png" alt="Ngabaca Logo" style="max-width: 150px; height: auto" />
            <h2>Verifikasi Alamat Email Anda</h2>
        </div>
        <div class="content">
            <p>Halo,</p>
            <p>
                Gunakan kode di bawah ini untuk memverifikasi alamat email Anda. Kode ini akan kedaluwarsa dalam 10
                menit.
            </p>
            <div class="otp-code" id="otpCode" title="Klik untuk memilih kode">{{ $otp }}</div>
            <div style="text-align: center; margin-top: 10px">
                <button onclick="copyOTP()" class="copy-button">📋 Salin Kode</button>
            </div>
            <script>
                function copyOTP() {
                    const otpElement = document.getElementById('otpCode');
                    const otpText = otpElement.textContent.trim();

                    // Modern clipboard API
                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard
                            .writeText(otpText)
                            .then(function() {
                                alert('Kode OTP berhasil disalin!');
                            })
                            .catch(function(err) {
                                console.error('Gagal menyalin: ', err);
                                fallbackCopy(otpText);
                            });
                    } else {
                        // Fallback untuk browser lama atau non-HTTPS
                        fallbackCopy(otpText);
                    }
                }

                function fallbackCopy(text) {
                    const textArea = document.createElement('textarea');
                    textArea.value = text;
                    textArea.style.position = 'fixed';
                    textArea.style.left = '-999999px';
                    textArea.style.top = '-999999px';
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();

                    try {
                        document.execCommand('copy');
                        alert('Kode OTP berhasil disalin!');
                    } catch (err) {
                        console.error('Gagal menyalin: ', err);
                        alert('Gagal menyalin kode. Silakan salin secara manual.');
                    }

                    document.body.removeChild(textArea);
                }

                // Auto-select OTP ketika diklik
                document.getElementById('otpCode').addEventListener('click', function() {
                    const range = document.createRange();
                    range.selectNodeContents(this);
                    const selection = window.getSelection();
                    selection.removeAllRanges();
                    selection.addRange(range);
                });
            </script>
            <p>Jika Anda tidak meminta kode ini, Anda bisa mengabaikan email ini.</p>
            <p>
                <strong>Tips:</strong> Anda juga bisa mengklik langsung pada kode di atas untuk memilihnya, lalu
                tekan Ctrl+C (Windows) atau Cmd+C (Mac) untuk menyalin.
            </p>
            <p>Terima kasih,<br />Tim Ngabaca</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Ngabaca. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
