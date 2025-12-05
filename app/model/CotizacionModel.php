<?php
/**
 * Modelo de Cotizaciones
 * DevSoft Build SAC
 */

require_once __DIR__ . '/Database.php';

class CotizacionModel {
    public static function create($data) {
        try {
            $pdo = getDB();
            $sql = "INSERT INTO cotizaciones (
                nombre_cotizacion, email_cotizacion, telefono_cotizacion, empresa_cotizacion,
                tipo_proyecto_cotizacion, descripcion_proyecto_cotizacion,
                presupuesto_estimado_cotizacion, plazo_estimado_cotizacion, servicio_id_cotizacion
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $data['nombre_cotizacion'] ?? '',
                $data['email_cotizacion'] ?? '',
                $data['telefono_cotizacion'] ?? null,
                $data['empresa_cotizacion'] ?? null,
                $data['tipo_proyecto_cotizacion'] ?? '',
                $data['descripcion_proyecto_cotizacion'] ?? '',
                $data['presupuesto_estimado_cotizacion'] ?? null,
                $data['plazo_estimado_cotizacion'] ?? null,
                $data['servicio_id_cotizacion'] ?? null
            ]);
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error en CotizacionModel::create: " . $e->getMessage());
            return false;
        }
    }
    
    public static function getAll($filters = []) {
        try {
            $pdo = getDB();
            $sql = "SELECT c.*, s.nombre_servicio 
                    FROM cotizaciones c 
                    LEFT JOIN servicios s ON c.servicio_id_cotizacion = s.id_servicio 
                    WHERE 1=1";
            $params = [];
            
            if (!empty($filters['estado_cotizacion'])) {
                $sql .= " AND estado_cotizacion = ?";
                $params[] = $filters['estado_cotizacion'];
            }
            
            $sql .= " ORDER BY created_at DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    
    public static function getById($id) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT c.*, s.nombre_servicio 
                                   FROM cotizaciones c 
                                   LEFT JOIN servicios s ON c.servicio_id_cotizacion = s.id_servicio 
                                   WHERE c.id_cotizacion = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public static function updateEstado($id, $estado) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("UPDATE cotizaciones SET estado_cotizacion = ? WHERE id_cotizacion = ?");
            return $stmt->execute([$estado, $id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}

