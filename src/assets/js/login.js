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

    function mostrarToastValidacion(mensaje, tipo = 'warning') {
        const toastContainer = document.getElementById('toast-container');
        
        if (!toastContainer) {
            const container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'fixed top-4 right-4 z-[99999] flex flex-col gap-3 pointer-events-none';
            document.body.appendChild(container);
        }
        
        const container = document.getElementById('toast-container');
        
        const titulo = tipo === 'warning' ? 'Campo requerido' : 
                       tipo === 'error' ? 'Error' : 
                       tipo === 'info' ? 'Información' : 'Éxito';
        
        const toastId = 'toast-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = `toast-validation ${tipo}`;
        
        const iconos = {
            info: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>`,
            warning: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4"/><path d="M12 17h.01"/><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z"/></svg>`,
            error: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>`,
            success: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>`
        };
        
        toast.innerHTML = `
            <div class="toast-contenido">
                <div class="toast-icono-wrapper">
                    <div class="toast-icono">${iconos[tipo] || iconos.warning}</div>
                </div>
                <div class="toast-mensaje-wrapper">
                    <div class="toast-titulo">${titulo}</div>
                    <div class="toast-mensaje">${mensaje}</div>
                </div>
            </div>
        `;
        
        container.appendChild(toast);
        
        setTimeout(() => {
            const toastElement = document.getElementById(toastId);
            if (toastElement) {
                toastElement.classList.add('exit');
                setTimeout(() => {
                    if (toastElement.parentNode) toastElement.remove();
                }, 200);
            }
        }, 3000);
    }

    // ========== LOGIN FORM ==========
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

            const correo = document.getElementById("correo").value.trim();

            if (!correo) {
                mostrarToastValidacion("El correo es obligatorio", "warning");
                return;
            }

            fetch("../../controllers/LogController.php?accion=recuperar", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ correo })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    mostrarToastValidacion(
                        "Si el correo está registrado, recibirás un enlace ",
                        "success"
                    );
                } else {
                    mostrarToastValidacion(
                        data.error || "Error al enviar el correo",
                        "error"
                    );
                }
            })
            .catch(() => {
                mostrarToastValidacion(
                    "Error de conexión con el servidor",
                    "error"
                );
            });
        });
    }

    //ESTABLECER NUEVA CONTRASEÑA
    const btnCambiar = document.getElementById("btnCambiarPassword");

    if (btnCambiar) {
        btnCambiar.addEventListener("click", cambiarPassword);
    }

    function cambiarPassword() {
        const password = document.getElementById("password").value;
        const confirmar = document.getElementById("confirmar_password").value;
        const token = new URLSearchParams(window.location.search).get("token");

        if (!password || !confirmar) {
            mostrarToastValidacion("Todos los campos son obligatorios", "warning");
            return;
        }

        if (password.length < 6) {
            mostrarToastValidacion("Mínimo 6 caracteres", "warning");
            return;
        }

        if (password !== confirmar) {
            mostrarToastValidacion("Las contraseñas no coinciden", "error");
            return;
        }

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
            if (data.success) {
                mostrarToastValidacion(
                    "Contraseña actualizada correctamente ",
                    "success"
                );

                // 🔥 Redirección después de 2 segundos
                setTimeout(() => {
                    window.location.href = "../login/login.php";
                }, 2000);

            } else {
                mostrarToastValidacion(
                    data.message || "Error al actualizar la contraseña",
                    "error"
                );
            }
        })
        .catch(() => {
            mostrarToastValidacion(
                "Error de conexión con el servidor",
                "error"
            );
        });
    }

});