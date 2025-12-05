<?php
/**
 * Modelo de Productos
 */

require_once __DIR__ . '/Database.php';

class ProductoModel {
    public static function getAll($filters = []) {
        try {
            $pdo = getDB();
            $sql = "SELECT p.*, cp.nombre as categoria_nombre 
                    FROM productos p 
                    LEFT JOIN categorias_productos cp ON p.categoria_id = cp.id 
                    WHERE 1=1";
            $params = [];
            
            if (!empty($filters['categoria_id'])) {
                $sql .= " AND p.categoria_id = ?";
                $params[] = $filters['categoria_id'];
            }
            
            if (!empty($filters['buscar'])) {
                $sql .= " AND p.nombre LIKE ?";
                $params[] = "%{$filters['buscar']}%";
            }
            
            if (isset($filters['stock_bajo'])) {
                $sql .= " AND p.stock_actual <= p.stock_minimo";
            }
            
            $sql .= " ORDER BY p.nombre";
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
            $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public static function create($data) {
        try {
            $pdo = getDB();
            $sql = "INSERT INTO productos (categoria_id, nombre, descripcion, codigo_barras, stock_actual, stock_minimo, precio_compra, precio_venta, proveedor, fecha_vencimiento, unidad_medida) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $data['categoria_id'], $data['nombre'], $data['descripcion'] ?? null,
                $data['codigo_barras'] ?? null, $data['stock_actual'] ?? 0,
                $data['stock_minimo'] ?? 0, $data['precio_compra'] ?? 0,
                $data['precio_venta'] ?? 0, $data['proveedor'] ?? null,
                $data['fecha_vencimiento'] ?? null, $data['unidad_medida'] ?? 'unidad'
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public static function update($id, $data) {
        try {
            $pdo = getDB();
            $sql = "UPDATE productos SET categoria_id=?, nombre=?, descripcion=?, codigo_barras=?, 
                    stock_actual=?, stock_minimo=?, precio_compra=?, precio_venta=?, proveedor=?, 
                    fecha_vencimiento=?, unidad_medida=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $data['categoria_id'], $data['nombre'], $data['descripcion'] ?? null,
                $data['codigo_barras'] ?? null, $data['stock_actual'] ?? 0,
                $data['stock_minimo'] ?? 0, $data['precio_compra'] ?? 0,
                $data['precio_venta'] ?? 0, $data['proveedor'] ?? null,
                $data['fecha_vencimiento'] ?? null, $data['unidad_medida'] ?? 'unidad', $id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public static function delete($id) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("UPDATE productos SET activo = 0 WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public static function updateStock($id, $cantidad, $tipo = 'entrada') {
        try {
            $pdo = getDB();
            $producto = self::getById($id);
            if (!$producto) return false;
            
            $cantidad_anterior = $producto['stock_actual'];
            $cantidad_nueva = $tipo === 'entrada' 
                ? $cantidad_anterior + $cantidad 
                : $cantidad_anterior - $cantidad;
            
            // Actualizar stock
            $stmt = $pdo->prepare("UPDATE productos SET stock_actual = ? WHERE id = ?");
            $stmt->execute([$cantidad_nueva, $id]);
            
            // Registrar movimiento
            $movStmt = $pdo->prepare("INSERT INTO movimientos_inventario (producto_id, tipo, cantidad, cantidad_anterior, cantidad_nueva) 
                                     VALUES (?, ?, ?, ?, ?)");
            $movStmt->execute([$id, $tipo, $cantidad, $cantidad_anterior, $cantidad_nueva]);
            
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}

