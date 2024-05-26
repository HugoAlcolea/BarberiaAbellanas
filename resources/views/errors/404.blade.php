<!-- resources/views/errors/404.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 404 - Página no encontrada</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
            margin: 0;
        }
        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <img src="{{ asset('img/errors/404.png') }}" alt="Error 404 - Página no encontrada">
</body>
</html>
