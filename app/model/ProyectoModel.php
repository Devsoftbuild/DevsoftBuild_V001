<?php
/**
 * Modelo de Proyectos
 * DevSoft Build SAC
 */

require_once __DIR__ . '/Database.php';

class ProyectoModel {
    /**
     * Obtener todos los proyectos con filtros
     * @param array $filters
     * @return array
     */
    public static function getAll($filters = []) {
        try {
            $pdo = getDB();
            $sql = "SELECT * FROM proyectos WHERE 1=1";
            $params = [];
            
            if (isset($filters['estado_proyecto'])) {
                $sql .= " AND estado_proyecto = ?";
                $params[] = $filters['estado_proyecto'];
            }
            
            if (isset($filters['destacado_proyecto'])) {
                $sql .= " AND destacado_proyecto = ?";
                $params[] = $filters['destacado_proyecto'];
            }
            
            if (!empty($filters['tipo_proyecto'])) {
                $sql .= " AND tipo_proyecto = ?";
                $params[] = $filters['tipo_proyecto'];
            }
            
            if (!empty($filters['buscar'])) {
                $sql .= " AND (nombre_proyecto LIKE ? OR descripcion_corta_proyecto LIKE ?)";
                $search = "%{$filters['buscar']}%";
                $params[] = $search;
                $params[] = $search;
            }
            
            $sql .= " ORDER BY orden_proyecto ASC, created_at DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error en ProyectoModel::getAll: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtener proyecto por ID
     * @param int $id
     * @return array|false
     */
    public static function getById($id) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT * FROM proyectos WHERE id_proyecto = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error en ProyectoModel::getById: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener proyecto por slug
     * @param string $slug
     * @return array|false
     */
    public static function getBySlug($slug) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT * FROM proyectos WHERE slug_proyecto = ?");
            $stmt->execute([$slug]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error en ProyectoModel::getBySlug: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener proyectos destacados
     * @param int $limit
     * @return array
     */
    public static function getDestacados($limit = 6) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("
                SELECT * FROM proyectos 
                WHERE destacado_proyecto = 1 AND estado_proyecto = 'completado'
                ORDER BY orden_proyecto ASC, created_at DESC
                LIMIT ?
            ");
            $stmt->execute([$limit]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error en ProyectoModel::getDestacados: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtener tecnologías de un proyecto
     * @param int $proyecto_id
     * @return array
     */
    public static function getTecnologias($proyecto_id) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("
                SELECT t.* FROM tecnologias t
                INNER JOIN tecnologias_proyectos tp ON t.id_tecnologia = tp.tecnologia_id_tecnologia_proyecto
                WHERE tp.proyecto_id_tecnologia_proyecto = ?
                ORDER BY t.nombre_tecnologia ASC
            ");
            $stmt->execute([$proyecto_id]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error en ProyectoModel::getTecnologias: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtener imágenes de un proyecto
     * @param int $proyecto_id
     * @return array
     */
    public static function getImagenes($proyecto_id) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("
                SELECT * FROM imagenes_proyectos 
                WHERE proyecto_id_imagen_proyecto = ?
                ORDER BY orden_imagen_proyecto ASC
            ");
            $stmt->execute([$proyecto_id]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error en ProyectoModel::getImagenes: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Crear nuevo proyecto
     * @param array $data
     * @return int|false
     */
    public static function create($data) {
        try {
            $pdo = getDB();
            $sql = "INSERT INTO proyectos (
                nombre_proyecto, slug_proyecto, descripcion_corta_proyecto, descripcion_larga_proyecto,
                imagen_principal_proyecto, tipo_proyecto, cliente_proyecto, url_proyecto,
                fecha_inicio_proyecto, fecha_fin_proyecto, estado_proyecto, destacado_proyecto, orden_proyecto,
                meta_titulo_proyecto, meta_descripcion_proyecto, meta_keywords_proyecto, meta_og_image_proyecto
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $data['nombre_proyecto'] ?? '',
                $data['slug_proyecto'] ?? self::generateSlug($data['nombre_proyecto'] ?? ''),
                $data['descripcion_corta_proyecto'] ?? null,
                $data['descripcion_larga_proyecto'] ?? null,
                $data['imagen_principal_proyecto'] ?? null,
                $data['tipo_proyecto'] ?? 'web',
                $data['cliente_proyecto'] ?? null,
                $data['url_proyecto'] ?? null,
                $data['fecha_inicio_proyecto'] ?? null,
                $data['fecha_fin_proyecto'] ?? null,
                $data['estado_proyecto'] ?? 'completado',
                $data['destacado_proyecto'] ?? 0,
                $data['orden_proyecto'] ?? 0,
                $data['meta_titulo_proyecto'] ?? null,
                $data['meta_descripcion_proyecto'] ?? null,
                $data['meta_keywords_proyecto'] ?? null,
                $data['meta_og_image_proyecto'] ?? null
            ]);
            
            return $pdo->lastInsertId();
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error en ProyectoModel::create: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Actualizar proyecto
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function update($id, $data) {
        try {
            $pdo = getDB();
            $sql = "UPDATE proyectos SET 
                nombre_proyecto = ?, slug_proyecto = ?, descripcion_corta_proyecto = ?, descripcion_larga_proyecto = ?,
                imagen_principal_proyecto = ?, tipo_proyecto = ?, cliente_proyecto = ?, url_proyecto = ?,
                fecha_inicio_proyecto = ?, fecha_fin_proyecto = ?, estado_proyecto = ?, destacado_proyecto = ?, orden_proyecto = ?,
                meta_titulo_proyecto = ?, meta_descripcion_proyecto = ?, meta_keywords_proyecto = ?, meta_og_image_proyecto = ?
                WHERE id_proyecto = ?";
            
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $data['nombre_proyecto'] ?? '',
                $data['slug_proyecto'] ?? self::generateSlug($data['nombre_proyecto'] ?? ''),
                $data['descripcion_corta_proyecto'] ?? null,
                $data['descripcion_larga_proyecto'] ?? null,
                $data['imagen_principal_proyecto'] ?? null,
                $data['tipo_proyecto'] ?? 'web',
                $data['cliente_proyecto'] ?? null,
                $data['url_proyecto'] ?? null,
                $data['fecha_inicio_proyecto'] ?? null,
                $data['fecha_fin_proyecto'] ?? null,
                $data['estado_proyecto'] ?? 'completado',
                $data['destacado_proyecto'] ?? 0,
                $data['orden_proyecto'] ?? 0,
                $data['meta_titulo_proyecto'] ?? null,
                $data['meta_descripcion_proyecto'] ?? null,
                $data['meta_keywords_proyecto'] ?? null,
                $data['meta_og_image_proyecto'] ?? null,
                $id
            ]);
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error en ProyectoModel::update: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Eliminar proyecto
     * @param int $id
     * @return bool
     */
    public static function delete($id) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("UPDATE proyectos SET estado_proyecto = 'cancelado' WHERE id_proyecto = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error en ProyectoModel::delete: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Generar slug
     * @param string $text
     * @return string
     */
    private static function generateSlug($text) {
        $text = mb_strtolower($text, 'UTF-8');
        $text = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ñ'], ['a', 'e', 'i', 'o', 'u', 'n'], $text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        $text = trim($text, '-');
        return $text;
    }
}

