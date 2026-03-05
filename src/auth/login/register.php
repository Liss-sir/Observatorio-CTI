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
        /* Efecto de medio círculo para la imagen - redondeado hacia la izquierda */
        .clip-half-circle {
            clip-path: ellipse(100% 100% at 100% 50%);
        }
        
        /* Sombra personalizada para el logo */
        .logo-shadow {
            filter: drop-shadow(0 20px 25px -5px rgba(57, 169, 0, 0.25)) 
                    drop-shadow(0 8px 10px -6px rgba(57, 169, 0, 0.2));
        }
        
        /* Sombra más intensa en hover */
        .logo-shadow:hover {
            filter: drop-shadow(0 25px 30px -8px rgba(57, 169, 0, 0.4)) 
                    drop-shadow(0 10px 15px -6px rgba(57, 169, 0, 0.3));
        }

        /* Animación de entrada del formulario */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Animación de salida del formulario */
        @keyframes slideOutDown {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(50px);
            }
        }

        /* Animación de entrada para la imagen */
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-form-in {
            animation: slideInUp 1s cubic-bezier(0.4, 0.0, 0.2, 1) forwards;
        }

        .animate-form-out {
            animation: slideOutDown 0.7s cubic-bezier(0.4, 0.0, 0.2, 1) forwards;
        }

        .animate-image-in {
            animation: slideInRight 1s cubic-bezier(0.4, 0.0, 0.2, 1) forwards;
        }
    </style>
</head>

<body class="h-screen bg-gray-50 text-gray-800">

<script src="https://unpkg.com/lucide@latest"></script>

