<?php
$token = $_GET['token'] ?? '';
?>

<script src="../../assets/js/login.js"></script>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Cambiar contraseña</title>
  <link rel="stylesheet" href="../../assets/css/style_toast_alert.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
    body {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #f5f7fa, #e4ecf7);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    .card {
        background: #fff;
        padding: 40px 30px;
        border-radius: 16px;
        width: 90%;
        max-width: 420px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .card input[type="password"] {
        width: 90%;
        padding: 12px;
        margin-bottom: 15px;
        border-radius: 8px;
        border: 1px solid #ddd;
        transition: all 0.2s ease;
    }
    /* Estilo focus más específico */
    .card input[type="password"]:focus {
        border-color: #39A900;
        outline: none;
        box-shadow: 0 0 0 2px rgba(57, 169, 0, 0.2);
    }
    #btnCambiarPassword {
        width: 70%;
        padding: 12px;
        background: #39A900;
        color: #fff;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        margin-top: 12px;
    }
    .msg {
        margin-top: 10px;
        font-size: 13px;
    }
</style>

</head>
    <body>

        <div class="card">

        <h2>Nueva contraseña</h2>

            <input type="password" id="password" placeholder="Nueva contraseña">
            <input type="password" id="confirmar_password" placeholder="Confirmar contraseña">

        <button id="btnCambiarPassword">Cambiar contraseña</button>

        <div class="msg" id="msg"></div>

</div>

    </body>
</html>