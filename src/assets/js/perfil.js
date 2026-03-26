document.addEventListener('DOMContentLoaded', function() {
    
    // ===== OBJETO PERFIL =====
    const Perfil = {
        datos: null,
        
        async cargarDatos() {
            try {
                const res = await fetch('../../controllers/MiPerfilController.php?action=index', {
                    method: 'GET',
                    headers: { 'Content-Type': 'application/json' }
                });
                
                const text = await res.text();
                
                if (!text.trim()) {
                    console.error('Respuesta vacía del servidor');
                    return;
                }
                
                const data = JSON.parse(text);
                
                if (res.ok && data.usuario) {
                    this.datos = data;
                    this.actualizarUI();
                } else {
                    console.error('Error API:', data);
                }
            } catch (error) {
                console.error('Error de conexión:', error);
            }
        },
        
        actualizarUI() {
            if (!this.datos) return;
            
            const u = this.datos.usuario;
            const s = this.datos.estadisticas;
            const perfiles = this.datos.ultimos_perfiles;
            
            // Header
            document.getElementById('headerInicial').textContent = u.nombre_completo.charAt(0).toUpperCase();
            document.getElementById('headerNombre').textContent = u.nombre_completo;
            document.getElementById('headerRol').textContent = u.tipo_cuenta;
            
            // Info
            document.getElementById('infoNombre').textContent = u.nombre_completo;
            document.getElementById('infoCorreo').textContent = u.correo;
            document.getElementById('fechaRegistro').textContent = `Miembro desde ${u.miembro_desde}`;
            document.getElementById('tipoCuenta').textContent = `Cuenta ${u.tipo_cuenta}`;
            document.getElementById('descripcionCuenta').textContent = u.descripcion;
            
            // Resumen Plataforma (4 tarjetas)
            document.getElementById('statPerfiles').textContent = s.perfiles_registrados ?? 0;
            document.getElementById('statEmpresas').textContent = s.empresas_registradas ?? 0;
            document.getElementById('statAdministradores').textContent = s.administradores_activos ?? 0;
            document.getElementById('statUsuarios').textContent = s.usuarios_totales ?? 0;
            
            // Estadísticas Detalladas
            const listaStats = document.getElementById('listaEstadisticas');
            if (listaStats && s) {
                listaStats.innerHTML = `
                    <div class="flex justify-between"><span>Programas de formación</span><span>${s.programas_formacion ?? 0}</span></div>
                    <div class="flex justify-between"><span>Áreas</span><span>${s.areas ?? 0}</span></div>
                    <div class="flex justify-between"><span>Líneas Tec.</span><span>${s.lineas_tecnologicas ?? 0}</span></div>
                    <div class="flex justify-between"><span>Tec. Emergentes</span><span>${s.tecnologias_emergentes ?? 0}</span></div>
                    <div class="flex justify-between"><span>Tendencias actuales</span><span>${s.tendencias_actuales ?? 0}</span></div>
                    <div class="flex justify-between"><span>Proyección a futuro</span><span>${s.proyecciones_futuro ?? 0}</span></div>
                    <div class="flex justify-between"><span>Sugerencias</span><span>${s.sugerencias ?? 0}</span></div>
                `;
            }
            
            // Perfiles Recientes
            const listaPerfiles = document.getElementById('listaPerfiles');
            if (listaPerfiles && perfiles && perfiles.length > 0) {
                listaPerfiles.innerHTML = perfiles.map(p => `
                    <div class="flex justify-between items-center border-b py-3 last:border-none">
                        <div class="flex items-center gap-3">
                            <i data-lucide="briefcase" class="w-4 h-4 text-gray-400"></i>
                            <span>${p.nombre}</span>
                        </div>
                        <span class="px-3 py-1 text-xs rounded-full bg-sena-soft text-green-700">
                            ${p.estado === '1' ? 'Activo' : 'Inactivo'}
                        </span>
                    </div>
                `).join('');
                
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }
        }
    };
    
    // ===== MODAL =====
    const modal = document.getElementById('modalEditar');
    const btnCancelar = document.getElementById('btnCancelar');
    const btnGuardar = document.getElementById('btnGuardar');
    const inputNombre = document.getElementById('inputNombre');
    const inputCorreo = document.getElementById('inputCorreo');
    
    // 🟢 ABRIR MODAL - PRECARGAR DATOS
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.btnAbrirPerfil');
        if (btn && modal && Perfil.datos) {
            // 🔥 PRECARGAR datos actuales del usuario
            inputNombre.value = Perfil.datos.usuario?.nombre_completo || '';
            inputCorreo.value = Perfil.datos.usuario?.correo || '';
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    });
    
    // 🔴 CERRAR MODAL (Cancelar)
    if (btnCancelar && modal) {
        btnCancelar.addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
    }
    
    // 🔴 CERRAR MODAL (Click fuera)
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
            const nombre_completo = inputNombre.value.trim();
            const correo = inputCorreo.value.trim();
            
            if (!nombre_completo || !correo) {
                alert('Todos los campos son requeridos');
                return;
            }
            
            btnGuardar.disabled = true;
            btnGuardar.textContent = 'Guardando...';
            
            try {
                const res = await fetch('../../controllers/MiPerfilController.php?action=update', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ nombre_completo, correo })
                });
                
                const text = await res.text();
                const data = text ? JSON.parse(text) : {};
                
                if (data.success) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    await Perfil.cargarDatos(); // Recargar datos actualizados
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
    
    // ===== INICIALIZAR =====
    Perfil.cargarDatos();
});