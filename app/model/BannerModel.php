<?php
/**
 * Modelo de Banners
 * DevSoft Build SAC
 */

require_once __DIR__ . '/Database.php';

class BannerModel {
    public static function getAll($filters = []) {
        try {
            $pdo = getDB();
            $sql = "SELECT * FROM banners WHERE 1=1";
            $params = [];
            
            if (isset($filters['estado_banner'])) {
                $sql .= " AND estado_banner = ?";
                $params[] = $filters['estado_banner'];
            }
            
            if (!empty($filters['posicion_banner'])) {
                $sql .= " AND posicion_banner = ?";
                $params[] = $filters['posicion_banner'];
            }
            
            // Filtrar por fechas activas
            if (isset($filters['fecha_actual'])) {
                $sql .= " AND (fecha_inicio_banner IS NULL OR fecha_inicio_banner <= ?) 
                         AND (fecha_fin_banner IS NULL OR fecha_fin_banner >= ?)";
                $params[] = $filters['fecha_actual'];
                $params[] = $filters['fecha_actual'];
            }
            
            $sql .= " ORDER BY orden_banner ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error en BannerModel::getAll: " . $e->getMessage());
            return [];
        }
    }
    
    public static function getByPosicion($posicion) {
        try {
            $pdo = getDB();
            $fecha_actual = date('Y-m-d');
            $stmt = $pdo->prepare("
                SELECT * FROM banners 
                WHERE posicion_banner = ? 
                AND estado_banner = 1
                AND (fecha_inicio_banner IS NULL OR fecha_inicio_banner <= ?)
                AND (fecha_fin_banner IS NULL OR fecha_fin_banner >= ?)
                ORDER BY orden_banner ASC
            ");
            $stmt->execute([$posicion, $fecha_actual, $fecha_actual]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    
    public static function getById($id) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT * FROM banners WHERE id_banner = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public static function create($data) {
        try {
            $pdo = getDB();
            $sql = "INSERT INTO banners (
                titulo_banner, imagen_banner, enlace_banner, texto_boton_banner,
                posicion_banner, orden_banner, estado_banner,
                fecha_inicio_banner, fecha_fin_banner
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $data['titulo_banner'] ?? '',
                $data['imagen_banner'] ?? '',
                $data['enlace_banner'] ?? null,
                $data['texto_boton_banner'] ?? null,
                $data['posicion_banner'] ?? 'home',
                $data['orden_banner'] ?? 0,
                isset($data['estado_banner']) ? 1 : 0,
                !empty($data['fecha_inicio_banner']) ? $data['fecha_inicio_banner'] : null,
                !empty($data['fecha_fin_banner']) ? $data['fecha_fin_banner'] : null
            ]);
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error en BannerModel::create: " . $e->getMessage());
            return false;
        }
    }
    
    public static function update($id, $data) {
        try {
            $pdo = getDB();
            $sql = "UPDATE banners SET 
                titulo_banner = ?, imagen_banner = ?, enlace_banner = ?, texto_boton_banner = ?,
                posicion_banner = ?, orden_banner = ?, estado_banner = ?,
                fecha_inicio_banner = ?, fecha_fin_banner = ?
                WHERE id_banner = ?";
            
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $data['titulo_banner'] ?? '',
                $data['imagen_banner'] ?? '',
                $data['enlace_banner'] ?? null,
                $data['texto_boton_banner'] ?? null,
                $data['posicion_banner'] ?? 'home',
                $data['orden_banner'] ?? 0,
                isset($data['estado_banner']) ? 1 : 0,
                !empty($data['fecha_inicio_banner']) ? $data['fecha_inicio_banner'] : null,
                !empty($data['fecha_fin_banner']) ? $data['fecha_fin_banner'] : null,
                $id
            ]);
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error en BannerModel::update: " . $e->getMessage());
            return false;
        }
    }
    
    public static function delete($id) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("DELETE FROM banners WHERE id_banner = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error en BannerModel::delete: " . $e->getMessage());
            return false;
        }
    }
}
