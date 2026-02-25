<?php
include __DIR__ . '/../../includes/header.php';
$error = $_GET['error'] ?? null;
?>

<!-- Tailwind -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Lucide -->
<script src="https://unpkg.com/lucide@latest"></script>

<div class="min-h-[calc(100vh-64px)] bg-gray-100 flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        <!-- Logo / Título -->
        <div class="flex flex-col items-center mb-6">
            <div class="bg-green-600 text-white w-12 h-12 flex items-center justify-center rounded-lg text-xl font-bold">
                S
            </div>
            <h1 class="text-2xl font-semibold mt-4">Iniciar Sesión</h1>
            <p class="text-gray-500 text-sm">Accede a tu panel de gestión</p>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6">

            <!-- Tabs -->
            <div class="flex bg-gray-100 rounded-lg p-1 mb-5 text-sm">

                <button type="button" data-role="empresa"
                    class="tab-btn flex-1 flex items-center justify-center gap-2 bg-white rounded-md py-2 shadow-sm font-medium">
                    <i data-lucide="building-2" class="w-4 h-4"></i>
                    Empresa
                </button>

                <button type="button" data-role="persona"
                    class="tab-btn flex-1 flex items-center justify-center gap-2 text-gray-500 py-2"> 
                    <i data-lucide="user" class="w-4 h-4"></i>
                    Persona
                </button>

                <button type="button" data-role="admin"
                    class="tab-btn flex-1 flex items-center justify-center gap-2 text-gray-500 py-2">
                    <i data-lucide="shield" class="w-4 h-4"></i>
                    Admin
                </button>

            </div>

            <?php if ($error): ?>
                <div class="bg-red-50 border border-red-200 text-red-600 text-sm p-3 rounded-lg mb-4">
                    Credenciales incorrectas
                </div>
            <?php endif; ?>

            <!-- ================= EMPRESA ================= -->
            <form method="POST" id="form-empresa" class="form-role space-y-4">

                <div>
                    <label class="text-sm text-gray-600">Nombre de la empresa</label>
                    <input type="text" name="empresa"
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
                </div>

                <div>
                    <label class="text-sm text-gray-600">NIT</label>
                    <input type="text" name="nit"
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
                </div>

                <div>
                    <label class="text-sm text-gray-600">Correo electrónico</label>
                    <input type="email" name="email"
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
                </div>

                <div>
                    <label class="text-sm text-gray-600">Contraseña</label>
                    <input type="password" name="password"
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
                </div>

                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-medium flex items-center justify-center gap-2">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    Ingresar como Empresa
                </button>

            </form>

            <!-- ================= PERSONA ================= -->
            <form method="POST" id="form-persona" class="form-role space-y-4 hidden">

                <div>
                    <label class="text-sm text-gray-600">Número de documento</label>
                    <input type="text" name="documento"
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
                </div>

                <div>
                    <label class="text-sm text-gray-600">Correo electrónico</label>
                    <input type="email" name="email"
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
                </div>

                <div>
                    <label class="text-sm text-gray-600">Contraseña</label>
                    <input type="password" name="password"
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
                </div>

                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-medium flex items-center justify-center gap-2">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    Ingresar como Persona
                </button>

            </form>

            <!-- ================= ADMIN ================= -->
            <form method="POST" id="form-admin" class="form-role space-y-4 hidden">

                <div>
                    <label class="text-sm text-gray-600">Correo electrónico</label>
                    <input type="email" name="email"
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
                </div>

                <div>
                    <label class="text-sm text-gray-600">Contraseña</label>
                    <input type="password" name="password"
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
                </div>

                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-medium flex items-center justify-center gap-2">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    Ingresar como Admin
                </button>

            </form>

        </div>
    </div>
</div>

<script src="/observatorio/Observatorio-CTI/src/assets/js/login.js"></script>

<script>
    lucide.createIcons();
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>