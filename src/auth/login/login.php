<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login | Observatorio CTI</title>

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
        /* Efecto de medio círculo para la imagen */
        .clip-half-circle {
            clip-path: ellipse(100% 100% at 0% 50%);
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

        .animate-form-in {
            animation: slideInUp 1s cubic-bezier(0.4, 0.0, 0.2, 1) forwards;
        }

        .animate-form-out {
            animation: slideOutDown 0.7s cubic-bezier(0.4, 0.0, 0.2, 1) forwards;
        }

        /* Animación de entrada para la imagen */
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-image-in {
            animation: slideInLeft 1s cubic-bezier(0.4, 0.0, 0.2, 1) forwards;
        }
    </style>
</head>

<body class="h-screen bg-gray-50 text-gray-800">

<script src="https://unpkg.com/lucide@latest"></script>

<div class="flex h-full">

    <!-- LADO IZQUIERDO (IMAGEN EN MEDIO CÍRCULO) -->
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

    <!-- LADO DERECHO (FORMULARIO CON LOGO DESTACADO) -->
    <div class="flex w-full lg:w-1/2 items-start justify-center px-8 bg-white overflow-y-auto pt-24">
        <div class="w-full max-w-sm animate-form-in" id="formContainer">
            
            <!-- Logo con sombra pronunciada -->
            <div class="flex justify-center">
                <div class="relative top-3">
                    
                    <!-- Logo con sombras personalizadas -->
                    <img src="../../assets/img/logo-tecnnova.png"
                         class="h-28 w-auto object-contain relative z-10
                                logo-shadow transition-all duration-300
                                hover:scale-105 cursor-pointer">
                </div>
            </div>

            <!-- Título -->
            <div class="mb-8 text-center">
                <h2 class="text-2xl font-semibold">
                    Iniciar sesión
                </h2>

                <!-- Línea verde sutil -->
                <div class="h-1 w-10 bg-sena mx-auto mt-3 rounded"></div>

                <p class="text-sm text-gray-500 mt-3">
                    Ingresa tus credenciales para acceder al Observatorio CTI
                </p>
            </div>

            <!-- Formulario -->
            <form id="loginForm" class="space-y-6">
                <!-- Email -->
                <div>
                    <label class="block text-sm mb-2 font-medium">
                        Correo electrónico
                    </label>
                    <input type="email"
                           id="correo"
                           name="correo"
                           placeholder="correo@ejemplo.com"
                           required
                           class="w-full px-4 py-3 rounded-md border border-gray-300 
                                  focus:border-sena focus:ring-1 focus:ring-sena/30
                                  focus:outline-none transition">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm mb-2 font-medium">
                        Contraseña
                    </label>
                    <div class="relative">
                        <input type="password"
                               id="password"
                               name="password"
                               placeholder="Ingresa tu contraseña"
                               required
                               class="w-full px-4 py-3 pr-12 rounded-md border border-gray-300 
                                      focus:border-sena focus:ring-1 focus:ring-sena/30
                                      focus:outline-none transition">

                        <button type="button"
                                id="togglePassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 
                                       text-gray-400 hover:text-sena transition">
                            <i data-lucide="eye" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <!-- Mensaje de error -->
                <div id="errorMensaje" class="hidden text-red-500 text-sm text-center bg-red-50 p-2 rounded"></div>

                <!-- Botón -->
                <button type="submit"
                        id="btnSubmit"
                        class="w-full bg-sena text-white py-3 rounded-md 
                               hover:bg-[#2d8a00] transition font-medium shadow-sm
                               hover:shadow-lg hover:shadow-sena/20
                               disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center gap-2">
                    <span id="btnText">Ingresar</span>
                    <span id="btnLoading" class="hidden flex items-center gap-2">
                        <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                        Cargando...
                    </span>
                </button>
                <a href="../../auth/login/rec_contra.php"  
                    class="block text-sena hover:text-sena-strong transition">
                        Olvidaste tu contraseña?
                </a>
            </form>

            <!-- Links -->
            <div class="text-center mt-8 text-sm text-gray-500 space-y-3">
                <p>
                    ¿No tienes una cuenta?
                    <a href="../../auth/login/register.php" 
                       class="text-sena font-medium hover:underline">
                        Regístrate
                    </a>
                </p>

                <a href="../../view/landing/landing.php" 
                   class="block text-gray-400 hover:text-sena transition">
                    ← Volver al inicio
                </a>
            </div>
        </div>
    </div>
</div>

<script src="../../assets/js/login.js"></script>
 <script>
    lucide.createIcons();
</script>
</body>
</html>