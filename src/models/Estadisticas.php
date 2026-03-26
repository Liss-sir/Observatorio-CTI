<?php

class EstadisticasModel {

    private $conn;

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    // Get statistics of profiles vs offers
    public function obtenerComparativaPerfilesVsOfertas() {
        try {
            // Estadísticas de perfiles ocupacionales por área
            $sqlPerfiles = "
                SELECT 
                    a.id_area,
                    a.nombre_area,
                    COUNT(DISTINCT po.id_perfil) as total_perfiles,
                    COUNT(DISTINCT po.id_programa) as programas_demandados,
                    SUM(po.cupos) as total_cupos_demandados
                FROM areas a
                LEFT JOIN lineas_tecnologicas lt ON a.id_area = lt.id_area AND lt.estado = 1
                LEFT JOIN perfiles_ocupacionales po ON lt.id_linea = po.id_linea AND po.estado = 1
                WHERE a.estado = 1
                GROUP BY a.id_area, a.nombre_area
                ORDER BY total_perfiles DESC
            ";
            
            $stmt = $this->conn->prepare($sqlPerfiles);
            $stmt->execute();
            $perfilesPorArea = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get statistics of programs by area
            $sqlProgramas = "
                SELECT 
                    a.id_area,
                    a.nombre_area,
                    COUNT(DISTINCT pf.id_programa) as total_programas,
                    SUM(pf.cupos_formacion) as total_cupos_ofertados,
                    COUNT(DISTINCT pf.id_nivel) as niveles_formacion
                FROM areas a
                LEFT JOIN programas_formacion pf ON a.id_area = pf.id_area AND pf.estado = 1
                WHERE a.estado = 1
                GROUP BY a.id_area, a.nombre_area
                ORDER BY total_programas DESC
            ";
            
            $stmt = $this->conn->prepare($sqlProgramas);
            $stmt->execute();
            $programasPorArea = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Combine results
            $comparativa = [];
            foreach ($perfilesPorArea as $perfil) {
                $comparativa[$perfil['id_area']] = [
                    'id_area' => $perfil['id_area'],
                    'nombre_area' => $perfil['nombre_area'],
                    'necesidades_empresariales' => [
                        'total_perfiles' => (int)$perfil['total_perfiles'],
                        'programas_demandados' => (int)$perfil['programas_demandados'],
                        'cupos_demandados' => (int)$perfil['total_cupos_demandados']
                    ],
                    'ofertas_formacion' => [
                        'total_programas' => 0,
                        'cupos_ofertados' => 0,
                        'niveles_formacion' => 0
                    ]
                ];
            }

            // Add data of programs
            foreach ($programasPorArea as $programa) {
                if (isset($comparativa[$programa['id_area']])) {
                    $comparativa[$programa['id_area']]['ofertas_formacion'] = [
                        'total_programas' => (int)$programa['total_programas'],
                        'cupos_ofertados' => (int)$programa['total_cupos_ofertados'],
                        'niveles_formacion' => (int)$programa['niveles_formacion']
                    ];
                } else {
                    $comparativa[$programa['id_area']] = [
                        'id_area' => $programa['id_area'],
                        'nombre_area' => $programa['nombre_area'],
                        'necesidades_empresariales' => [
                            'total_perfiles' => 0,
                            'programas_demandados' => 0,
                            'cupos_demandados' => 0
                        ],
                        'ofertas_formacion' => [
                            'total_programas' => (int)$programa['total_programas'],
                            'cupos_ofertados' => (int)$programa['total_cupos_ofertados'],
                            'niveles_formacion' => (int)$programa['niveles_formacion']
                        ]
                    ];
                }
            }

            // Calculate additional indicators
            $totales = [
                'total_necesidades' => 0,
                'total_ofertas' => 0,
                'total_perfiles' => 0,
                'total_programas' => 0,
                'cobertura' => 0
            ];

            foreach ($comparativa as &$item) {
                $totales['total_perfiles'] += $item['necesidades_empresariales']['total_perfiles'];
                $totales['total_programas'] += $item['ofertas_formacion']['total_programas'];
                $totales['total_necesidades'] += $item['necesidades_empresariales']['cupos_demandados'];
                $totales['total_ofertas'] += $item['ofertas_formacion']['cupos_ofertados'];
            }

            $totales['cobertura'] = $totales['total_ofertas'] > 0 
                ? round(($totales['total_necesidades'] / $totales['total_ofertas']) * 100, 2)
                : 0;

            return [
                'por_area' => array_values($comparativa),
                'totales' => $totales,
                'fecha_consulta' => date('Y-m-d H:i:s')
            ];

        } catch (Exception $e) {
            error_log("Error en obtenerComparativaPerfilesVsOfertas: " . $e->getMessage());
            return [
                'por_area' => [],
                'totales' => [
                    'total_necesidades' => 0,
                    'total_ofertas' => 0,
                    'total_perfiles' => 0,
                    'total_programas' => 0,
                    'cobertura' => 0
                ],
                'error' => $e->getMessage()
            ];
        }
    }

