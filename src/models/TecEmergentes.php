<?php

class TecnoEmergentes {

    private $conn;

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    /**
     * Crear una nueva tendencia emergente
     * @param string $nombre
     * @param string $descripcion
     * @return int|false ID insertado o false si falla
     */
    public function crear($nombre, $descripcion) {
        try {
            $sql = "INSERT INTO tendencias_emergentes (nombre, descripcion, estado) VALUES (?, ?, 1)";
            $stmt = $this->conn->prepare($sql);
            $ok = $stmt->execute([$nombre, $descripcion]);
            return $ok ? $this->conn->lastInsertId() : false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Listar todas las tendencias activas (estado = 1)
     * @return array
     */
    public function listar() {
        try {
            $sql = "SELECT id_tendencia, nombre, descripcion, estado 
                    FROM tendencias_emergentes 
                    WHERE estado = 1 
                    ORDER BY id_tendencia DESC";
            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    public function listarInactivas() {
        try {
            $sql = "SELECT id_tendencia, nombre, descripcion, estado 
                    FROM tendencias_emergentes 
                    WHERE estado = 0 
                    ORDER BY id_tendencia DESC";
            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Obtener una tendencia por su ID (incluye cualquier estado)
     * @param int $id
     * @return array|null
     */
    public function obtenerPorId($id) {
        try {
            $sql = "SELECT id_tendencia, nombre, descripcion, estado
                    FROM tendencias_emergentes 
                    WHERE id_tendencia = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Actualizar nombre y descripción de una tendencia
     * @param int $id
     * @param string $nombre
     * @param string $descripcion
     * @return bool
     */
    public function actualizar($id, $nombre, $descripcion) {
        try {
            $sql = "UPDATE tendencias_emergentes SET nombre = ?, descripcion = ? WHERE id_tendencia = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$nombre, $descripcion, $id]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Inactivar una tendencia (estado = 0)
     * @param int $id
     * @return bool
     */
    public function inactivar($id) {
        try {
            $sql = "UPDATE tendencias_emergentes SET estado = 0 WHERE id_tendencia = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Activar una tendencia (estado = 1) – método opcional
     * @param int $id
     * @return bool
     */
    public function activar($id) {
        try {
            $sql = "UPDATE tendencias_emergentes SET estado = 1 WHERE id_tendencia = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            return true;
        }
    }
}