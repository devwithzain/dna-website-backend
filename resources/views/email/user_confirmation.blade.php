<!DOCTYPE html>
<html>
<head>
    <title>Thank You for Your Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        p {
            margin-bottom: 15px;
        }
        .signature {
            margin-top: 30px;
            color: #2c5282;
        }
        .header {
            color: #2d3748;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <p class="header">Dear {{ $name }},</p>
    
    <p>Thank you for reaching out to us! We have received your request and will get back to you shortly.</p>
    
    <div class="signature">
        <p>Best regards,</p>
        <p><strong>Mobile DNA Team</strong></p>
    </div>
</body>
</html>