<?php
/**
 * Modelo de Procesos
 * DevSoft Build SAC
 */

require_once __DIR__ . '/Database.php';

class ProcesoModel {
    public static function getAll($filters = []) {
        try {
            $pdo = getDB();
            $sql = "SELECT * FROM procesos WHERE 1=1";
            $params = [];
            
            if (isset($filters['estado_proceso'])) {
                $sql .= " AND estado_proceso = ?";
                $params[] = $filters['estado_proceso'];
            }
            
            $sql .= " ORDER BY numero_proceso ASC, orden_proceso ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
}