<div class="flex h-full">

    <!-- LADO IZQUIERDO (FORMULARIO CON LOGO DESTACADO) -->
    <div class="flex w-full lg:w-1/2 items-start justify-center px-6 bg-white overflow-y-auto pt-6">
        <div class="w-full max-w-lg py-0 animate-form-in">
            
            <!-- Logo con sombra pronunciada -->
            <div class="flex justify-center mb-1">
                <div class="relative top-2">
                    <!-- Logo con sombras personalizadas -->
                    <img src="../../assets/img/logo-tecnnova.png"
                         class="h-24 w-auto object-contain relative z-10
                                logo-shadow transition-all duration-300
                                hover:scale-105 cursor-pointer">
                </div>
            </div>

            <!-- Título -->
            <div class="mb-6 text-center">
                <h2 class="text-2xl font-semibold">
                    Crear Cuenta
                </h2>

                <!-- Línea verde sutil -->
                <div class="h-1 w-10 bg-sena mx-auto mt-3 rounded"></div>

                <p class="text-sm text-gray-500 mt-2">
                    Regístrate para acceder al Observatorio CTI
                </p>
            </div>

            <!-- Formulario -->
            <form id="registerForm" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <!-- Representante -->
                <div>
                    <label class="block text-xs font-medium mb-1">
                        Nombre del representante legal <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="representante" placeholder="Carlos Ocampo"
                           class="w-full px-3 py-2 rounded-md border border-gray-300 text-sm
                                  focus:border-sena focus:ring-1 focus:ring-sena/30
                                  focus:outline-none transition" />
                </div>

                <!-- Empresa -->
                <div>
                    <label class="block text-xs font-medium mb-1">
                        Nombre de la empresa <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="empresa" placeholder="Ejemplo S.A.S"
                           class="w-full px-3 py-2 rounded-md border border-gray-300 text-sm
                                  focus:border-sena focus:ring-1 focus:ring-sena/30
                                  focus:outline-none transition" />
                </div>

                <!-- Email (Full width) -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium mb-1">
                        Correo electrónico <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" placeholder="ejemplo@ejemplo.com"
                           class="w-full px-3 py-2 rounded-md border border-gray-300 text-sm
                                  focus:border-sena focus:ring-1 focus:ring-sena/30
                                  focus:outline-none transition" />
                </div>

                <!-- Razón social (Full width) -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium mb-1">
                        Razón social
                    </label>
                    <input type="text" name="razon_social" placeholder="Razón social..."
                           class="w-full px-3 py-2 rounded-md border border-gray-300 text-sm
                                  focus:border-sena focus:ring-1 focus:ring-sena/30
                                  focus:outline-none transition" />
                </div>

                <!-- Tipo documento -->
                <div>
                    <label class="block text-xs font-medium mb-1">
                        Tipo de documento <span class="text-red-500">*</span>
                    </label>
                    <select name="tipo_documento"
                            class="w-full px-3 py-2 rounded-md border border-gray-300 text-sm
                                   focus:border-sena focus:ring-1 focus:ring-sena/30
                                   focus:outline-none transition">
                        <option value="">Seleccione</option>
                        <option value="CC">Cédula</option>
                        <option value="NIT">NIT</option>
                        <option value="CE">RUT</option>
                    </select>
                </div>

                <!-- Documento -->
                <div>
                    <label class="block text-xs font-medium mb-1">
                        Número de documento <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="documento" placeholder="148444112453"
                           class="w-full px-3 py-2 rounded-md border border-gray-300 text-sm
                                  focus:border-sena focus:ring-1 focus:ring-sena/30
                                  focus:outline-none transition" />
                </div>

                <!-- Contraseña -->
                <div>
                    <label class="block text-xs font-medium mb-1">
                        Contraseña <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña"
                               class="w-full px-3 py-2 pr-10 rounded-md border border-gray-300 text-sm
                                      focus:border-sena focus:ring-1 focus:ring-sena/30
                                      focus:outline-none transition" />
                        
                        <button type="button" id="togglePassword" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 
                                       text-gray-400 hover:text-sena transition">
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
                               class="w-full px-3 py-2 pr-10 rounded-md border border-gray-300 text-sm
                                      focus:border-sena focus:ring-1 focus:ring-sena/30
                                      focus:outline-none transition" />
                        
                        <button type="button" id="toggleConfirmPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 
                                       text-gray-400 hover:text-sena transition">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <!-- Botón (Full width) -->
                <button type="submit"
                        class="md:col-span-2 w-full bg-sena text-white py-2 rounded-md text-sm
                               hover:bg-[#2d8a00] transition font-medium shadow-sm
                               hover:shadow-lg hover:shadow-sena/20">
                    Registrarse
                </button>
            </form>

            <!-- Links -->
            <div class="text-center mt-8 text-sm text-gray-500 space-y-3">
                <p>
                    ¿Ya tienes una cuenta?
                    <a href="../../auth/login/login.php" 
                       class="text-sena font-medium hover:underline">
                        Inicia sesión
                    </a>
                </p>

                <a href="../../view/landing/landing.php" 
                   class="block text-gray-400 hover:text-sena transition">
                    ← Volver al inicio
                </a>
            </div>
        </div>
    </div>

    <!-- LADO DERECHO (IMAGEN EN MEDIO CÍRCULO) -->
    <div class="hidden lg:flex w-1/2 relative overflow-visible bg-[#fff] animate-image-in">
        
        <!-- Contenedor con clip-path de medio círculo -->
        <div class="absolute inset-0 clip-half-circle overflow-hidden">
            <img src="../../assets/img/sena.jpeg"
                 class="w-full h-full object-cover">
            
            <!-- Overlay con gradiente ajustado al medio círculo -->
            <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/70 to-sena/50"></div>
        </div>

        <!-- Contenido textual (ajustado ligeramente a la derecha) -->
        <div class="relative z-10 flex flex-col justify-center pl-32 pr-12 text-white max-w-xl h-full">
            <h1 class="text-3xl xl:text-4xl font-semibold leading-snug mb-6">
                Observatorio de Perfiles Tecnológicos
            </h1>

            <p class="text-base opacity-85 leading-relaxed">
                Análisis estratégico de necesidades empresariales y tecnologías emergentes 
                del sector productivo risaraldense.
            </p>

            <div class="h-1 w-14 bg-sena mt-8 rounded"></div>

            <p class="absolute bottom-8 text-xs opacity-70 tracking-wide">
                Centro de Diseño e Innovación Tecnológica Industrial · SENA 2026
            </p>
        </div>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="<?= BASE_URL ?>src/assets/js/register.js"></script>

<script>
lucide.createIcons();

// Toggle password visibility
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');

if (togglePassword && passwordInput) {
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        const icon = this.querySelector('i');
        icon.setAttribute('data-lucide', type === 'password' ? 'eye' : 'eye-off');
        lucide.createIcons();
    });
}

// Toggle confirm password visibility
const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
const confirmPasswordInput = document.getElementById('confirmPassword');

if (toggleConfirmPassword && confirmPasswordInput) {
    toggleConfirmPassword.addEventListener('click', function() {
        const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPasswordInput.setAttribute('type', type);
        
        const icon = this.querySelector('i');
        icon.setAttribute('data-lucide', type === 'password' ? 'eye' : 'eye-off');
        lucide.createIcons();
    });
}

// Animación de salida del formulario al enviar
const form = document.querySelector('form');
if (form) {
    form.addEventListener('submit', function(e) {
        const formContainer = document.querySelector('.animate-form-in');
        if (formContainer) {
            formContainer.classList.remove('animate-form-in');
            formContainer.classList.add('animate-form-out');
            
            // Evitar que se envíe inmediatamente para ver la animación
            setTimeout(() => {
                // Aquí iría el envío del formulario o redirección
            }, 700);
        }
    });
}
</script>

</body>
</html> 