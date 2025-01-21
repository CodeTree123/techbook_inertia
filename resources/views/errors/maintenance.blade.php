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
            background: linear-gradient(135deg, #55AA28, #2a5298);
            font-family: 'Arial', sans-serif;
            text-align: center;
            color: white;
            padding: 20px;
        }

        .container {
            max-width: 800px;
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

        /* Countdown Timer */
        .countdown {
            font-size: 22px;
            font-weight: bold;
            margin: 20px 0;
            padding: 10px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            display: inline-block;
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
        
        <!-- Countdown Timer -->
        <div class="countdown" id="countdown">Maintenance ends in: Loading...</div>

        <div class="loader"></div>
        <div class="logo">
            <img src="https://techyeahinc.com/assets/media/logo/tech_yeah_logo.png" alt="" width="100%">
        </div>
    </div>

    @php 
    use App\Models\GeneralSetting;
    $time = GeneralSetting::select('maintenance_end_time')->first(); 
    $maintenanceEndTime = $time->maintenance_end_time;
    @endphp

    <script>
    // Get maintenance end time from Laravel
    const maintenanceEndTime = "{{ $maintenanceEndTime }}";

    if (maintenanceEndTime) {
        const targetTime = new Date(maintenanceEndTime).getTime();

        function updateCountdown() {
            const now = new Date().getTime();
            const timeLeft = targetTime - now;

            if (timeLeft <= 0) {
                document.getElementById("countdown").innerHTML = "Maintenance completed!";
                return;
            }

            const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

            document.getElementById("countdown").innerHTML = 
                `Maintenance ends in: ${days}d ${hours}h ${minutes}m ${seconds}s`;
        }

        // Update the countdown every second
        setInterval(updateCountdown, 1000);
        updateCountdown();
    } else {
        document.getElementById("countdown").innerHTML = "Maintenance end time not available.";
    }
</script>

</body>
</html>
