<?php
/**
 * Modelo de Galería
 */

require_once __DIR__ . '/Database.php';

class GaleriaModel {
    public static function getAll($filters = []) {
        try {
            $pdo = getDB();
            $sql = "SELECT g.*, s.nombre as servicio_nombre 
                    FROM galeria_trabajos g 
                    LEFT JOIN servicios s ON g.servicio_id = s.id 
                    WHERE 1=1";
            $params = [];
            
            if (isset($filters['activo'])) {
                $sql .= " AND g.activo = ?";
                $params[] = $filters['activo'];
            }
            
            if (isset($filters['destacado'])) {
                $sql .= " AND g.destacado = ?";
                $params[] = $filters['destacado'];
            }
            
            if (!empty($filters['servicio_id'])) {
                $sql .= " AND g.servicio_id = ?";
                $params[] = $filters['servicio_id'];
            }
            
            $sql .= " ORDER BY g.destacado DESC, g.created_at DESC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error: " . $e->getMessage());
            return [];
        }
    }
    
    public static function getById($id) {
        try {
            $pdo = getDB();
            $sql = "SELECT g.*, s.nombre as servicio_nombre 
                    FROM galeria_trabajos g 
                    LEFT JOIN servicios s ON g.servicio_id = s.id 
                    WHERE g.id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error: " . $e->getMessage());
            return false;
        }
    }
    
    public static function create($data) {
        try {
            $pdo = getDB();
            $sql = "INSERT INTO galeria_trabajos (servicio_id, cliente_id, empleado_id, titulo, descripcion, imagen_antes, imagen_despues, tipo, destacado, activo) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $data['servicio_id'] ?? null, $data['cliente_id'] ?? null, $data['empleado_id'] ?? null,
                $data['titulo'] ?? null, $data['descripcion'] ?? null,
                $data['imagen_antes'] ?? null, $data['imagen_despues'] ?? null,
                $data['tipo'] ?? 'solo_despues', $data['destacado'] ?? 0, $data['activo'] ?? 1
            ]);
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error: " . $e->getMessage());
            return false;
        }
    }
    
    public static function update($id, $data) {
        try {
            $pdo = getDB();
            $sql = "UPDATE galeria_trabajos SET servicio_id=?, cliente_id=?, empleado_id=?, titulo=?, descripcion=?, imagen_antes=?, imagen_despues=?, tipo=?, destacado=?, activo=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $data['servicio_id'] ?? null, $data['cliente_id'] ?? null, $data['empleado_id'] ?? null,
                $data['titulo'] ?? null, $data['descripcion'] ?? null,
                $data['imagen_antes'] ?? null, $data['imagen_despues'] ?? null,
                $data['tipo'] ?? 'solo_despues', $data['destacado'] ?? 0, $data['activo'] ?? 1, $id
            ]);
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error: " . $e->getMessage());
            return false;
        }
    }
    
    public static function delete($id) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("UPDATE galeria_trabajos SET activo = 0 WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public static function getFeatured($limit = 6) {
        return self::getAll(['activo' => 1, 'destacado' => 1]);
    }
}

