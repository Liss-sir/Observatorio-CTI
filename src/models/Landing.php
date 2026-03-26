<?php

class LandingModel {

    private $conn;

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    /**
     * Obtener los 4 últimos perfiles ocupacionales creados.
     * Incluye información relacionada: línea tecnológica, programa, nivel, etc.
     */
    public function ultimosPerfiles($limit = 4) {
        try {
            $sql = "SELECT 
                        p.id_perfil,
                        p.nombre,
                        p.descripcion,
                        p.cupos,
                        p.fecha_creacion,
                        p.estado,
                        l.nombre_linea,
                        a.nombre_area,
                        prog.nombre_programa,
                        prog.codigo_programa,
                        n.nombre_nivel
                    FROM perfiles_ocupacionales p
                    LEFT JOIN lineas_tecnologicas l ON p.id_linea = l.id_linea
                    LEFT JOIN areas a ON l.id_area = a.id_area
                    LEFT JOIN programas_formacion prog ON p.id_programa = prog.id_programa
                    LEFT JOIN niveles_formacion n ON p.id_nivel = n.id_nivel
                    WHERE p.estado = 1
                    ORDER BY p.fecha_creacion DESC
                    LIMIT :limit";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Obtener las 4 líneas tecnológicas con más perfiles vinculados.
     * Devuelve el nombre de la línea, el área y el conteo de perfiles.
     */
    public function lineasConMasPerfiles($limit = 4) {
        try {
            $sql = "SELECT 
                        l.id_linea,
                        l.nombre_linea,
                        a.nombre_area,
                        COUNT(p.id_perfil) AS total_perfiles
                    FROM lineas_tecnologicas l
                    INNER JOIN areas a ON l.id_area = a.id_area
                    LEFT JOIN perfiles_ocupacionales p ON l.id_linea = p.id_linea AND p.estado = 1
                    WHERE l.estado = 1
                    GROUP BY l.id_linea, l.nombre_linea, a.nombre_area
                    ORDER BY total_perfiles DESC
                    LIMIT :limit";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Obtener los 5 programas de formación con mayor número de cupos.
     * Incluye detalles del programa, área y nivel.
     */
    public function programasConMasCupos($limit = 5) {
        try {
            $sql = "SELECT 
                        p.id_programa,
                        p.codigo_programa,
                        p.nombre_programa,
                        p.cupos_formacion,
                        p.modalidad,
                        p.fecha_creacion,
                        p.fecha_fin,
                        a.nombre_area,
                        n.nombre_nivel
                    FROM programas_formacion p
                    INNER JOIN areas a ON p.id_area = a.id_area
                    INNER JOIN niveles_formacion n ON p.id_nivel = n.id_nivel
                    WHERE p.estado = 1
                    ORDER BY p.cupos_formacion DESC
                    LIMIT :limit";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
}