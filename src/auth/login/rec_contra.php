<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña</title>
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
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }

        .icon {
            font-size: 50px;
            margin-bottom: 15px;
        }

        h1 {
            margin: 0;
            font-size: 22px;
            color: #333;
        }

        p {
            font-size: 14px;
            color: #666;
            margin: 15px 0 25px;
        }

        input {
            width: 90%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 14px;
            outline: none;
        }

        input:focus {
            border-color: #39A900;
        }

        #email {
            width: 40%;
            padding: 12px;
            margin-top: 10px;
            background: #39A900;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: 0.3s;
        }

        #email:hover {
            background: #2e8700;
        }

        .link {
            display: block;
            margin-top: 15px;
            font-size: 13px;
            color: #666;
            text-decoration: none;
        }

        .link:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .card {
                padding: 30px 20px;
            }

            h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>

<div class="card">

    <div class="icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="none" stroke="#39A900" stroke-width="2" viewBox="0 0 24 24">
            <rect x="3" y="11" width="18" height="11" rx="2"></rect>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
        </svg>
    </div>

    <h1>Recuperar contraseña</h1>

    <p>
        Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
    </p>

    <form id="form-recuperar">
        <input type="email" id="correo" required>
        <button type="submit">Enviar enlace</button>
    </form>

    <a href="../login/login.php" class="link">
        ← Volver al inicio de sesión
    </a>

</div>
<script src="../../assets/js/login.js"></script>
</body>
</html>