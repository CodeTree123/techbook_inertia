<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We’ll be back soon!</title>
    <style>
        /* Reset styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Full-page layout */
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            font-family: 'Arial', sans-serif;
            text-align: center;
            color: white;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
        }

        h1 {
            font-size: 42px;
            margin-bottom: 10px;
        }

        p {
            font-size: 18px;
            opacity: 0.8;
        }

        /* Loader animation */
        .loader {
            margin: 30px auto;
            width: 50px;
            height: 50px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            h1 {
                font-size: 32px;
            }
            p {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>🚧 We're Under Maintenance 🚧</h1>
        <p>We're making some improvements. Please check back soon!</p>
        <div class="loader"></div>
    </div>

</body>
</html>
