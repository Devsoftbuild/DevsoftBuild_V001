<?php
/**
 * Modelo de Testimonios
 * DevSoft Build SAC
 */

require_once __DIR__ . '/Database.php';

class TestimonioModel {
    public static function getAll($filters = []) {
        try {
            $pdo = getDB();
            $sql = "SELECT * FROM testimonios WHERE 1=1";
            $params = [];
            
            if (isset($filters['estado_testimonio'])) {
                $sql .= " AND estado_testimonio = ?";
                $params[] = $filters['estado_testimonio'];
            }
            
            if (isset($filters['destacado_testimonio'])) {
                $sql .= " AND destacado_testimonio = ?";
                $params[] = $filters['destacado_testimonio'];
            }
            
            $sql .= " ORDER BY orden_testimonio ASC, created_at DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    
    public static function getDestacados($limit = 3) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT * FROM testimonios WHERE destacado_testimonio = 1 AND estado_testimonio = 1 ORDER BY orden_testimonio ASC LIMIT ?");
            $stmt->execute([$limit]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
}

