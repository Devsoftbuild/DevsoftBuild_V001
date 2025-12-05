<?php
/**
 * Modelo de Empleados
 */

require_once __DIR__ . '/Database.php';

class EmpleadoModel {
    public static function getAll($filters = []) {
        try {
            $pdo = getDB();
            $sql = "SELECT * FROM empleados WHERE 1=1";
            $params = [];
            
            if (!empty($filters['buscar'])) {
                $sql .= " AND (nombre LIKE ? OR apellido LIKE ? OR email LIKE ?)";
                $search = "%{$filters['buscar']}%";
                $params = array_merge($params, [$search, $search, $search]);
            }
            
            if (isset($filters['activo'])) {
                $sql .= " AND activo = ?";
                $params[] = $filters['activo'];
            }
            
            $sql .= " ORDER BY nombre, apellido";
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
            $stmt = $pdo->prepare("SELECT * FROM empleados WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public static function create($data) {
        try {
            $pdo = getDB();
            $sql = "INSERT INTO empleados (nombre, apellido, email, telefono, fecha_nacimiento, genero, direccion, especialidad, porcentaje_comision, salario_base, fecha_ingreso) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $data['nombre'], $data['apellido'], $data['email'] ?? null,
                $data['telefono'], $data['fecha_nacimiento'] ?? null,
                $data['genero'] ?? null, $data['direccion'] ?? null,
                $data['especialidad'] ?? null, $data['porcentaje_comision'] ?? 0,
                $data['salario_base'] ?? 0, $data['fecha_ingreso'] ?? null
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public static function update($id, $data) {
        try {
            $pdo = getDB();
            $sql = "UPDATE empleados SET nombre=?, apellido=?, email=?, telefono=?, fecha_nacimiento=?, 
                    genero=?, direccion=?, especialidad=?, porcentaje_comision=?, salario_base=?, fecha_ingreso=? 
                    WHERE id=?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $data['nombre'], $data['apellido'], $data['email'] ?? null,
                $data['telefono'], $data['fecha_nacimiento'] ?? null,
                $data['genero'] ?? null, $data['direccion'] ?? null,
                $data['especialidad'] ?? null, $data['porcentaje_comision'] ?? 0,
                $data['salario_base'] ?? 0, $data['fecha_ingreso'] ?? null, $id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public static function delete($id) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("UPDATE empleados SET activo = 0 WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}

