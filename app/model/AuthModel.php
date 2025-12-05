<?php
/**
 * Modelo de Autenticación
 * Maneja el login y sesiones de usuarios administradores
 */

require_once __DIR__ . '/Database.php';

class AuthModel {
    /**
     * Verificar credenciales de usuario
     * @param string $email
     * @param string $password
     * @return array|false
     */
    public static function login($email, $password) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT id_usuario, nombre_usuario, email_usuario, password_usuario, rol_usuario, estado_usuario FROM usuarios WHERE email_usuario = ? AND estado_usuario = 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password_usuario'])) {
                // Actualizar último acceso
                $updateStmt = $pdo->prepare("UPDATE usuarios SET ultimo_acceso_usuario = NOW() WHERE id_usuario = ?");
                $updateStmt->execute([$user['id_usuario']]);
                
                // Normalizar nombres de campos para compatibilidad
                return [
                    'id' => $user['id_usuario'],
                    'nombre' => $user['nombre_usuario'],
                    'email' => $user['email_usuario'],
                    'rol' => $user['rol_usuario'],
                    'activo' => $user['estado_usuario']
                ];
            }
            
            return false;
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error en login: " . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Verificar si el usuario está autenticado
     * @return bool
     */
    public static function isAuthenticated() {
        return isset($_SESSION['admin_user_id']) && !empty($_SESSION['admin_user_id']);
    }
    
    /**
     * Obtener usuario actual
     * @return array|false
     */
    public static function getCurrentUser() {
        if (!self::isAuthenticated()) {
            return false;
        }
        
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT id_usuario, nombre_usuario, email_usuario, rol_usuario, estado_usuario FROM usuarios WHERE id_usuario = ? AND estado_usuario = 1");
            $stmt->execute([$_SESSION['admin_user_id']]);
            $user = $stmt->fetch();
            
            if ($user) {
                // Normalizar nombres de campos para compatibilidad
                return [
                    'id' => $user['id_usuario'],
                    'nombre' => $user['nombre_usuario'],
                    'email' => $user['email_usuario'],
                    'rol' => $user['rol_usuario'],
                    'activo' => $user['estado_usuario']
                ];
            }
            
            return false;
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error al obtener usuario: " . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Cerrar sesión
     */
    public static function logout() {
        unset($_SESSION['admin_user_id']);
        unset($_SESSION['admin_user_name']);
        unset($_SESSION['admin_user_role']);
        session_destroy();
    }
    
    /**
     * Requerir autenticación (redirige si no está autenticado)
     */
    public static function requireAuth() {
        if (!self::isAuthenticated()) {
            header('Location: ' . URL_SERV . '/dw-panel/login.php');
            exit;
        }
    }
}

