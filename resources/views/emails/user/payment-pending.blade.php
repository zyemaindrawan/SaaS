<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Pending - {{ $paymentCode }}</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 40px 30px;
        }
        .payment-info {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .payment-info p {
            margin: 10px 0;
        }
        .amount {
            font-size: 32px;
            font-weight: bold;
            color: #d97706;
            text-align: center;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background: #f59e0b;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: bold;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
        .warning {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h3>Menunggu Pembayaran</h3>
        </div>
        
        <div class="content">
            <h3>Halo, {{ $userName }}! 👋</h3>
            
            <p>Terima kasih telah melakukan pemesanan di <strong>{{ $appName }}</strong>. Pembayaran Anda sedang menunggu konfirmasi.</p>
            
            <div class="payment-info">
                <p><strong>Kode Pembayaran:</strong> {{ $paymentCode }}</p>
                <p><strong>Status:</strong> <span style="color: #f59e0b; font-weight: bold;">Pending</span></p>
                <p><strong>Batas Waktu:</strong> {{ $expiredAt }}</p>
            </div>
            
            <div class="amount">
                Rp {{ $grossAmount }}
            </div>
            
            <div class="warning">
                <strong>Penting:</strong> Silakan selesaikan pembayaran sebelum <strong>{{ $expiredAt }}</strong> agar pesanan Anda tidak dibatalkan secara otomatis.
            </div>
            
            <p style="text-align: center;">
                <a href="{{ $paymentUrl }}" class="button">Bayar Sekarang</a>
            </p>
            
            <p><strong>Cara Pembayaran:</strong></p>
            <ol>
                <li>Klik tombol "Bayar Sekarang" di atas</li>
                <li>Pilih metode pembayaran yang Anda inginkan</li>
                <li>Selesaikan pembayaran sesuai instruksi</li>
                <li>Anda akan menerima email konfirmasi setelah pembayaran berhasil</li>
            </ol>
            
            <p>Jika Anda mengalami kesulitan, silakan hubungi tim support kami.</p>
            
            <p>Terima kasih,<br><strong>Tim {{ $appName }}</strong></p>
        </div>
        
        <div class="footer">
            <p>Copyright &copy; {{ date('Y') }} {{ $appName }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
