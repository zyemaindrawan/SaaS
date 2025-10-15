<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Activated - {{ $websiteName }}</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 50px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 32px;
        }
        .celebration-icon {
            font-size: 80px;
            margin: 20px 0;
            animation: bounce 1s infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        .content {
            padding: 40px 30px;
        }
        .content h2 {
            color: #667eea;
            margin-top: 0;
        }
        .website-card {
            background: linear-gradient(135deg, #ffffffff 0%, #ffffffff 100%);
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            color: #333;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }
        .website-card h3 {
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        .website-card p {
            margin: 8px 0;
            font-size: 16px;
            opacity: 0.95;
        }
        .website-card .domain {
            font-size: 18px;
            font-weight: bold;
            margin: 15px 0;
            padding: 12px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            word-break: break-all;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            padding: 16px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            font-size: 16px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: transform 0.3s ease;
        }
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        .features {
            background: #f8f9ff;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .features h3 {
            color: #667eea;
            margin-top: 0;
        }
        .feature-item {
            display: flex;
            align-items: start;
            margin: 15px 0;
        }
        .feature-icon {
            font-size: 24px;
            margin-right: 12px;
            flex-shrink: 0;
        }
        .info-box {
            background: #fff4e6;
            border-left: 4px solid #ffa726;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .info-box p {
            margin: 5px 0;
        }
        .footer {
            background: #f8f9fa;
            padding: 25px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #ddd, transparent);
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h3>Website Anda Sudah Aktif!</h3>
        </div>
        
        <div class="content">
            <h3>Halo, {{ $userName }}! 👋</h3>
            
            <p>Kabar gembira! Website Anda <strong>{{ $websiteName }}</strong> saat ini sudah aktif dan online. Terima kasih telah mempercayai layanan kami!</p>
            
            <div class="website-card">
                <h3>{{ $websiteName }}</h3>
                <p><strong>Status:</strong> Active & Live</p>
                <p><strong>Diaktifkan:</strong> {{ $activatedAt }}</p>
                
                @if($customDomain)
                    <p><strong>Domain:</strong> {{ $customDomain }}</p>
                @else
                    <p><strong>Domain:</strong> Preview URL</p>
                @endif
            </div>
            
            <div class="button-container">
                <a href="{{ $websiteUrl }}" class="button">🚀 Akses Website Saya</a>
            </div>
            
            <div class="divider"></div>
            
            <p>Jika Anda memiliki pertanyaan, masukan, atau butuh bantuan teknis, jangan ragu untuk menghubungi tim support kami. Kami siap membantu Anda!</p>
            
            <p style="margin-top: 30px;">Salam hangat,<br><strong>Tim {{ $appName }}</strong></p>
        </div>
        
        <div class="footer">
            <p>Copyright &copy; {{ date('Y') }} {{ $appName }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
