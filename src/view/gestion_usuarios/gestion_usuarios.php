<?php
include __DIR__ . '../../../includes/header.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Usuarios</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="../../../assets/css/output.css">
  <link rel="stylesheet" href="../../assets/css/globals.css">
  <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-gray-100">
  <div class="max-w-[82rem] mx-auto px-4 py-8 lg:px-8">
    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Gestión de Usuarios</h1>
            <p class="text-gray-500 text-sm">Administra los usuarios registrados en la plataforma</p>
        </div>
    </div>

    <!-- CARDS -->
    <div class="grid grid-cols-3 gap-4 mb-6">

        <div class="rounded-lg border-2 border-emerald-500 bg-emerald-50 p-4">
            <p id="total-usuarios" class="text-2xl font-bold text-gray-900">
            0
            </p>
            <p class="text-sm text-gray-500">
            Total usuarios
            </p>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white p-4">
            <p id="usuarios-activos" class="text-2xl font-bold text-gray-900">
            0
            </p>
            <p class="text-sm text-gray-500">
            Activos
            </p>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white p-4">
            <p id="usuarios-inactivos" class="text-2xl font-bold text-gray-900">
            0
            </p>
            <p class="text-sm text-gray-500">
            Inactivos
            </p>
        </div>

    </div>

    <!-- BUSCADOR -->
    <div class="flex-1 items-center gap-3 mb-6">
      <input
        type="text"
        placeholder="Buscar usuario por nombre, email o empresa..."
        class="w-full rounded-lg border border-gray-200 bg-white py-2 pl-10 pr-4 text-sm focus:border-emerald-500 focus:outline-none"
      />
    </div>

    <!-- FILTRO -->
    <div class="flex items-center gap-2 mb-6">
      <select
        class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm"
      >
        <option>Todos</option>
        <option>Empresa</option>
        <option>Emprendimiento</option>
        <option>Admin</option>
      </select>
    </div>

    <!-- TABLA -->
    <div class="rounded-lg border border-gray-200 bg-white overflow-hidden">
      <table class="w-full text-sm">
        <thead class="border-b border-gray-200 bg-gray-50">
          <tr>
            <th class="px-4 py-3 text-left font-medium text-gray-500">
              Usuario
            </th>
            <th class="px-4 py-3 text-left font-medium text-gray-500">
              Empresa
            </th>
            <th class="px-4 py-3 text-left font-medium text-gray-500">
              Perfiles
            </th>
            <th class="px-4 py-3 text-left font-medium text-gray-500">
              Estado
            </th>
            <th class="px-4 py-3 text-left font-medium text-gray-500">
              Acciones
            </th>
          </tr>
        </thead>
        <tbody id="tabla-usuarios">
        </tbody>
      </table>
    </div>
  </div>
  <!-- JS -->
<script src="../../assets/js/usuarios.js"></script>
</body>
</html>
<?php include 'modal_deshabilitar_usuario.php'; ?>
<?php include 'modal_habilitar_usuario.php'; ?>
<?php include __DIR__ . '../../../includes/footer.php'; ?>