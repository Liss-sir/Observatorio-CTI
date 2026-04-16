<div id="modal-detalle-usuario" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-hidden="true">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-80 transition-opacity"></div>
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-xl transform overflow-hidden rounded-lg border border-sena-border bg-white shadow-xl transition-all animate-modalFadeIn">
                <!-- HEADER -->
                <div class="border-b border-sena-border px-5 py-3 flex items-center justify-between bg-sena-soft">
                    <h3 class="text-base font-bold text-sena-text-main">
                        Detalle del Usuario
                    </h3>
                    <button class="cerrar-modal-detalle-usuario text-gray-500 hover:text-gray-700 transition-colors">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <!-- CONTENT -->
                <div class="px-5 py-4">
                    <div class="bg-gray-50 rounded-lg p-3 flex items-center gap-3 mb-4">
                        <div id="detalle-avatar"
                            class="w-10 h-10 rounded-full bg-sena flex items-center justify-center text-white font-bold text-sm">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p id="detalle-cargo" class="text-xs text-sena truncate">
                            </p>
                            <p id="detalle-nombre" class="text-xs text-sena truncatetext-sm font-bold text-sena-text-main truncate">
                            </p>
                        </div>
                    </div>
                    
                    <!-- SECTION: PERSONAL INFORMATION -->
                    <div class="mb-4">
                        <h4 class="text-sm font-bold text-sena-text-main mb-2">
                            Información Personal
                        </h4>
                        
                        <div class="space-y-1.5">
                            <div class="flex items-start gap-2">
                                <span class="text-xs font-bold text-sena-text-soft w-32 flex-shrink-0">
                                    REPRESENTANTE
                                </span>
                                <span class="text-xs font-medium text-sena-text-main flex-1" id="detalle-representante">
                                  
                                </span>
                            </div>
                            
                            <div class="flex items-start gap-2">
                                <span class="text-xs font-bold text-sena-text-soft w-32 flex-shrink-0">
                                    CORREO
                                </span>
                                <span class="text-xs font-medium text-sena-text-main flex-1 truncate" id="detalle-correo">
                                    
                                </span>
                            </div>
                            
                            <div class="flex items-start gap-2">
                                <span class="text-xs font-bold text-sena-text-soft w-32 flex-shrink-0">
                                    RAZÓN SOCIAL
                                </span>
                                <span class="text-xs font-medium text-sena-text-main flex-1 truncate" id="detalle-razon-social">
                                    
                                </span>
                            </div>
                            
                            <div class="flex items-start gap-2">
                                <span class="text-xs font-bold text-sena-text-soft w-32 flex-shrink-0">
                                    TIPO DOCUMENTO
                                </span>
                                <span class="text-xs font-medium text-sena-text-main flex-1" id="detalle-documento">
                                    
                                </span>
                            </div>
                            
                            <div class="flex items-start gap-2">
                                <span class="text-xs font-bold text-sena-text-soft w-32 flex-shrink-0">
                                    NÚMERO
                                </span>
                                <span class="text-xs font-medium text-sena-text-main flex-1" id="detalle-numero-documento">
                                    
                                </span>
                            </div>
                            
                            <div class="flex items-start gap-2">
                                <span class="text-xs font-bold text-sena-text-soft w-32 flex-shrink-0">
                                    FECHA REGISTRO
                                </span>
                                <span class="text-xs font-medium text-sena-text-main flex-1" id="detalle-fecha-registro">
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- TECHNOLOGIES -->
                    <div class="mb-4">
                        <h4 class="text-sm font-bold text-sena-text-main mb-2">
                            Líneas Tecnológicas
                        </h4>
                        <div id="detalle-tecnologias"
                            class="flex flex-wrap gap-1.5">
                        </div>
                    </div>
                    
                    <!-- PROFILES -->
                    <div class="mb-4">
                        <h4 class="text-sm font-bold text-sena-text-main mb-2">
                            Perfiles (<span id="detalle-total-perfiles" class="text-sena">0</span>)
                        </h4>
                        <div id="detalle-perfiles" class="space-y-1.5">
                        </div>
                        <button id="btn-ver-mas-perfiles"
                            class="text-xs text-sena font-semibold mt-2 hover:underline hidden">
                            Ver más
                        </button>
                    </div>
                    
                    <!-- CLOSE BUTTON -->
                    <button
                        class="cerrar-modal-detalle-usuario w-full bg-sena hover:opacity-90 text-white font-semibold rounded py-2 text-xs transition-colors">
                        Cerrar
                    </button>
                </div>                 
    </div>
</div>