<!DOCTYPE html>
<html>

<head>
   <title>New Appointment Request</title>
   <style>
      body {
         font-family: Arial, sans-serif;
         line-height: 1.6;
         margin: 20px;
         color: #333;
      }

      h2 {
         color: #2c3e50;
         border-bottom: 2px solid #eee;
         padding-bottom: 10px;
      }

      p {
         margin: 10px 0;
      }

      strong {
         color: #2c3e50;
      }
   </style>
</head>

<body>
   <h2>New Appointment Request</h2>
   <p><strong>Name:</strong> {{ $name }}</p>
   <p><strong>Email:</strong> {{ $email }}</p>
   <p><strong>Phone:</strong> {{ $phone }}</p>
   <p><strong>Special Request:</strong> {{ $specialRequest ?? 'No special request' }}</p>
   <p><strong>Agreement:</strong>
      {{ $agreeToTerms ? 'User agreed to receive messages.' : 'User did NOT agree to receive messages.' }}</p>
</body>

</html>