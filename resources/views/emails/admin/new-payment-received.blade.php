<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Payment Received - {{ $paymentCode }}</title>
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
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .payment-details {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 20px 0;
        }
        .payment-details p {
            margin: 10px 0;
        }
        .stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }
        .stat-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
        }
        .stat-box h4 {
            margin: 0;
            color: #64748b;
            font-size: 12px;
            text-transform: uppercase;
        }
        .stat-box p {
            margin: 10px 0 0 0;
            font-size: 24px;
            font-weight: bold;
            color: #1e293b;
        }
        .amount-highlight {
            font-size: 36px;
            font-weight: bold;
            color: #10b981;
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            background: #d1fae5;
            border-radius: 8px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h3>Pembayaran Baru Diterima</h3>
        </div>
        
        <div class="content">
            <h3>Halo Admin,</h3>
            
            <p>Ada pembayaran baru yang telah dikonfirmasi pada sistem:</p>
            
            <div class="amount-highlight">
                Rp {{ $grossAmount }}
            </div>
            
            <div class="payment-details">
                <h3 style="margin-top: 0; color: #1e40af;">Detail Pembayaran</h3>
                <p><strong>Kode Pembayaran:</strong> {{ $paymentCode }}</p>
                <p><strong>Tanggal Bayar:</strong> {{ $paidAt }}</p>
                <p><strong>Status:</strong> <span style="color: #10b981; font-weight: bold;">✓ PAID</span></p>
            </div>
            
            <div class="payment-details">
                <h3 style="margin-top: 0; color: #1e40af;">Informasi Customer</h3>
                <p><strong>Nama:</strong> {{ $userName }}</p>
                <p><strong>Email:</strong> {{ $userEmail }}</p>
                <p><strong>Website:</strong> {{ $websiteName }}</p>
                <p><strong>Template:</strong> {{ $templateName }}</p>
            </div>
            
            <div class="stats">
                <div class="stat-box">
                    <h4>Total Payments</h4>
                    <p>{{ $totalPayments }}</p>
                </div>
                <div class="stat-box">
                    <h4>Total Revenue</h4>
                    <p style="font-size: 18px;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
            </div>
            
            <p><strong>Action Required:</strong></p>
            <ul>
                <li>Review dan aktivasi website customer</li>
                <li>Pastikan semua konten sudah sesuai</li>
                <li>Kirim email aktivasi setelah website live</li>
            </ul>
        </div>
        
        <div class="footer">
            <p>Copyright &copy; {{ date('Y') }} {{ config('app.name') }}.</p>
        </div>
    </div>
</body>
</html>
