<?php
/**
 * Modelo de Estadísticas
 * DevSoft Build SAC
 */

require_once __DIR__ . '/Database.php';

class EstadisticaModel {
    public static function getAll($filters = []) {
        try {
            $pdo = getDB();
            $sql = "SELECT * FROM estadisticas WHERE 1=1";
            $params = [];
            
            if (isset($filters['estado_estadistica'])) {
                $sql .= " AND estado_estadistica = ?";
                $params[] = $filters['estado_estadistica'];
            }
            
            $sql .= " ORDER BY orden_estadistica ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
}


