<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Under Maintenance</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #333;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Nunito', sans-serif;
            margin: 0;
        }
        .maintenance-container {
            text-align: center;
            padding: 50px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
        }
        .maintenance-container h1 {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #212529;
        }
        .maintenance-container p {
            font-size: 1.1rem;
            color: #6c757d;
            margin-bottom: 30px;
        }
        .maintenance-icon {
            font-size: 6rem;
            margin-bottom: 20px;
            color: #007bff;
        }
        .btn-home {
            color: #fff;
            background-color: #f47b22;
            border-color: #f47b22;
            font-size: 1rem;
            padding: 15px 30px;
            border-radius: 30px;
            transition: all 0.3s;
        }
        .btn-home:hover {
            color: #f47b22;
            background-color: #fff;
            border-color: #f47b22;
            box-shadow: 0 0 12px rgba(244, 123, 34, 0.3);
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-icon">🚧</div>
        <h1>We're Under Maintenance</h1>
        <p>We are currently working hard to bring you the best experience. Please stay tuned!</p>
        <a href="/" class="btn btn-home">Go to Homepage</a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
