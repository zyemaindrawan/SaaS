<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New User Registration</title>
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
            background: #dc3545;
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .user-info {
            background: #f8f9fa;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
        }
        .user-info p {
            margin: 8px 0;
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
            <h3>User Baru Terdaftar</h3>
        </div>
        
        <div class="content">
            <h3>Halo Admin,</h3>
            
            <p>Ada user baru yang baru saja mendaftar di sistem:</p>
            
            <div class="user-info">
                <p><strong>Nama:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Waktu Registrasi:</strong> {{ $registeredAt }}</p>
                <!-- <p><strong>IP Address:</strong> {{ $user->registration_ip ?? 'N/A' }}</p> -->
            </div>
            
            <p><strong>Total User Terdaftar:</strong> {{ $totalUsers }}</p>
            
            <p>Silakan cek dashboard admin untuk informasi lebih lanjut.</p>
        </div>
        
        <div class="footer">
            <p>Copyright &copy; {{ date('Y') }} {{ config('app.name') }}.</p>
        </div>
    </div>
</body>
</html>
