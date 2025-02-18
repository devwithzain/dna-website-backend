<!DOCTYPE html>
<html>

<head>
   <title>New Newsletter Subscriber</title>
   <style>
      body {
         font-family: Arial, sans-serif;
         margin: 20px;
         color: #333;
      }

      h2 {
         color: #2c3e50;
         border-bottom: 2px solid #eee;
         padding-bottom: 10px;
      }
   </style>
</head>

<body>
   <h2>New Newsletter Subscription</h2>
   <p><strong>Email:</strong> {{ $subscriber->email }}</p>
   <p>A new user has subscribed to the newsletter.</p>
</body>

</html>