document.addEventListener('DOMContentLoaded', function() {
// ===== COMPONENTES REUTILIZABLES =====
const Componentes = {

    tarjeta({ icono, titulo, valor, subtitulo }) {
        return `
             <div class="border rounded-xl p-4 flex items-start gap-3">
                 <div class="bg-sena-soft p-2 rounded-lg">
                     <i data-lucide="${icono}" class="w-5 h-5 text-green-600"></i>
                 </div>
                 <div>
                     <p class="text-sm text-gray-500">${titulo}</p>
                     <p class="text-2xl font-semibold">${valor}</p>
                     <p class="text-xs text-gray-400">${subtitulo}</p>
                 </div>
             </div>
        `;
    },

    estadistica(label, valor) {
        return `
             <div class="flex justify-between">
                 <span>${label}</span>
                 <span>${valor}</span>
             </div>
        `;
    },

    perfilItem(p) {
        return `
             <div class="flex justify-between items-center border-b py-3 last:border-none">
                 <div class="flex items-center gap-3">
                     <i data-lucide="briefcase" class="w-4 h-4 text-gray-400"></i>
                     <span>${p.nombre}</span>
                 </div>
                 <span class="px-3 py-1 text-xs rounded-full bg-sena-soft text-green-700">
                    ${p.estado === '1' ? 'Activo' : 'Inactivo'}
                 </span>
             </div>
        `;
    }
};

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

            let data;
            try {
                 data = JSON.parse(text);
            } catch (error) {
                console.error('Respuesta NO es JSON:', text);
                return;
            }
             
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

        const esEmpresa = u.tipo_cuenta === 'Empresa';
         
        // ===== HEADER =====
        const headerInicial = document.getElementById('headerInicial');
        const headerNombre = document.getElementById('headerNombre'); 
        const headerRol = document.getElementById('headerRol');

        if (headerInicial) headerInicial.textContent = u.nombre_completo.charAt(0).toUpperCase();
        if (headerNombre) headerNombre.textContent = u.nombre_completo; 
        if (headerRol) headerRol.textContent = u.tipo_cuenta;
        
        // ===== INFO =====
        const infoNombre = document.getElementById('infoNombre'); 
        const infoCorreo = document.getElementById('infoCorreo');
        const fechaRegistro = document.getElementById('fechaRegistro');
        const tipoCuenta = document.getElementById('tipoCuenta');
        const descripcionCuenta = document.getElementById('descripcionCuenta');

        if (infoNombre) infoNombre.textContent = u.nombre_completo; 
        if (infoCorreo) infoCorreo.textContent = u.correo;
        if (fechaRegistro) fechaRegistro.textContent = `Miembro desde ${u.miembro_desde}`;
        if (tipoCuenta) tipoCuenta.textContent = `Cuenta ${u.tipo_cuenta}`;
        if (descripcionCuenta) descripcionCuenta.textContent = u.descripcion;
        
        // ===== RESUMEN (CONTENEDOR FIJO) =====
        const contenedorResumen = document.getElementById('resumenContainer');

        if (contenedorResumen) {
            if (esEmpresa) {
                 contenedorResumen.innerHTML = `
                    ${Componentes.tarjeta({
                        icono: 'file-text',
                        titulo: 'Perfiles',
                        valor: s.perfiles_registrados ?? 0,
                        subtitulo: 'creados'
                    })}
                    ${Componentes.tarjeta({
                        icono: 'book-open',
                        titulo: 'Programas',
                        valor: s.programas_formacion ?? 0,
                        subtitulo: 'registrados'
                    })}
                    ${Componentes.tarjeta({
                        icono: 'lightbulb',
                        titulo: 'Sugerencias',
                        valor: s.sugerencias ?? 0,
                        subtitulo: 'generadas'
                    })}
                `;
            } else {
                contenedorResumen.innerHTML = ` 
                    ${Componentes.tarjeta({
                        icono: 'file-text',
                        titulo: 'Perfiles',
                        valor: s.perfiles_registrados ?? 0,
                        subtitulo: 'registrados'
                    })}
                    ${Componentes.tarjeta({
                        icono: 'building',
                        titulo: 'Empresas',
                        valor: s.empresas_registradas ?? 0,
                        subtitulo: 'registradas'
                    })} 
                    ${Componentes.tarjeta({
                        icono: 'shield',
                        titulo: 'Administradores',
                        valor: s.administradores_activos ?? 0,
                        subtitulo: 'activos'
                    })}
                    ${Componentes.tarjeta({
                        icono: 'users',
                        titulo: 'Usuarios',
                        valor: s.usuarios_totales ?? 0,
                        subtitulo: 'totales'
                    })}
                `; 
            }

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
        
        // ===== ESTADÍSTICAS =====
        const listaStats = document.getElementById('listaEstadisticas');

        if (listaStats && s) { 

            if (esEmpresa) {
                listaStats.innerHTML = `
                    ${Componentes.estadistica('Perfiles creados', s.perfiles_registrados ?? 0)}
                    ${Componentes.estadistica('Programas de formación', s.programas_formacion ?? 0)}
                    ${Componentes.estadistica('Sugerencias', s.sugerencias ?? 0)}
                 `;
            } else {
                listaStats.innerHTML = `
                    ${Componentes.estadistica('Programas de formación', s.programas_formacion ?? 0)}
                    ${Componentes.estadistica('Áreas', s.areas ?? 0)}
                    ${Componentes.estadistica('Líneas Tec.', s.lineas_tecnologicas ?? 0)}
                    ${Componentes.estadistica('Tec. Emergentes', s.tecnologias_emergentes ?? 0)}
                    ${Componentes.estadistica('Tendencias actuales', s.tendencias_actuales ?? 0)}
                    ${Componentes.estadistica('Proyección a futuro', s.proyecciones_futuro ?? 0)}
                    ${Componentes.estadistica('Sugerencias', s.sugerencias ?? 0)}
                 `;
            }
        }
        
        // ===== PERFILES RECIENTES =====
        const listaPerfiles = document.getElementById('listaPerfiles');

        if (listaPerfiles) {
            if (perfiles && perfiles.length > 0) { 
                listaPerfiles.innerHTML = perfiles.map(p => Componentes.perfilItem(p)).join(''); 
            } else {
                listaPerfiles.innerHTML = `<p class="text-sm text-gray-400">No hay perfiles disponibles</p>`;
            }

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

document.addEventListener('click', (e) => { 
    const btn = e.target.closest('.btnAbrirPerfil');
    if (btn && modal && Perfil.datos) { 
        inputNombre.value = Perfil.datos.usuario?.nombre_completo || '';
        inputCorreo.value = Perfil.datos.usuario?.correo || '';
        
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

if (modal) {
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
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
                await Perfil.cargarDatos();
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

Perfil.cargarDatos();
});