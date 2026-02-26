document.addEventListener("DOMContentLoaded", () => {

    lucide.createIcons(); // activa lucide

    const toggleBtn = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("password");

    toggleBtn.addEventListener("click", () => {

        const isPassword = passwordInput.type === "password";

        // Cambiar tipo
        passwordInput.type = isPassword ? "text" : "password";

        // Cambiar icono
        toggleBtn.innerHTML = isPassword
            ? '<i data-lucide="eye-off" class="w-5 h-5"></i>'
            : '<i data-lucide="eye" class="w-5 h-5"></i>';

        lucide.createIcons(); // volver a renderizar icono
    });

});