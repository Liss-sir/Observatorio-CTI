document.addEventListener("DOMContentLoaded", () => {

    const modal = document.getElementById('modalEditar');
    const btnEditar = document.getElementById('btnEditar');
    const btnCancelar = document.getElementById('btnCancelar');
    const btnGuardar = document.getElementById('btnGuardar');

    btnEditar.addEventListener('click', () => {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    });

    btnCancelar.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    btnGuardar.addEventListener('click', () => {
        alert('Guardar cambios (conectar backend)');
        modal.classList.add('hidden');
    });

});