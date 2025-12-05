<?php
/**
 * Modelo de Servicios
 * DevSoft Build SAC
 */

require_once __DIR__ . '/Database.php';

class ServicioModel {
    /**
     * Obtener todos los servicios con filtros
     * @param array $filters
     * @return array
     */
    public static function getAll($filters = []) {
        try {
            $pdo = getDB();
            $sql = "SELECT * FROM servicios WHERE 1=1";
            $params = [];
            
            if (isset($filters['estado_servicio'])) {
                $sql .= " AND estado_servicio = ?";
                $params[] = $filters['estado_servicio'];
            }
            
            if (isset($filters['destacado_servicio'])) {
                $sql .= " AND destacado_servicio = ?";
                $params[] = $filters['destacado_servicio'];
            }
            
            if (!empty($filters['buscar'])) {
                $sql .= " AND (nombre_servicio LIKE ? OR descripcion_corta_servicio LIKE ?)";
                $search = "%{$filters['buscar']}%";
                $params[] = $search;
                $params[] = $search;
            }
            
            $sql .= " ORDER BY orden_servicio ASC, nombre_servicio ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error en ServicioModel::getAll: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtener servicio por ID
     * @param int $id
     * @return array|false
     */
    public static function getById($id) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT * FROM servicios WHERE id_servicio = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error en ServicioModel::getById: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener servicio por slug
     * @param string $slug
     * @return array|false
     */
    public static function getBySlug($slug) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT * FROM servicios WHERE slug_servicio = ? AND estado_servicio = 1");
            $stmt->execute([$slug]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error en ServicioModel::getBySlug: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener servicios destacados
     * @param int $limit
     * @return array
     */
    public static function getDestacados($limit = 4) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("
                SELECT * FROM servicios 
                WHERE destacado_servicio = 1 AND estado_servicio = 1 
                ORDER BY orden_servicio ASC 
                LIMIT ?
            ");
            $stmt->execute([$limit]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error en ServicioModel::getDestacados: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Crear nuevo servicio
     * @param array $data
     * @return int|false ID del servicio creado o false
     */
    public static function create($data) {
        try {
            $pdo = getDB();
            $sql = "INSERT INTO servicios (
                nombre_servicio, slug_servicio, descripcion_corta_servicio, descripcion_larga_servicio,
                icono_servicio, imagen_servicio, precio_desde_servicio, orden_servicio,
                destacado_servicio, estado_servicio,
                meta_titulo_servicio, meta_descripcion_servicio, meta_keywords_servicio, meta_og_image_servicio
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $data['nombre_servicio'] ?? '',
                $data['slug_servicio'] ?? self::generateSlug($data['nombre_servicio'] ?? ''),
                $data['descripcion_corta_servicio'] ?? null,
                $data['descripcion_larga_servicio'] ?? null,
                $data['icono_servicio'] ?? null,
                $data['imagen_servicio'] ?? null,
                $data['precio_desde_servicio'] ?? null,
                $data['orden_servicio'] ?? 0,
                $data['destacado_servicio'] ?? 0,
                $data['estado_servicio'] ?? 1,
                $data['meta_titulo_servicio'] ?? null,
                $data['meta_descripcion_servicio'] ?? null,
                $data['meta_keywords_servicio'] ?? null,
                $data['meta_og_image_servicio'] ?? null
            ]);
            
            return $pdo->lastInsertId();
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error en ServicioModel::create: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Actualizar servicio
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function update($id, $data) {
        try {
            $pdo = getDB();
            $sql = "UPDATE servicios SET 
                nombre_servicio = ?, slug_servicio = ?, descripcion_corta_servicio = ?, descripcion_larga_servicio = ?,
                icono_servicio = ?, imagen_servicio = ?, precio_desde_servicio = ?, orden_servicio = ?,
                destacado_servicio = ?, estado_servicio = ?,
                meta_titulo_servicio = ?, meta_descripcion_servicio = ?, meta_keywords_servicio = ?, meta_og_image_servicio = ?
                WHERE id_servicio = ?";
            
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $data['nombre_servicio'] ?? '',
                $data['slug_servicio'] ?? self::generateSlug($data['nombre_servicio'] ?? ''),
                $data['descripcion_corta_servicio'] ?? null,
                $data['descripcion_larga_servicio'] ?? null,
                $data['icono_servicio'] ?? null,
                $data['imagen_servicio'] ?? null,
                $data['precio_desde_servicio'] ?? null,
                $data['orden_servicio'] ?? 0,
                $data['destacado_servicio'] ?? 0,
                $data['estado_servicio'] ?? 1,
                $data['meta_titulo_servicio'] ?? null,
                $data['meta_descripcion_servicio'] ?? null,
                $data['meta_keywords_servicio'] ?? null,
                $data['meta_og_image_servicio'] ?? null,
                $id
            ]);
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error en ServicioModel::update: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Eliminar servicio (soft delete)
     * @param int $id
     * @return bool
     */
    public static function delete($id) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("UPDATE servicios SET estado_servicio = 0 WHERE id_servicio = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error en ServicioModel::delete: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Generar slug a partir de un texto
     * @param string $text
     * @return string
     */
    private static function generateSlug($text) {
        // Convertir a minúsculas
        $text = mb_strtolower($text, 'UTF-8');
        
        // Reemplazar caracteres especiales
        $text = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ñ'], ['a', 'e', 'i', 'o', 'u', 'n'], $text);
        
        // Reemplazar espacios y caracteres especiales por guiones
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        
        // Eliminar guiones al inicio y final
        $text = trim($text, '-');
        
        return $text;
    }
}
