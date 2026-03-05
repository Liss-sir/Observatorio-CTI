document.addEventListener("DOMContentLoaded", function () {
    const btnCrearArea = document.getElementById("btn-crear-area");
    const modalCrearArea = document.getElementById("modal-crear-area");

    btnCrearArea.addEventListener("click", () => {
        modalCrearArea.classList.remove("hidden");
    });

        document.querySelectorAll(".cerrar-modal-area").forEach(btn => {
        btn.addEventListener("click", () => {
        modalCrearArea.classList.add("hidden");

    });
    });
});