<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cuenta Verificada</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa, #e4ecf7);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background: #ffffff;
            border-radius: 16px;
            padding: 40px 30px;
            max-width: 420px;
            width: 90%;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .icon {
            font-size: 60px;
            margin-bottom: 20px;
        }

        .icon.success {
            color: #28a745;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }

        p {
            font-size: 15px;
            color: #666;
            margin-bottom: 25px;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            background: #39A900; /* SENA */
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            transition: 0.3s;
        }

        .btn:hover {
            background: #2e8700;
        }

        @media (max-width: 480px) {
            .card {
                padding: 30px 20px;
            }

            h1 {
                font-size: 20px;
            }

            p {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="card">
    <div class="icon success">✔️</div>

    <h1>¡Cuenta verificada!</h1>

    <p>
        Tu correo electrónico ha sido verificado correctamente.  
        Ya puedes iniciar sesión en la plataforma.
    </p>

    <a href="../login/login.php" class="btn">
        Ir al inicio de sesión
    </a>
</div>

</body>
</html>