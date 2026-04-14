document.addEventListener('DOMContentLoaded', function() {

    // ===== 5. TOGGLE DE CAMPOS SEGÚN ROL =====
    const selectRol = document.querySelector('select[name="rol"]');

    function toggleCamposPorRol(rol) {
        const esAdmin = rol === 'admin';
        
        // Mostrar/ocultar campos con data-role="empresa"
        document.querySelectorAll('[data-role="empresa"]').forEach(el => {
            el.style.display = esAdmin ? 'none' : '';
            // Deshabilitar inputs ocultos para que no se envíen
            const inputs = el.querySelectorAll('input, select, textarea');
            inputs.forEach(input => input.disabled = esAdmin);
        });
        
        // Actualizar hidden inputs según rol
        const representanteHidden = document.querySelector('input[name="representante"][type="hidden"]');
        const razonHidden = document.querySelector('input[name="razon_social"][type="hidden"]');
        
        if (representanteHidden) {
            representanteHidden.value = esAdmin ? 'Administrador' : '';
        }
        if (razonHidden) {
            razonHidden.value = esAdmin ? '' : '';
        }
    }

    // Event listener para cambios en el select
    if (selectRol) {
        selectRol.addEventListener('change', (e) => {
            toggleCamposPorRol(e.target.value);
        });
        
        // Ejecutar al cargar por si ya hay un valor seleccionado
        toggleCamposPorRol(selectRol.value);
    }
    
    // ===== 1. INICIALIZAR LUCIDE ICONS =====
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // ===== 2. TOGGLE PASSWORD VISIBILITY =====
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            const icon = this.querySelector('i');
            if (icon) {
                icon.setAttribute('data-lucide', type === 'password' ? 'eye' : 'eye-off');
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }
        });
    }

    // ===== 3. TOGGLE CONFIRM PASSWORD VISIBILITY =====
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    
    if (toggleConfirmPassword && confirmPasswordInput) {
        toggleConfirmPassword.addEventListener('click', function() {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            
            const icon = this.querySelector('i');
            if (icon) {
                icon.setAttribute('data-lucide', type === 'password' ? 'eye' : 'eye-off');
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }
        });
    }

    // ===== 4. MANEJO DEL FORMULARIO DE REGISTRO =====
    const registerForm = document.getElementById("registerForm");
    
    if (registerForm) {
        registerForm.addEventListener("submit", async (e) => {
            e.preventDefault(); // Prevenir envío tradicional del formulario

            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            
            // Validar que existan todos los campos necesarios
            if (!form.representante || !form.empresa || !form.email || 
                !form.tipo_documento || !form.documento || !form.password) {
                console.error("No se encontraron todos los campos del formulario");
                alert("Error: No se pudieron cargar los campos del formulario");
                return;
            }

            // Guardar texto original del botón y mostrar spinner
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span class="flex items-center justify-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Creando cuenta...
                </span>
            `;

            // Preparar datos para enviar
            const data = {
                representante_legal: form.representante.value.trim(),
                nombre_empresa: form.empresa.value.trim(),
                tipo_documento: form.tipo_documento.value,
                numero_documento: form.documento.value.trim(),
                correo: form.email.value.trim(),
                password: form.password.value,
                razon_social: form.razon_social.value.trim() || null
            };
            if (form.rol) {
                data.rol = form.rol.value;
            }

            // ===== VALIDACIONES =====
            const esAdmin = form.rol?.value === 'admin';

            // Campos requeridos dinámicos
            const camposRequeridos = {
                representante_legal: !esAdmin,      // Solo requerido para empresa
                nombre_empresa: true,               // Siempre requerido
                correo: true,                       // Siempre requerido
                tipo_documento: !esAdmin,           // Solo requerido para empresa
                numero_documento: !esAdmin,         // Solo requerido para empresa
                password: true,                     // Siempre requerido
                razon_social: false                 // Siempre opcional
            };

            // Validar solo los campos requeridos según el rol
            for (const [campo, requerido] of Object.entries(camposRequeridos)) {
                if (requerido && (!data[campo] || data[campo]?.trim() === '')) {
                    alert(`El campo ${campo.replace('_', ' ')} es requerido`);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    return;
                }
            }

            // Validar formato de correo (siempre)
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(data.correo)) {
                alert("Por favor ingresa un correo electrónico válido");
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                return;
            }

            // Validar contraseña mínima (siempre)
            if (data.password.length < 6) {
                alert("La contraseña debe tener al menos 6 caracteres");
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                return;
            }

            // Validar que las contraseñas coincidan (siempre)
            if (data.password !== form.confirm_password?.value) {
                alert("Las contraseñas no coinciden");
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                return;
            }
       
            // Validar contraseña mínima
            if (data.password.length < 6) {
                alert("La contraseña debe tener al menos 6 caracteres");
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                return;
            }

            // Validar que las contraseñas coincidan
            if (data.password !== form.confirm_password.value) {
                alert("Las contraseñas no coinciden");
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                return;
            }

            // ===== ENVÍO AJAX =====
            try {
                const response = await fetch("../../controllers/LogController.php?accion=register", {
                    method: "POST",
                    headers: { 
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify(data)
                });

                // Verificar que la respuesta sea JSON
                const contentType = response.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                    throw new Error("La respuesta del servidor no es JSON válido");
                }

                const result = await response.json();
                console.log("Respuesta del servidor:", result);

                if (result.success) {
                    // ===== ÉXITO: Mostrar modal =====
                    
                    // Animación de salida del formulario
                    const formContainer = document.querySelector('.animate-form-in');
                    if (formContainer) {
                        formContainer.classList.remove('animate-form-in');
                        formContainer.classList.add('animate-form-out');
                    }
                    
                    // Mostrar modal de éxito después de la animación
                    setTimeout(() => {
                        if (typeof window.mostrarModalRegistroExitoso === 'function') {
                            window.mostrarModalRegistroExitoso();
                        } else {
                            // Fallback si el modal no está disponible
                            alert("¡Registro exitoso! Por favor revisa tu correo para verificar tu cuenta.");
                            window.location.href = "../../auth/login/login.php";
                        }
                    }, 500);
                    
                } else {
                    // Error del servidor
                    alert("Error: " + (result.error || "Error desconocido"));
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }

            } catch (error) {
                console.error("Error en la petición:", error);
                alert("Error en la conexión con el servidor. Por favor intenta de nuevo.");
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    } else {
        console.error("No se encontró el formulario de registro");
    }
});