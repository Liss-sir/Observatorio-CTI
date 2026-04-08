document.addEventListener("DOMContentLoaded", () => {
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
    const formRecuperar = document.getElementById("form-recuperar");

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
    }

    if (loginForm) {
        loginForm.addEventListener("submit", async (e) => {
            e.preventDefault();

            ocultarError();

            const correo = correoInput?.value.trim();
            const password = passwordInput?.value;

            if (!correo || !password) {
                mostrarError("Correo y contraseña son requeridos");
                return;
            }

            if (!validarEmail(correo)) {
                mostrarError("Por favor ingresa un correo válido");
                return;
            }

            setLoading(true);

            try {
                const response = await fetch(API_URL, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ correo, password }),
                });

                const data = await response.json();

                if (data.success === true) {
                    manejarLoginExitoso(data);
                } else {
                    mostrarError(data.error || "Error al iniciar sesión");
                    setLoading(false);
                }

            } catch (error) {
                mostrarError("Error de conexión");
                setLoading(false);
            }
        });
    }

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

    //ENVIO CORREO RECUPERAR
    if (formRecuperar) {
        formRecuperar.addEventListener("submit", function(e) {
            e.preventDefault();

            const correo = document.getElementById("correo").value;

            fetch("../../controllers/LogController.php?accion=recuperar", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ correo })
            })
            .then(res => res.json())
            .then(data => console.log(data));
        });
    }

    //ESTABLECER NUEVA CONTRASEÑA
    const btnCambiar = document.getElementById("btnCambiarPassword");

    if (btnCambiar) {
        btnCambiar.addEventListener("click", cambiarPassword);
    }

    function cambiarPassword() {
        const password = document.getElementById("password").value;
        const token = new URLSearchParams(window.location.search).get("token");

        fetch("../../controllers/LogController.php?accion=restablecer", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                token: token,
                nueva_password: password
            })
        })
        .then(res => res.json())
        .then(data => {
            console.log(data);
        });
    }

});