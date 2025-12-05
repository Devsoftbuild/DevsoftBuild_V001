<?php
/**
 * Modelo de Citas
 * Maneja todas las operaciones relacionadas con citas
 */

require_once __DIR__ . '/Database.php';

class CitaModel {
    /**
     * Obtener todas las citas con filtros
     * @param array $filters
     * @return array
     */
    public static function getAll($filters = []) {
        try {
            $pdo = getDB();
            
            $sql = "SELECT c.*, 
                    cl.nombre as cliente_nombre, 
                    cl.apellido as cliente_apellido,
                    cl.telefono as cliente_telefono,
                    cl.email as cliente_email,
                    s.nombre as servicio_nombre,
                    s.precio as servicio_precio,
                    e.nombre as empleado_nombre,
                    e.apellido as empleado_apellido
                    FROM citas c
                    INNER JOIN clientes cl ON c.cliente_id = cl.id
                    INNER JOIN servicios s ON c.servicio_id = s.id
                    LEFT JOIN empleados e ON c.empleado_id = e.id
                    WHERE 1=1";
            
            $params = [];
            
            // Filtros
            if (!empty($filters['estado'])) {
                $sql .= " AND c.estado = ?";
                $params[] = $filters['estado'];
            }
            
            if (!empty($filters['fecha_desde'])) {
                $sql .= " AND c.fecha >= ?";
                $params[] = $filters['fecha_desde'];
            }
            
            if (!empty($filters['fecha_hasta'])) {
                $sql .= " AND c.fecha <= ?";
                $params[] = $filters['fecha_hasta'];
            }
            
            if (!empty($filters['cliente_id'])) {
                $sql .= " AND c.cliente_id = ?";
                $params[] = $filters['cliente_id'];
            }
            
            if (!empty($filters['servicio_id'])) {
                $sql .= " AND c.servicio_id = ?";
                $params[] = $filters['servicio_id'];
            }
            
            if (!empty($filters['empleado_id'])) {
                $sql .= " AND c.empleado_id = ?";
                $params[] = $filters['empleado_id'];
            }
            
            if (!empty($filters['buscar'])) {
                $sql .= " AND (cl.nombre LIKE ? OR cl.apellido LIKE ? OR cl.telefono LIKE ? OR s.nombre LIKE ?)";
                $search = "%{$filters['buscar']}%";
                $params[] = $search;
                $params[] = $search;
                $params[] = $search;
                $params[] = $search;
            }
            
            $sql .= " ORDER BY c.fecha DESC, c.hora DESC";
            
            // Paginación
            if (!empty($filters['limit'])) {
                $sql .= " LIMIT " . (int)$filters['limit'];
                if (!empty($filters['offset'])) {
                    $sql .= " OFFSET " . (int)$filters['offset'];
                }
            }
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error al obtener citas: " . $e->getMessage());
            }
            return [];
        }
    }
    
    /**
     * Obtener una cita por ID
     * @param int $id
     * @return array|false
     */
    public static function getById($id) {
        try {
            $pdo = getDB();
            $sql = "SELECT c.*, 
                    cl.nombre as cliente_nombre, 
                    cl.apellido as cliente_apellido,
                    cl.telefono as cliente_telefono,
                    cl.email as cliente_email,
                    s.nombre as servicio_nombre,
                    s.precio as servicio_precio,
                    s.duracion_minutos as servicio_duracion,
                    e.nombre as empleado_nombre,
                    e.apellido as empleado_apellido
                    FROM citas c
                    INNER JOIN clientes cl ON c.cliente_id = cl.id
                    INNER JOIN servicios s ON c.servicio_id = s.id
                    LEFT JOIN empleados e ON c.empleado_id = e.id
                    WHERE c.id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error al obtener cita: " . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Crear una nueva cita
     * @param array $data
     * @return int|false ID de la cita creada o false si falla
     */
    public static function create($data) {
        try {
            $pdo = getDB();
            
            // Calcular total
            $precio = $data['precio'];
            $descuento = isset($data['descuento']) ? $data['descuento'] : 0;
            $total = $precio - $descuento;
            
            $sql = "INSERT INTO citas (
                cliente_id, servicio_id, empleado_id, fecha, hora, 
                duracion_minutos, estado, precio, descuento, total, notas
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $data['cliente_id'],
                $data['servicio_id'],
                $data['empleado_id'] ?? null,
                $data['fecha'],
                $data['hora'],
                $data['duracion_minutos'],
                $data['estado'] ?? 'pendiente',
                $precio,
                $descuento,
                $total,
                $data['notas'] ?? null
            ]);
            
            return $pdo->lastInsertId();
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error al crear cita: " . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Actualizar una cita
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function update($id, $data) {
        try {
            $pdo = getDB();
            
            // Calcular total
            $precio = $data['precio'];
            $descuento = isset($data['descuento']) ? $data['descuento'] : 0;
            $total = $precio - $descuento;
            
            $sql = "UPDATE citas SET
                cliente_id = ?,
                servicio_id = ?,
                empleado_id = ?,
                fecha = ?,
                hora = ?,
                duracion_minutos = ?,
                estado = ?,
                precio = ?,
                descuento = ?,
                total = ?,
                notas = ?
                WHERE id = ?";
            
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $data['cliente_id'],
                $data['servicio_id'],
                $data['empleado_id'] ?? null,
                $data['fecha'],
                $data['hora'],
                $data['duracion_minutos'],
                $data['estado'],
                $precio,
                $descuento,
                $total,
                $data['notas'] ?? null,
                $id
            ]);
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error al actualizar cita: " . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Eliminar una cita
     * @param int $id
     * @return bool
     */
    public static function delete($id) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("DELETE FROM citas WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error al eliminar cita: " . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Cambiar estado de una cita
     * @param int $id
     * @param string $estado
     * @return bool
     */
    public static function changeStatus($id, $estado) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("UPDATE citas SET estado = ? WHERE id = ?");
            return $stmt->execute([$estado, $id]);
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error al cambiar estado: " . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Obtener citas del día
     * @param string $fecha
     * @return array
     */
    public static function getByDate($fecha) {
        try {
            $pdo = getDB();
            $sql = "SELECT c.*, 
                    cl.nombre as cliente_nombre, 
                    cl.apellido as cliente_apellido,
                    s.nombre as servicio_nombre,
                    e.nombre as empleado_nombre,
                    e.apellido as empleado_apellido
                    FROM citas c
                    INNER JOIN clientes cl ON c.cliente_id = cl.id
                    INNER JOIN servicios s ON c.servicio_id = s.id
                    LEFT JOIN empleados e ON c.empleado_id = e.id
                    WHERE c.fecha = ?
                    ORDER BY c.hora ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$fecha]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error al obtener citas por fecha: " . $e->getMessage());
            }
            return [];
        }
    }
    
    /**
     * Contar citas con filtros
     * @param array $filters
     * @return int
     */
    public static function count($filters = []) {
        try {
            $pdo = getDB();
            
            $sql = "SELECT COUNT(*) FROM citas c
                    INNER JOIN clientes cl ON c.cliente_id = cl.id
                    INNER JOIN servicios s ON c.servicio_id = s.id
                    WHERE 1=1";
            
            $params = [];
            
            // Aplicar mismos filtros que getAll
            if (!empty($filters['estado'])) {
                $sql .= " AND c.estado = ?";
                $params[] = $filters['estado'];
            }
            
            if (!empty($filters['fecha_desde'])) {
                $sql .= " AND c.fecha >= ?";
                $params[] = $filters['fecha_desde'];
            }
            
            if (!empty($filters['fecha_hasta'])) {
                $sql .= " AND c.fecha <= ?";
                $params[] = $filters['fecha_hasta'];
            }
            
            if (!empty($filters['buscar'])) {
                $sql .= " AND (cl.nombre LIKE ? OR cl.apellido LIKE ? OR cl.telefono LIKE ? OR s.nombre LIKE ?)";
                $search = "%{$filters['buscar']}%";
                $params[] = $search;
                $params[] = $search;
                $params[] = $search;
                $params[] = $search;
            }
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error al contar citas: " . $e->getMessage());
            }
            return 0;
        }
    }
}

