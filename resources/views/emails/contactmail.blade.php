<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Mail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background-color: #0d6efd;
            color: #ffffff;
            text-align: center;
            padding: 20px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .email-body {
            padding: 20px;
            color: #212529;
        }

        .email-body ul {
            list-style-type: none;
            padding: 0;
        }

        .email-body ul li {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #e9ecef;
        }

        .btn-primary {
            text-decoration: none;
            font-weight: 600;
        }

        .email-footer {
            text-align: center;
            padding: 10px;
            font-size: 12px;
            background-color: #f8f9fa;
            color: #6c757d;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <p>Name : {{ $contactMessage['name'] }}</p> <br>
        <p>Email : {{ $contactMessage['email'] }}</p> <br>
        <p>Phone : {{ $contactMessage['phone'] }}</p> <br>
        <p>Comment : {{ $contactMessage['comment'] }}</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
