<?php
//ESTO ES NUEVO
// Protección opcional
if (isset($requiereAuth) && $requiereAuth) {
    session_start();
    if (!isset($_SESSION['autenticado'])) {
        header('Location: ../../auth/login/login.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?? 'Observatorio CTI' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gray-50 font-[Inter]">

    <?php include 'components/header.php'; ?>

    <main class="max-w-7xl mx-auto px-4 py-8">
        <?php include $contenido; ?>
    </main>

    <script src="../../assets/js/auth.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>