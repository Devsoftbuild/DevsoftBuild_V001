<?php
/**
 * Modelo de Tecnologías
 * DevSoft Build SAC
 */

require_once __DIR__ . '/Database.php';

class TecnologiaModel {
    public static function getAll($filters = []) {
        try {
            $pdo = getDB();
            $sql = "SELECT * FROM tecnologias WHERE 1=1";
            $params = [];
            
            if (isset($filters['estado_tecnologia'])) {
                $sql .= " AND estado_tecnologia = ?";
                $params[] = $filters['estado_tecnologia'];
            }
            
            if (!empty($filters['categoria_tecnologia'])) {
                $sql .= " AND categoria_tecnologia = ?";
                $params[] = $filters['categoria_tecnologia'];
            }
            
            $sql .= " ORDER BY categoria_tecnologia ASC, orden_tecnologia ASC, nombre_tecnologia ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error en TecnologiaModel::getAll: " . $e->getMessage());
            return [];
        }
    }
    
    public static function getById($id) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT * FROM tecnologias WHERE id_tecnologia = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public static function getByCategoria($categoria) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT * FROM tecnologias WHERE categoria_tecnologia = ? AND estado_tecnologia = 1 ORDER BY orden_tecnologia ASC");
            $stmt->execute([$categoria]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
}

