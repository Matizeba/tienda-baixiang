<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Exitoso - Baixiang</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f7f7;
            color: #555555;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-top: 6px solid #e67e22;
        }
        h1 {
            color: #e67e22;
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .account-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .account-details p {
            margin: 0;
            padding: 8px 0;
            border-bottom: 1px solid #e6e6e6;
        }
        .account-details p:last-child {
            border-bottom: none;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #888888;
            text-align: center;
        }
        .footer p {
            margin: 5px 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #e67e22;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 20px;
            text-align: center;
        }
        .btn:hover {
            background-color: #d35400;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>¡Bienvenido a Baixiang!</h1>
        <p>Hola {{ $user->name }},</p>
        <p>Nos alegra que te hayas unido a nuestra comunidad. Tu registro en Baixiang se ha realizado con éxito.</p>
        
        <div class="account-details">
            <p><strong>Correo electrónico:</strong> {{ $user->email }}</p>
            <p><strong>Contraseña temporal:</strong> {{ $password }}</p>
        </div>

        <p>Puedes utilizar estos datos para iniciar sesión en nuestra tienda online. Te recomendamos cambiar tu contraseña después del primer inicio de sesión para mantener tu cuenta segura.</p>
        
        <a href="{{ route('login') }}" class="btn">Iniciar Sesión</a>

        <div class="footer">
            <p>¿Tienes alguna pregunta? Estamos aquí para ayudarte.</p>
            <p>Gracias por elegir Baixiang. ¡Esperamos que disfrutes de tu experiencia de compra!</p>
        </div>
    </div>
</body>
</html>
