<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>找不到頁面 - 短網址服務</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-container {
            max-width: 600px;
            padding: 2rem;
            text-align: center;
        }
        .error-code {
            font-size: 120px;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 0;
            line-height: 1;
        }
        .error-message {
            font-size: 24px;
            margin-bottom: 1.5rem;
            color: #495057;
        }
        .error-description {
            margin-bottom: 2rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <p class="error-code">404</p>
        <h1 class="error-message">找不到頁面</h1>
        <p class="error-description">
            很抱歉，您請求的頁面不存在或已被移除。
            <br>可能是短網址已過期或從未建立過。
        </p>
        <div>
            <a href="{{ url('/') }}" class="btn btn-primary">回到首頁</a>
            <a href="{{ route('urls.create') }}" class="btn btn-outline-secondary ms-2">建立短網址</a>
        </div>
    </div>
</body>
</html>