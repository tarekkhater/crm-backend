<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #e0e7ff 0%, #e6e6e6 100%);
            color: #1a1a1a;
            overflow: hidden;
        }

        .container {
            text-align: center;
            max-width: 500px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
            animation: fadeIn 1s ease-out;
        }

        h1 {
            font-size: 36px;
            font-weight: 700;
            color: #b71c1c;
            margin-bottom: 20px;
            animation: slideInDown 0.8s ease-out;
        }

        p {
            font-size: 18px;
            line-height: 1.6;
            color: #444;
            margin-bottom: 20px;
            animation: slideInUp 0.8s ease-out;
        }

        .error-icon {
            font-size: 80px;
            color: #b71c1c;
            margin-bottom: 20px;
            animation: pulse 2s infinite ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
                margin: 20px;
            }
            h1 {
                font-size: 28px;
            }
            p {
                font-size: 16px;
            }
            .error-icon {
                font-size: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-icon">⚠</div>
        <h1>Unauthorized</h1>
        <p>This server could not verify that you are authorized to access the document requested. Either you supplied the wrong credentials (e.g., bad password), or your browser doesn't understand how to supply the credentials required. </p>
    </div>
</body>
</html>