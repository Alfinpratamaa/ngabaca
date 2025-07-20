<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <h2>Verifikasi Alamat Email Anda</h2>
        </div>
        <div class="content">
            <p>Halo,</p>
            <p>Gunakan kode di bawah ini untuk memverifikasi alamat email Anda. Kode ini akan kedaluwarsa dalam
                10 menit.</p>
            <div class="otp-code">{{ $otp }}</div>
            <p>Jika Anda tidak meminta kode ini, Anda bisa mengabaikan email ini.</p>
            <p>Terima kasih,<br>Tim {{ config('app.name') }}</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
