<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $mail['subject'] }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            margin: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333333;
            margin-bottom: 10px;
        }

        p {
            color: #555555;
            margin: 5px 0;
        }

        .content {
            border-top: 1px solid #dddddd;
            margin-top: 10px;
            padding-top: 10px;
        }

        .content p {
            color: #777777;
            margin: 10px 0;
        }

        .footer {
            margin-top: 20px;
            border-top: 1px solid #dddddd;
            padding-top: 10px;
            text-align: center;
        }

        .footer img {
            max-width: 100px;
            height: auto;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <h2>{{ $mail['subject'] }}</h2>
        <p>From: {{ $mail['sender'] }}</p>
        <div class="content">
            <p>{{ $mail['body'] }}</p>
            <p>Cut & Paste this token "{{ $mail['token'] }}" on the registration page here: <a href="{{$mail['link']}}" type="button">Click</a></p>
            <p>This token will expire in 30 minutes.</p>
        </div>
        <div class="footer">
            <img src="https://techyeahinc.com/assets/media/logo/tech_yeah_logo.png" alt="Company Logo">
            <p>Tech Yeah<br>
                1905 Marketview Dr. Suite 226, Yorkville, IL 60560<br>
                Phone: (833) 832-4002<br>
                Email: info@techyeahinc.com
               Website: <a href="www.techyeahinc.com">www.techyeahinc.com</a>
            </p>
        </div>
    </div>
</body>

</html>