    // Get distribution of requests by technological line
    public function obtenerDistribucionPorLineaTecnologica($id_area = null) {
        try {
            $sql = "
                SELECT 
                    lt.id_linea,
                    lt.nombre_linea,
                    a.id_area,
                    a.nombre_area,
                    pf.id_programa,
                    pf.nombre_programa,
                    pf.codigo_programa,
                    COUNT(DISTINCT po.id_perfil) as total_perfiles,
                    SUM(po.cupos) as total_cupos_solicitados,
                    COUNT(DISTINCT po.id_usuario) as empresas_que_solicitan,
                    GROUP_CONCAT(DISTINCT nf.nombre_nivel SEPARATOR ', ') as niveles_demandados
                FROM lineas_tecnologicas lt
                INNER JOIN areas a ON lt.id_area = a.id_area AND a.estado = 1
                INNER JOIN programas_formacion pf ON lt.id_programa = pf.id_programa AND pf.estado = 1
                LEFT JOIN perfiles_ocupacionales po ON lt.id_linea = po.id_linea AND po.estado = 1
                LEFT JOIN niveles_formacion nf ON po.id_nivel = nf.id_nivel
                WHERE lt.estado = 1
            ";
            
            $params = [];
            if ($id_area) {
                $sql .= " AND a.id_area = ?";
                $params[] = $id_area;
            }
            
            $sql .= " GROUP BY lt.id_linea, lt.nombre_linea, a.id_area, a.nombre_area, pf.id_programa, pf.nombre_programa, pf.codigo_programa
                      ORDER BY total_perfiles DESC, total_cupos_solicitados DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $lineas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Process results
            $distribucion = [];
            $totales = [
                'total_lineas' => count($lineas),
                'total_perfiles' => 0,
                'total_cupos_solicitados' => 0,
                'total_empresas' => 0
            ];

            foreach ($lineas as $linea) {
                $totales['total_perfiles'] += (int)$linea['total_perfiles'];
                $totales['total_cupos_solicitados'] += (int)$linea['total_cupos_solicitados'];
                $totales['total_empresas'] += (int)$linea['empresas_que_solicitan'];
                
                $distribucion[] = [
                    'id_linea' => (int)$linea['id_linea'],
                    'nombre_linea' => $linea['nombre_linea'],
                    'area' => [
                        'id_area' => (int)$linea['id_area'],
                        'nombre_area' => $linea['nombre_area']
                    ],
                    'programa' => [
                        'id_programa' => (int)$linea['id_programa'],
                        'nombre_programa' => $linea['nombre_programa'],
                        'codigo_programa' => $linea['codigo_programa']
                    ],
                    'estadisticas' => [
                        'total_perfiles' => (int)$linea['total_perfiles'],
                        'total_cupos_solicitados' => (int)$linea['total_cupos_solicitados'],
                        'empresas_que_solicitan' => (int)$linea['empresas_que_solicitan'],
                        'niveles_demandados' => $linea['niveles_demandados'] ?? ''
                    ],
                    'porcentaje_perfiles' => 0,
                    'porcentaje_cupos' => 0
                ];
            }

            // Calculate percentages
            foreach ($distribucion as &$item) {
                if ($totales['total_perfiles'] > 0) {
                    $item['porcentaje_perfiles'] = round(($item['estadisticas']['total_perfiles'] / $totales['total_perfiles']) * 100, 2);
                }
                if ($totales['total_cupos_solicitados'] > 0) {
                    $item['porcentaje_cupos'] = round(($item['estadisticas']['total_cupos_solicitados'] / $totales['total_cupos_solicitados']) * 100, 2);
                }
            }

            return [
                'distribucion' => $distribucion,
                'totales' => $totales,
                'filtro_area' => $id_area,
                'fecha_consulta' => date('Y-m-d H:i:s')
            ];

        } catch (Exception $e) {
            error_log("Error en obtenerDistribucionPorLineaTecnologica: " . $e->getMessage());
            return [
                'distribucion' => [],
                'totales' => [
                    'total_lineas' => 0,
                    'total_perfiles' => 0,
                    'total_cupos_solicitados' => 0,
                    'total_empresas' => 0
                ],
                'error' => $e->getMessage()
            ];
        }
    }

