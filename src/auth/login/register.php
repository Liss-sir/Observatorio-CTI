<?php
session_start();
$esAdmin = isset($_SESSION['rol_nombre']) && strtolower($_SESSION['rol_nombre']) === 'administrador';
$modoAdmin = $esAdmin && ($_GET['admin'] ?? null) == '1';
?>
<?php
$razonesSociales = [
    ['value' => 'SOCIEDAD POR ACCIONES SIMPLIFICADA', 'label' => 'Sociedad por Acciones Simplificada (SAS)'],
    ['value' => 'SOCIEDAD ANONIMA', 'label' => 'Sociedad Anónima (SA)'],
    ['value' => 'SOCIEDAD DE RESPONSABILIDAD LIMITADA', 'label' => 'Sociedad de Responsabilidad Limitada (Ltda)'],
    ['value' => 'SOCIEDAD EN COMANDITA SIMPLE', 'label' => 'Sociedad en Comandita Simple'],
    ['value' => 'SOCIEDAD EN COMANDITA POR ACCIONES', 'label' => 'Sociedad en Comandita por Acciones'],
    ['value' => 'EMPRESA UNIPERSONAL', 'label' => 'Empresa Unipersonal'],
    ['value' => 'COOPERATIVA', 'label' => 'Cooperativa'],
    ['value' => 'FUNDACION', 'label' => 'Fundación'],
    ['value' => 'ASOCIACION', 'label' => 'Asociación']
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro | Observatorio CTI</title>
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sena: '#39a900'
                    }
                }
            }
        }
    </script>
    <style>
        .custom-select {
            width: 100%;
            padding: 0.5rem 2.5rem 0.5rem 0.75rem;
            font-size: 0.875rem;
            color: #1e293b;
            background-color: white;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 0.8rem;
        }
        .custom-select:focus { outline: none; border-color: #39A900; }
        .custom-select:disabled { background-color: #f9fafb; cursor: not-allowed; opacity: 0.7; }
        
        .clip-half-circle { clip-path: ellipse(100% 100% at 100% 50%); }
        .logo-shadow {
            filter: drop-shadow(0 20px 25px -5px rgba(57, 169, 0, 0.25))
                    drop-shadow(0 8px 10px -6px rgba(57, 169, 0, 0.2));
        }
        .logo-shadow:hover {
            filter: drop-shadow(0 25px 30px -8px rgba(57, 169, 0, 0.4))
                    drop-shadow(0 10px 15px -6px rgba(57, 169, 0, 0.3));
        }
        @keyframes slideInUp { from { opacity: 0; transform: translateY(50px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideOutDown { from { opacity: 1; transform: translateY(0); } to { opacity: 0; transform: translateY(50px); } }
        @keyframes slideInRight { from { opacity: 0; transform: translateX(50px); } to { opacity: 1; transform: translateX(0); } }
        .animate-form-in { animation: slideInUp 1s cubic-bezier(0.4, 0.0, 0.2, 1) forwards; }
        .animate-form-out { animation: slideOutDown 0.7s cubic-bezier(0.4, 0.0, 0.2, 1) forwards; }
        .animate-image-in { animation: slideInRight 1s cubic-bezier(0.4, 0.0, 0.2, 1) forwards; }
    </style>
</head>
<body class="h-screen bg-gray-50 text-gray-800">
    <script src="https://unpkg.com/lucide@latest"></script>
    <div class="flex h-full">
        <!-- LADO IZQUIERDO (FORMULARIO) -->
        <div class="flex w-full lg:w-1/2 items-start justify-center px-6 bg-white overflow-y-auto pt-6">
            <div class="w-full max-w-lg py-0 animate-form-in">
                <div class="flex justify-center mb-1">
                    <div class="relative top-2">
                        <img src="../../assets/img/logo-tecnnova.png"
                             class="h-24 w-auto object-contain relative z-10 logo-shadow transition-all duration-300 hover:scale-105 cursor-pointer">
                    </div>
                </div>
                <div class="mb-6 text-center">
                    <h2 class="text-2xl font-semibold">Crear Cuenta</h2>
                    <div class="h-1 w-10 bg-sena mx-auto mt-3 rounded"></div>
                    <p class="text-sm text-gray-500 mt-2">Regístrate para acceder al Observatorio CTI</p>
                </div>

                <form id="registerForm" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <!-- Rol Elegible -->
                    <?php if ($esAdmin): ?>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium mb-1">Rol</label>
                        <select id="rolSelect" name="rol" class="custom-select w-full px-3 py-2 rounded-md border border-gray-300 text-sm focus:border-sena focus:ring-1 focus:ring-sena/30 focus:outline-none transition">
                            <option value="empresa">Empresa</option>
                            <option value="administrador" <?= $modoAdmin ? 'selected' : '' ?>>Administrador</option>
                        </select>
                        <input type="hidden" name="rol_hidden" id="rolHidden" value="<?= $modoAdmin ? 'administrador' : 'empresa' ?>">
                    </div>
                    <?php else: ?>
                        <input type="hidden" name="rol" value="empresa">
                    <?php endif; ?>

                    <!-- Representante Legal -->
                    <div data-role="empresa" class="transition-all duration-200">
                        <label class="block text-xs font-medium mb-1">
                            Nombre del representante legal <span class="text-red-500">*</span>
                        </label>
                        <input id="representanteInput" type="text" name="representante" placeholder="Carlos Ocampo"
                               class="w-full px-3 py-2 rounded-md border border-gray-300 text-sm focus:border-sena focus:ring-1 focus:ring-sena/30 focus:outline-none transition" />
                    </div>

                    <!-- Empresa / Admin -->
                    <div id="empresaContainer">
                        <label id="empresaLabel" class="block text-xs font-medium mb-1">
                            Nombre de la empresa <span class="text-red-500">*</span>
                        </label>
                        <input id="empresaInput" type="text" name="empresa" placeholder="Ejemplo S.A.S"
                               class="w-full px-3 py-2 rounded-md border border-gray-300 text-sm focus:border-sena focus:ring-1 focus:ring-sena/30 focus:outline-none transition" />
                    </div>

                    <!-- Email -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium mb-1">
                            Correo electrónico <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" placeholder="ejemplo@ejemplo.com"
                               class="w-full px-3 py-2 rounded-md border border-gray-300 text-sm focus:border-sena focus:ring-1 focus:ring-sena/30 focus:outline-none transition" />
                    </div>

                    <!-- Razón Social -->
                    <div data-role="empresa" class="md:col-span-2 transition-all duration-200">
                        <label class="block text-xs font-medium mb-1">Razón social</label>
                        <select id="razonSocialSelect" name="razon_social" class="custom-select w-full px-3 py-2 rounded-md border border-gray-300 text-sm focus:border-sena focus:ring-1 focus:ring-sena/30 focus:outline-none transition">
                            <option value="">Seleccione el tipo de empresa (opcional)</option>
                            <?php foreach ($razonesSociales as $razon): ?>
                                <option value="<?= htmlspecialchars($razon['value']) ?>"><?= htmlspecialchars($razon['label']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Tipo documento -->
                    <div>
                        <label class="block text-xs font-medium mb-1">
                            Tipo de documento <span class="text-red-500">*</span>
                        </label>
                        <select name="tipo_documento" class="custom-select w-full px-3 py-2 rounded-md border border-gray-300 text-sm focus:border-sena focus:ring-1 focus:ring-sena/30 focus:outline-none transition">
                            <option value="">Seleccione</option>
                            <option value="CC">CC</option>
                            <option value="NIT">NIT</option>
                            <option value="RUT">RUT</option>
                            <option value="CE">CE</option>
                            <option value="TI">TI</option>
                            <option value="PASAPORTE">Pasaporte</option>
                        </select>
                    </div>

                    <!-- Documento -->
                    <div>
                        <label class="block text-xs font-medium mb-1">
                            Número de documento <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="documento" placeholder="148444112453"
                               class="w-full px-3 py-2 rounded-md border border-gray-300 text-sm focus:border-sena focus:ring-1 focus:ring-sena/30 focus:outline-none transition" />
                    </div>

                    <!-- Contraseña -->
                    <div>
                        <label class="block text-xs font-medium mb-1">
                            Contraseña <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña"
                                   class="w-full px-3 py-2 pr-10 rounded-md border border-gray-300 text-sm focus:border-sena focus:ring-1 focus:ring-sena/30 focus:outline-none transition" />
                            <button type="button" id="togglePassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-sena transition">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Confirmar contraseña -->
                    <div>
                        <label class="block text-xs font-medium mb-1">
                            Confirmar Contraseña <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="confirmPassword" name="confirm_password" placeholder="Confirma tu contraseña"
                                   class="w-full px-3 py-2 pr-10 rounded-md border border-gray-300 text-sm focus:border-sena focus:ring-1 focus:ring-sena/30 focus:outline-none transition" />
                            <button type="button" id="toggleConfirmPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-sena transition">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Botón -->
                    <button type="submit" class="md:col-span-2 w-full bg-sena text-white py-2 rounded-md text-sm hover:bg-[#2d8a00] transition font-medium shadow-sm hover:shadow-lg hover:shadow-sena/20">
                        Registrarse
                    </button>
                </form>

                <div class="text-center mt-8 text-sm text-gray-500 space-y-3">
                    <p>¿Ya tienes una cuenta? <a href="../../auth/login/login.php" class="text-sena font-medium hover:underline">Inicia sesión</a></p>
                    <a href="../../view/landing/landing.php" class="block text-gray-400 hover:text-sena transition">← Volver al inicio</a>
                </div>
            </div>
        </div>

        <!-- LADO DERECHO (IMAGEN) -->
        <div class="hidden lg:flex w-1/2 relative overflow-visible bg-[#fff] animate-image-in">
            <div class="absolute inset-0 clip-half-circle overflow-hidden">
                <img src="../../assets/img/sena.jpeg" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/70 to-sena/50"></div>
            </div>
            <div class="relative z-10 flex flex-col justify-center pl-32 pr-12 text-white max-w-xl h-full">
                <h1 class="text-3xl xl:text-4xl font-semibold leading-snug mb-6">Observatorio de Perfiles Tecnológicos</h1>
                <p class="text-base opacity-85 leading-relaxed">Análisis estratégico de necesidades empresariales y tecnologías emergentes del sector productivo risaraldense.</p>
                <div class="h-1 w-14 bg-sena mt-8 rounded"></div>
                <p class="absolute bottom-8 text-xs opacity-70 tracking-wide">Centro de Diseño e Innovación Tecnológica Industrial · SENA 2026</p>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const rolSelect = document.getElementById('rolSelect');
            const representanteInput = document.getElementById('representanteInput');
            const empresaInput = document.getElementById('empresaInput');
            const empresaLabel = document.getElementById('empresaLabel');
            const razonSocialSelect = document.getElementById('razonSocialSelect');
            const empresaContainer = document.getElementById('empresaContainer');

            function actualizarCampos() {
                const esAdmin = rolSelect.value === 'administrador';

                if (esAdmin) {
                    empresaContainer.classList.add('md:col-span-2');
                } else {
                    empresaContainer.classList.remove('md:col-span-2');
                }

                // Representante Legal
                representanteInput.value = esAdmin ? 'Administrador' : '';
                representanteInput.readOnly = esAdmin;
                representanteInput.placeholder = esAdmin ? '' : 'Carlos Ocampo';
                representanteInput.classList.toggle('bg-gray-50', esAdmin);
                representanteInput.classList.toggle('cursor-not-allowed', esAdmin);

                // Nombre Empresa / Admin
                empresaLabel.innerHTML = esAdmin 
                    ? 'Nombre admin <span class="text-red-500">*</span>' 
                    : 'Nombre de la empresa <span class="text-red-500">*</span>';
                
                // Nunca se autocompleta, siempre queda vacío y editable
                empresaInput.value = ''; 
                empresaInput.readOnly = false;
                empresaInput.placeholder = esAdmin ? 'Nombre del administrador' : 'Ejemplo S.A.S';
                empresaInput.classList.remove('bg-gray-50', 'cursor-not-allowed');

                // Razón Social
                razonSocialSelect.disabled = esAdmin;
                razonSocialSelect.value = esAdmin ? 'SOCIEDAD POR ACCIONES SIMPLIFICADA' : '';
                razonSocialSelect.classList.toggle('bg-gray-50', esAdmin);
                razonSocialSelect.classList.toggle('cursor-not-allowed', esAdmin);
            }

            rolSelect.addEventListener('change', actualizarCampos);
            actualizarCampos();
        });
    </script>

    <script src="../../assets/js/registro/register.js"></script>
    <?php include __DIR__ . '/modal_registro_confirmacion.php'; ?>
</body>
</html>