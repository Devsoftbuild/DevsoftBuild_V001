<?php
/**
 * Modelo de Clientes
 */

require_once __DIR__ . '/Database.php';

class ClienteModel {
    public static function getAll($filters = []) {
        try {
            $pdo = getDB();
            $sql = "SELECT * FROM clientes WHERE 1=1";
            $params = [];
            
            if (!empty($filters['buscar'])) {
                $sql .= " AND (nombre LIKE ? OR apellido LIKE ? OR telefono LIKE ? OR email LIKE ?)";
                $search = "%{$filters['buscar']}%";
                $params = array_merge($params, [$search, $search, $search, $search]);
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
            if (DEBUG_MODE) error_log("Error: " . $e->getMessage());
            return [];
        }
    }
    
    public static function getById($id) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public static function create($data) {
        try {
            $pdo = getDB();
            $sql = "INSERT INTO clientes (nombre, apellido, email, telefono, fecha_nacimiento, genero, direccion, ciudad, codigo_postal) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $data['nombre'], $data['apellido'], $data['email'] ?? null,
                $data['telefono'], $data['fecha_nacimiento'] ?? null,
                $data['genero'] ?? null, $data['direccion'] ?? null,
                $data['ciudad'] ?? null, $data['codigo_postal'] ?? null
            ]);
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error: " . $e->getMessage());
            return false;
        }
    }
    
    public static function update($id, $data) {
        try {
            $pdo = getDB();
            $sql = "UPDATE clientes SET nombre=?, apellido=?, email=?, telefono=?, fecha_nacimiento=?, 
                    genero=?, direccion=?, ciudad=?, codigo_postal=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $data['nombre'], $data['apellido'], $data['email'] ?? null,
                $data['telefono'], $data['fecha_nacimiento'] ?? null,
                $data['genero'] ?? null, $data['direccion'] ?? null,
                $data['ciudad'] ?? null, $data['codigo_postal'] ?? null, $id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public static function delete($id) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("UPDATE clientes SET activo = 0 WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}

