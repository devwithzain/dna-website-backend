<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>{{ $subject }}</title>
   <style>
   body,
   p,
   h1,
   h2,
   h3,
   strong {
      font-family: sans-serif;
   }
   </style>
</head>

<body
   style="font-family:  sans-serif; line-height: 1.6; margin: 0; padding: 20px; background-color: #f9f9f9; color: #333;">

   <!-- Email Container -->
   <div
      style="max-width: 600px; margin: auto; background-color: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">

      <!-- Header -->
      <h1 style="font-size: 24px; color: #007bff; text-align: center; margin-bottom: 20px;">{{ $subject }}</h1>

      <!-- User Details -->
      <p style="margin-bottom: 10px; font-size: 16px;">
         <strong style="color: #555;">Name:</strong> {{ $name }}
      </p>
      <p style="margin-bottom: 10px; font-size: 16px;">
         <strong style="color: #555;">Email:</strong> {{ $email }}
      </p>
      <p style="margin-bottom: 20px; font-size: 16px;">
         <strong style="color: #555;">Phone:</strong> {{ $phone }}
      </p>

      <!-- Appointment Details -->
      <h2
         style="font-size: 20px; color: #333; border-bottom: 2px solid #007bff; padding-bottom: 5px; margin-bottom: 15px;">
         Appointment Details:</h2>
      <p style="margin-bottom: 10px; font-size: 16px;">
         <strong style="color: #555;">Selected Service:</strong> {{ $selectedOption }}
      </p>
      <p style="margin-bottom: 20px; font-size: 16px;">
         <strong style="color: #555;">Preferred Date:</strong> {{ $selectedDate }}
      </p>

      <!-- Special Request -->
      <h3 style="font-size: 18px; color: #007bff; margin-bottom: 10px;">Special Request:</h3>
      <p style="font-size: 16px; color: #333;">{{ $specialRequest }}</p>

   </div>
</body>

</html>