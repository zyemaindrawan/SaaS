<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            margin: 0;
            padding: 40px;
        }
        .error-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .error-title {
            color: #e53e3e;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .error-message {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .template-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 4px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1 class="error-title">Template Error</h1>
        <p class="error-message">
            There was an error rendering the template preview. Please check the template configuration and try again.
        </p>
        
        @if(isset($error))
            <div class="template-info">
                <strong>Error Details:</strong><br>
                {{ $error }}
            </div>
        @endif
        
        @if(isset($template))
            <div class="template-info">
                <strong>Template:</strong> {{ $template->name }}<br>
                <strong>Slug:</strong> {{ $template->slug }}
            </div>
        @endif
    </div>
</body>
</html>
