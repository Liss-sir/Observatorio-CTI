document.addEventListener("DOMContentLoaded", () => {

  lucide.createIcons();

  function togglePassword(inputId, buttonId) {
    const input = document.getElementById(inputId);
    const button = document.getElementById(buttonId);

    button.addEventListener("click", () => {
      const isPassword = input.type === "password";
      input.type = isPassword ? "text" : "password";

      button.innerHTML = isPassword
        ? '<i data-lucide="eye-off" class="w-5 h-5"></i>'
        : '<i data-lucide="eye" class="w-5 h-5"></i>';

      lucide.createIcons();
    });
  }

  togglePassword("password", "togglePassword");
  togglePassword("confirmPassword", "toggleConfirmPassword");

});