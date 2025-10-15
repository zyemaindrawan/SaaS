<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success - {{ $paymentCode }}</title>
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .success-icon {
            font-size: 60px;
            margin: 20px 0;
        }
        .content {
            padding: 40px 30px;
        }
        .payment-info {
            background: #d1fae5;
            border-left: 4px solid #10b981;
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
            color: #059669;
            text-align: center;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background: #10b981;
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
        .next-steps {
            background: #eff6ff;
            border: 1px solid #93c5fd;
            padding: 20px;
            border-radius: 4px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h3>Pembayaran Berhasil!</h3>
        </div>
        
        <div class="content">
            <h3>Selamat, {{ $userName }}! 🎉</h3>
            
            <p>Pembayaran Anda telah <strong>berhasil diproses</strong>. Terima kasih telah mempercayai layanan kami!</p>
            
            <div class="payment-info">
                <p><strong>Kode Pembayaran:</strong> {{ $paymentCode }}</p>
                <p><strong>Status:</strong> <span style="color: #10b981; font-weight: bold;">✓ Paid (Lunas)</span></p>
                <p><strong>Tanggal Pembayaran:</strong> {{ $paidAt }}</p>
                <p><strong>Website:</strong> {{ $websiteName }}</p>
            </div>
            
            <div class="amount">
                Rp {{ $grossAmount }}
            </div>
            
            <div class="next-steps">
                <h3 style="margin-top: 0; color: #1e40af;">📋 Langkah Selanjutnya:</h3>
                <ol style="margin: 10px 0; padding-left: 20px;">
                    <li>Website Anda sedang dalam proses aktivasi</li>
                    <li>Tim kami akan meninjau dan mengaktifkan website dalam 1-2 jam kerja</li>
                    <li>Anda akan menerima email notifikasi setelah website aktif</li>
                    <li>Akses dashboard untuk melihat detail website Anda</li>
                </ol>
            </div>
            
            <p style="text-align: center;">
                <a href="{{ $dashboardUrl }}" class="button">Buka Dashboard</a>
            </p>
            
            <p>Jika Anda memiliki pertanyaan atau butuh bantuan, jangan ragu untuk menghubungi tim support kami.</p>
            
            <p>Salam hangat,<br><strong>Tim {{ $appName }}</strong></p>
        </div>
        
        <div class="footer">
            <p>Copyright &copy; {{ date('Y') }} {{ $appName }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
