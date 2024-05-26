<!-- resources/views/errors/401.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 401 - No autorizado</title>
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
    <img src="{{ asset('img/errors/401.jpeg') }}" alt="Error 401 - No autorizado">
</body>
</html>
