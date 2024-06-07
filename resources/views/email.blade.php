<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envío de Prueba</title>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img src="{{ asset('img/HACEM.png') }}" alt="Logo HACEM" class="logo">
        </div>
        <br>
        <h1>Hola, {{ $name }}</h1>
        <p>Tu cuenta en HACEM - SENA CIDM ha sido creada. Estos son tus datos para el ingreso a la plataforma:</p>
        <br>
        <p><strong>Correo:</strong> {{ $email }}</p>
        <p><strong>Contraseña Temporal:</strong> {{ $password }}</p>
        <br>
        <p>Por favor, cambia tu contraseña después de iniciar sesión.</p>
    </div>
</body>
</html>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        color: #333;
        padding: 20px;
        margin: 0;
    }

    .email-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
    }

    .logo {
        max-width: 100%;
        height: auto;
    }

    h1 {
        color: #e78637;
        font-size: 24px;
        margin-top: 20px;
        margin-bottom: 10px;
    }

    p {
        margin-bottom: 10px;
        line-height: 1.5;
    }

    strong {
        font-weight: bold;
    }
</style>
