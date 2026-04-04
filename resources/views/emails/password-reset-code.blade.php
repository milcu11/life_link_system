<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9fafb;
        }
        .card {
            background-color: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            color: #dc2626;
            font-weight: bold;
        }
        .code-box {
            background-color: #f3f4f6;
            border: 2px solid #dc2626;
            border-radius: 6px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .code {
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 4px;
            color: #dc2626;
            font-family: monospace;
        }
        .expiry {
            color: #666;
            font-size: 14px;
            margin-top: 10px;
        }
        .steps {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .steps ol {
            margin: 0;
            padding-left: 20px;
        }
        .steps li {
            margin: 8px 0;
        }
        .warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .warning strong {
            color: #d97706;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #999;
            margin-top: 30px;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
        .button {
            display: inline-block;
            background-color: #dc2626;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <div class="logo">❤️ LifeLink</div>
                <h2>Password Reset Request</h2>
            </div>

            <p>Hello <strong>{{ $userName }}</strong>,</p>

            <p>We received a request to reset the password for your <strong>LifeLink Blood Donation System</strong> account.</p>

            <p style="text-align: center; font-weight: bold; font-size: 16px; margin-bottom: 10px;">Your Verification Code:</p>

            <div class="code-box">
                <div class="code">{{ $resetCode }}</div>
                <div class="expiry">Expires in {{ $expirationMinutes }} minutes</div>
            </div>

            <div class="steps">
                <strong>How to use this code:</strong>
                <ol>
                    <li>Visit the password reset page</li>
                    <li>Enter your email address</li>
                    <li>Enter the verification code above</li>
                    <li>Create a new strong password</li>
                    <li>Confirm your new password</li>
                </ol>
            </div>

            <div class="warning">
                <strong>⚠️ Important Security Notes:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li><strong>Never share this code</strong> with anyone, including LifeLink staff</li>
                    <li><strong>Never reply to this email with your code</strong></li>
                    <li>If you didn't request a password reset, <strong>please ignore this email</strong></li>
                    <li>Your account remains secure as long as this code is not shared</li>
                </ul>
            </div>

            <p>If you have any issues resetting your password or believe this is an error, please contact our support team.</p>

            <p style="margin-top: 30px;">Thanks,<br><strong>LifeLink Blood Donation System Team</strong></p>

            <div class="footer">
                <p style="margin: 5px 0;">This is an automated email. Please do not reply to this message.</p>
                <p style="margin: 5px 0;">© 2026 LifeLink. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>

