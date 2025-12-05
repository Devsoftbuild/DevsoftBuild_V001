<?php
/**
 * Modelo de Contactos
 * DevSoft Build SAC
 */

require_once __DIR__ . '/Database.php';

class ContactoModel {
    public static function create($data) {
        try {
            $pdo = getDB();
            $sql = "INSERT INTO contactos (
                nombre_contacto, email_contacto, telefono_contacto, empresa_contacto,
                tipo_proyecto_contacto, mensaje_contacto, ip_contacto, user_agent_contacto
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $data['nombre_contacto'] ?? '',
                $data['email_contacto'] ?? '',
                $data['telefono_contacto'] ?? null,
                $data['empresa_contacto'] ?? null,
                $data['tipo_proyecto_contacto'] ?? null,
                $data['mensaje_contacto'] ?? '',
                $_SERVER['REMOTE_ADDR'] ?? null,
                $_SERVER['HTTP_USER_AGENT'] ?? null
            ]);
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error en ContactoModel::create: " . $e->getMessage());
            return false;
        }
    }
    
    public static function getAll($filters = []) {
        try {
            $pdo = getDB();
            $sql = "SELECT * FROM contactos WHERE 1=1";
            $params = [];
            
            if (!empty($filters['estado_contacto'])) {
                $sql .= " AND estado_contacto = ?";
                $params[] = $filters['estado_contacto'];
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
            $stmt = $pdo->prepare("SELECT * FROM contactos WHERE id_contacto = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public static function updateEstado($id, $estado) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("UPDATE contactos SET estado_contacto = ? WHERE id_contacto = ?");
            return $stmt->execute([$estado, $id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}

