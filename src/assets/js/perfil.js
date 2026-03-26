document.addEventListener("DOMContentLoaded", () => {

    const modal = document.getElementById('modalEditar');
    const btnCancelar = document.getElementById('btnCancelar');
    const btnGuardar = document.getElementById('btnGuardar');

    const inputNombre = document.getElementById('inputNombre');
    const inputCorreo = document.getElementById('inputCorreo');

    // 🟢 ABRIR MODAL
    document.addEventListener('click', (e) => {

        const btn = e.target.closest('.btnAbrirPerfil');

        if (btn) {
            const modal = document.getElementById('modalEditar');
            const inputNombre = document.getElementById('inputNombre');
            const inputCorreo = document.getElementById('inputCorreo');

            inputNombre.value = Auth.usuario?.nombre || '';
            inputCorreo.value = Auth.usuario?.correo || '';

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

    });

    // 🔴 CERRAR MODAL (BOTÓN CANCELAR)
    if (btnCancelar) {
        btnCancelar.addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
    }

    // 🔴 CERRAR AL HACER CLICK FUERA (PRO)
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        });
    }

    // 💾 GUARDAR CAMBIOS
    if (btnGuardar) {
        btnGuardar.addEventListener('click', async () => {
            const nombre = inputNombre.value;
            const correo = inputCorreo.value;

            try {
                const res = await fetch('PerfilController.php?action=editar', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ nombre, correo })
                });

                const data = await res.json();

                if (data.success) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');

                    await Auth.verificarSesion(); // 🔥 refresca header
                } else {
                    alert(data.error || 'Error al guardar');
                }

            } catch (error) {
                console.error(error);
            }
        });
    }

});