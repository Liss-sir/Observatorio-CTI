document.addEventListener("DOMContentLoaded", () => {

    lucide.createIcons();

    // ========== REFERENCIAS DOM ==========
    const loginForm = document.getElementById("loginForm");
    const correoInput = document.getElementById("correo");
    const passwordInput = document.getElementById("password");
    const toggleBtn = document.getElementById("togglePassword");
    const errorMensaje = document.getElementById("errorMensaje");
    const btnSubmit = document.getElementById("btnSubmit");
    const btnText = document.getElementById("btnText");
    const btnLoading = document.getElementById("btnLoading");
    const formContainer = document.getElementById("formContainer");

    // ========== CONFIGURACIÓN ==========
    const API_URL = "../../controllers/LogController.php?accion=login";
    
    // Página por defecto si el servidor no envía redirect
    const DEFAULT_REDIRECT = "../../view/landing/landing.php";

    // ========== TOGGLE PASSWORD ==========
    if (toggleBtn && passwordInput) {
        toggleBtn.addEventListener("click", () => {
            const isPassword = passwordInput.type === "password";
            passwordInput.type = isPassword ? "text" : "password";

            toggleBtn.innerHTML = isPassword
                ? '<i data-lucide="eye-off" class="w-5 h-5"></i>'
                : '<i data-lucide="eye" class="w-5 h-5"></i>';

            lucide.createIcons();
        });
    }

    // ========== LOGIN FORM ==========
    if (!loginForm) {
        console.error("❌ No se encontró el formulario de login");
        return;
    }

    loginForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        // Limpiar error previo
        ocultarError();

        // Obtener valores
        const correo = correoInput?.value.trim();
        const password = passwordInput?.value;

        // Validación básica
        if (!correo || !password) {
            mostrarError("Correo y contraseña son requeridos");
            return;
        }

        // Validar formato de correo
        if (!validarEmail(correo)) {
            mostrarError("Por favor ingresa un correo válido");
            return;
        }

        // Estado de carga
        setLoading(true);

        try {
            console.log("📤 Enviando petición a:", API_URL);
            console.log("📧 Correo:", correo);
            
            const response = await fetch(API_URL, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ 
                    correo: correo, 
                    password: password 
                }),
            });

            console.log("📥 Status:", response.status);

            // Verificar si la respuesta es JSON
            const contentType = response.headers.get("content-type");
            console.log("📄 Content-Type:", contentType);

            if (!contentType || !contentType.includes("application/json")) {
                const text = await response.text();
                console.error("❌ Respuesta no JSON:", text.substring(0, 500));
                throw new Error("El servidor no retornó JSON válido. Revisa la ruta del controller.");
            }

            const data = await response.json();
            console.log("✅ Datos recibidos:", data);

            if (data.success === true) {
                // Login exitoso
                console.log("🎉 Login exitoso!");
                manejarLoginExitoso(data);
            } else {
                // Error de autenticación
                console.log("❌ Error del servidor:", data.error);
                mostrarError(data.error || "Error al iniciar sesión");
                setLoading(false);
            }

        } catch (error) {
            console.error("💥 Error en login:", error);
            mostrarError(error.message || "Error de conexión. Verifica tu conexión a internet.");
            setLoading(false);
        }
    });

    // ========== FUNCIONES AUXILIARES ==========

    function manejarLoginExitoso(data) {
        console.log("🔄 Iniciando redirección...");
        
        // Guardar datos del usuario en sessionStorage
        if (data.usuario) {
            sessionStorage.setItem("usuario", JSON.stringify(data.usuario));
            sessionStorage.setItem("id_usuario", data.usuario.id_usuario);
            sessionStorage.setItem("correo", data.usuario.correo);
            sessionStorage.setItem("rol", data.usuario.rol_nombre);
            console.log("💾 Datos guardados en sessionStorage");
        }

        // Determinar URL de redirección
        const redirectUrl = "../../view/landing/landing.php" || DEFAULT_REDIRECT;
        console.log("🔗 URL de redirección:", redirectUrl);

        // Animación de salida
        if (formContainer) {
            formContainer.classList.remove("animate-form-in");
            formContainer.classList.add("animate-form-out");
            console.log("🎬 Animación iniciada");
        }

        // Redireccionar después de la animación
        setTimeout(() => {
            console.log("🏃 Redirigiendo ahora a:", redirectUrl);
            window.location.assign(redirectUrl); // Usar assign en lugar de href
        }, 500); // Aumentado a 500ms para asegurar la animación
    }

    function mostrarError(mensaje) {
        if (!errorMensaje) return;
        errorMensaje.textContent = mensaje;
        errorMensaje.classList.remove("hidden");
        console.log("⚠️ Error mostrado:", mensaje);
    }

    function ocultarError() {
        if (errorMensaje) {
            errorMensaje.classList.add("hidden");
            errorMensaje.textContent = "";
        }
    }

    function setLoading(loading) {
        if (!btnSubmit) return;
        
        btnSubmit.disabled = loading;
        
        if (btnText && btnLoading) {
            if (loading) {
                btnText.classList.add("hidden");
                btnLoading.classList.remove("hidden");
            } else {
                btnText.classList.remove("hidden");
                btnLoading.classList.add("hidden");
            }
        }
    }

    function validarEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

});