<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- JS -->
    <script src="/observatorio/Observatorio-CTI/src/assets/js/login.js"></script>
</head>
<body class="h-screen bg-gray-100">
<script src="https://unpkg.com/lucide@latest"></script>


<div class="flex h-full">

    <!-- LADO IZQUIERDO (IMAGEN + TEXTO) -->
    <div class="hidden lg:flex w-1/2 relative">

        <img src="../../assets/img/sena.jpeg"
            class="absolute inset-0 w-full h-full object-cover">

        <div class="absolute inset-0 bg-black/60"></div>

        <div class="relative z-10 flex flex-col justify-center px-16 text-white">
            
            <h1 class="text-4xl font-bold leading-tight mb-6">
                Observatorio de Perfiles Tecnológicos
            </h1>

            <p class="text-lg opacity-80 max-w-md">
                Necesidades empresariales clasificadas por líneas tecnológicas
                y tecnologías emergentes del sector productivo risaraldense.
            </p>

            <p class="absolute bottom-8 text-xs opacity-70">
                Centro de Diseño e Innovación Tecnológica Industrial - SENA 2026
            </p>

        </div>
    </div>

    <!-- LADO DERECHO (FORMULARIO) -->
    <div class="flex w-full lg:w-1/2 items-center justify-center px-8 bg-white">

        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl border-2 border-gray-150 p-10 transition hover:shadow-3xl">

            <h2 class="text-2xl font-semibold mb-2 text-center text-[30px]">
                Iniciar Sesión
            </h2>

            <p class="text-gray-500 mb-8 text-center text-[18px]">
                Accede a tu panel de gestión empresarial
            </p>

            <form class="space-y-6">

                <!-- Email -->
                <div>
                    <label class="block text-sm mb-2">
                        Correo electrónico
                    </label>

                    <div class="relative">
                        <input type="email"
                               placeholder="correo@ejemplo.com"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:outline-none bg-gray-100">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm mb-2">
                        Contraseña
                    </label>

                    <div class="relative">
                        <input type="password"
                            id="password"
                            placeholder="Ingresa tu contraseña"
                            class="w-full px-4 py-3 pr-12 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:outline-none bg-gray-100">

                        <!-- Botón ojo -->
                        <button type="button"
                                id="togglePassword"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-green-600 transition">

                            <i data-lucide="eye" class="w-5 h-5"></i>

                        </button>
                    </div>
                </div>

                <!-- Botón -->
                <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-medium transition">
                    Ingresar
                </button>

            </form>

            <div class="text-center mt-6 text-sm text-gray-500">
                No tienes una cuenta?
                <a href="../../auth/login/register.php" class="text-green-600 hover:underline">
                    Regístrate aquí
                </a>
            </div>

            <div class="text-center mt-4">
                <a href="../../view/landing/landing.php" class="text-sm text-green-600 hover:underline">
                    ← Volver al inicio
                </a>
            </div>

        </div>

    </div>

</div>

</body>
</html>