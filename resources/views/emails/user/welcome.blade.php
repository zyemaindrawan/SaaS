<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ $appName }}</title>
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
        .content h2 {
            color: #667eea;
            margin-top: 0;
        }
        ul {
            list-style: none;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
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
            <h3>Selamat Datang di {{ $appName }}</h3>
        </div>
        
        <div class="content">
            <h3>Halo, {{ $userName }}! 👋</h3>
            
            <p>Terima kasih telah mendaftar di <strong>{{ $appName }}</strong>. Kami sangat senang Anda bergabung dengan kami!</p>
            
            <p>Dengan akun Anda, sekarang Anda dapat:</p>
            <ul>
                <li>✅ Membuat website profesional dengan template pilihan</li>
                <li>✅ Mengelola konten website Anda dengan mudah</li>
                <li>✅ Mengakses fitur premium dan dukungan pelanggan</li>
            </ul>
            
            <p style="text-align: center;">
                <a href="{{ $appUrl }}/login" class="button">Mulai Sekarang</a>
            </p>
            
            <p>Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi tim support kami.</p>
            
            <p>Salam hangat,<br><strong>Tim {{ $appName }}</strong></p>
        </div>
        
        <div class="footer">
            <p>Copyright &copy; {{ date('Y') }} {{ $appName }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