    // Get complete statistics of dashboard
    public function obtenerEstadisticasCompletas() {
        try {
            $comparativa = $this->obtenerComparativaPerfilesVsOfertas();
            $distribucion = $this->obtenerDistribucionPorLineaTecnologica();

            // Get additional statistics of summary
            $sqlResumen = "
                SELECT 
                    (SELECT COUNT(*) FROM perfiles_ocupacionales WHERE estado = 1) as total_perfiles_activos,
                    (SELECT COUNT(*) FROM programas_formacion WHERE estado = 1) as total_programas_activos,
                    (SELECT COUNT(*) FROM lineas_tecnologicas WHERE estado = 1) as total_lineas_activas,
                    (SELECT COUNT(*) FROM areas WHERE estado = 1) as total_areas_activas,
                    (SELECT SUM(cupos) FROM perfiles_ocupacionales WHERE estado = 1) as total_cupos_demandados,
                    (SELECT SUM(cupos_formacion) FROM programas_formacion WHERE estado = 1) as total_cupos_ofertados,
                    (SELECT COUNT(DISTINCT id_usuario) FROM perfiles_ocupacionales WHERE estado = 1) as empresas_participantes
            ";
            
            $stmt = $this->conn->prepare($sqlResumen);
            $stmt->execute();
            $resumen = $stmt->fetch(PDO::FETCH_ASSOC);

            // Calculate coverage rate
            $tasa_cobertura = 0;
            if ($resumen['total_cupos_ofertados'] > 0) {
                $tasa_cobertura = round(($resumen['total_cupos_demandados'] / $resumen['total_cupos_ofertados']) * 100, 2);
            }

            return [
                'resumen' => [
                    'total_perfiles_activos' => (int)$resumen['total_perfiles_activos'],
                    'total_programas_activos' => (int)$resumen['total_programas_activos'],
                    'total_lineas_activas' => (int)$resumen['total_lineas_activas'],
                    'total_areas_activas' => (int)$resumen['total_areas_activas'],
                    'total_cupos_demandados' => (int)$resumen['total_cupos_demandados'],
                    'total_cupos_ofertados' => (int)$resumen['total_cupos_ofertados'],
                    'empresas_participantes' => (int)$resumen['empresas_participantes'],
                    'tasa_cobertura' => $tasa_cobertura
                ],
                'comparativa_perfiles_vs_ofertas' => $comparativa,
                'distribucion_lineas_tecnologicas' => $distribucion,
                'fecha_consulta' => date('Y-m-d H:i:s')
            ];

        } catch (Exception $e) {
            error_log("Error en obtenerEstadisticasCompletas: " . $e->getMessage());
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    // Get temporal trends of requests
    public function obtenerTendenciasTemporales($periodo = 'mes') {
        try {
            $groupBy = "";
            switch ($periodo) {
                case 'trimestre':
                    $groupBy = "CONCAT(YEAR(po.fecha_creacion), '-T', QUARTER(po.fecha_creacion))";
                    break;
                case 'año':
                    $groupBy = "YEAR(po.fecha_creacion)";
                    break;
                default:
                    $groupBy = "DATE_FORMAT(po.fecha_creacion, '%Y-%m')";
            }

            $sql = "
                SELECT 
                    {$groupBy} as periodo,
                    COUNT(DISTINCT po.id_perfil) as total_perfiles,
                    SUM(po.cupos) as total_cupos,
                    COUNT(DISTINCT po.id_usuario) as empresas_nuevas,
                    COUNT(DISTINCT po.id_linea) as lineas_demandadas
                FROM perfiles_ocupacionales po
                WHERE po.estado = 1
                GROUP BY periodo
                ORDER BY periodo DESC
                LIMIT 12
            ";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $tendencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'periodo' => $periodo,
                'datos' => $tendencias,
                'fecha_consulta' => date('Y-m-d H:i:s')
            ];

        } catch (Exception $e) {
            error_log("Error en obtenerTendenciasTemporales: " . $e->getMessage());
            return [
                'periodo' => $periodo,
                'datos' => [],
                'error' => $e->getMessage()
            ];
        }
    }

    // Get statistics by level of training
    public function obtenerEstadisticasPorNivel() {
        try {
            $sql = "
                SELECT 
                    nf.id_nivel,
                    nf.nombre_nivel,
                    COUNT(DISTINCT po.id_perfil) as total_perfiles,
                    SUM(po.cupos) as total_cupos_demandados,
                    COUNT(DISTINCT pf.id_programa) as total_programas_ofertados,
                    SUM(pf.cupos_formacion) as total_cupos_ofertados
                FROM niveles_formacion nf
                LEFT JOIN perfiles_ocupacionales po ON nf.id_nivel = po.id_nivel AND po.estado = 1
                LEFT JOIN programas_formacion pf ON nf.id_nivel = pf.id_nivel AND pf.estado = 1
                WHERE nf.estado = 1
                GROUP BY nf.id_nivel, nf.nombre_nivel
                ORDER BY total_perfiles DESC
            ";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $niveles = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'niveles' => $niveles,
                'fecha_consulta' => date('Y-m-d H:i:s')
            ];

        } catch (Exception $e) {
            error_log("Error en obtenerEstadisticasPorNivel: " . $e->getMessage());
            return [
                'niveles' => [],
                'error' => $e->getMessage()
            ];
        }
    }
}