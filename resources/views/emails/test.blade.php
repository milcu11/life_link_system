<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Test Email</title>
</head>
<body>
    <h2>Email Test</h2>
    <p>{{ $message ?? 'This is a test email from LifeLink' }}</p>
    <p>Sent at: {{ now()->format('Y-m-d H:i:s') }}</p>
</body>
</html>