<!DOCTYPE html>
<html>

<head>
    <title>Welcome to Our Application</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
            border: 1px solid #ddd;
        }

        .header {
            text-align: center;
            background-color: #007bff;
            color: #fff;
            padding: 10px 0;
        }

        .content {
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Welcome, {{ $user->name }}!</h1>
        </div>
        <div class="content">
            <p>We are excited to have you on board. Your account has been created successfully.</p>
            <p>Your temporary password is: <strong>{{ $password }}</strong></p>
            <p>Please make sure to change your password after logging in for the first time.</p>
            <p>If you have any questions, feel free to contact our support team.</p>
        </div>
        <div class="footer">
            <p>Thank you,</p>
            <p>The Team</p>
        </div>
    </div>
</body>

</html>