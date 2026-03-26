const Perfil = {
    datos: null,
    
    async cargarDatos() {
        try {
            const res = await fetch('../../controllers/MiPerfilController.php?action=index', {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' }
            });
            
            const text = await res.text();
            console.log('Respuesta:', text); // 🔍 Para depurar
            
            const data = JSON.parse(text);
            
            if (res.ok && data.usuario) {
                this.datos = data;
                this.actualizarUI();
            } else {
                console.error('Error:', data);
                alert('Error al cargar perfil');
            }
        } catch (error) {
            console.error('Error de conexión:', error);
        }
    },
    
    actualizarUI() {
        if (!this.datos) return;
        
        // Header
        const nombre = this.datos.usuario.nombre_completo;
        document.getElementById('headerInicial').textContent = nombre.charAt(0).toUpperCase();
        document.getElementById('headerNombre').textContent = nombre;
        document.getElementById('headerRol').textContent = this.datos.usuario.tipo_cuenta;
        
        // Info
        document.getElementById('infoNombre').textContent = nombre;
        document.getElementById('infoCorreo').textContent = this.datos.usuario.correo;
        document.getElementById('fechaRegistro').textContent = `Miembro desde ${this.datos.usuario.miembro_desde}`;
        
        // Cuenta
        document.getElementById('tipoCuenta').textContent = `Cuenta ${this.datos.usuario.tipo_cuenta}`;
        document.getElementById('descripcionCuenta').textContent = this.datos.usuario.descripcion;
        
        // Estadísticas
        if (this.datos.estadisticas) {
            const s = this.datos.estadisticas;
            document.getElementById('statPerfiles').textContent = s.programas ?? 0;
            document.getElementById('statEmpresas').textContent = s.areas ?? 0;
            
            document.getElementById('listaEstadisticas').innerHTML = `
                <div class="flex justify-between"><span>Programas</span><span>${s.programas ?? 0}</span></div>
                <div class="flex justify-between"><span>Áreas</span><span>${s.areas ?? 0}</span></div>
                <div class="flex justify-between"><span>Tecnologías</span><span>${s.tecnologias ?? 0}</span></div>
            `;
        }
        
        // Perfiles
        if (this.datos.ultimos_perfiles?.length > 0) {
            document.getElementById('listaPerfiles').innerHTML = this.datos.ultimos_perfiles.map(p => `
                <div class="flex justify-between items-center border-b py-3 last:border-none">
                    <div class="flex items-center gap-3">
                        <i data-lucide="briefcase" class="w-4 h-4 text-gray-400"></i>
                        <span>${p.nombre_perfil}</span>
                    </div>
                    <span class="px-3 py-1 text-xs rounded-full bg-sena-soft text-green-700">${p.estado}</span>
                </div>
            `).join('');
            lucide.createIcons();
        }
    }
};

document.addEventListener("DOMContentLoaded", () => {
    Perfil.cargarDatos(); // ✅ Cargar todo al iniciar
    
    const modal = document.getElementById('modalEditar');
    const btnGuardar = document.getElementById('btnGuardar');
    const btnCancelar = document.getElementById('btnCancelar');
    const inputNombre = document.getElementById('inputNombre');
    const inputCorreo = document.getElementById('inputCorreo');

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.btnAbrirPerfil');
        if (btn && modal) {
            inputNombre.value = Perfil.datos?.usuario?.nombre_completo || '';
            inputCorreo.value = Perfil.datos?.usuario?.correo || '';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    });

    if (btnCancelar && modal) {
        btnCancelar.addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
    }

    if (btnGuardar) {
        btnGuardar.addEventListener('click', async () => {
            const nombre_completo = inputNombre.value.trim();
            const correo = inputCorreo.value.trim();

            if (!nombre_completo || !correo) {
                alert('Todos los campos son requeridos');
                return;
            }

            btnGuardar.disabled = true;
            btnGuardar.textContent = 'Guardando...';

            try {
                const res = await fetch('../../../controllers/MiPerfilController.php?action=update', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ nombre_completo, correo })
                });

                const data = await res.json();

                if (data.success) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    await Perfil.cargarDatos(); // ✅ Recargar datos actualizados
                    alert('Perfil actualizado correctamente');
                } else {
                    alert(data.error || 'Error al guardar');
                }
            } catch (error) {
                console.error(error);
                alert('Error de conexión');
            } finally {
                btnGuardar.disabled = false;
                btnGuardar.textContent = 'Guardar cambios';
            }
        });
    }
});