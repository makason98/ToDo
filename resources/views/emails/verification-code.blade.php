<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9fafb; padding: 40px 0;">
    <div style="max-width: 400px; margin: 0 auto; background: white; border-radius: 8px; padding: 40px; text-align: center;">
        <h1 style="font-size: 24px; color: #111827; margin-bottom: 8px;">Verify your email</h1>
        <p style="color: #6b7280; font-size: 14px; margin-bottom: 32px;">Enter this code to complete your registration:</p>

        <div style="background: #f3f4f6; border-radius: 8px; padding: 20px; margin-bottom: 32px;">
            <span style="font-size: 32px; font-weight: 700; letter-spacing: 8px; color: #111827;">{{ $code }}</span>
        </div>

        <p style="color: #9ca3af; font-size: 12px;">This code expires in 10 minutes.</p>
    </div>
</body>
</html>
