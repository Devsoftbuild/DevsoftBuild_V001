<?php
/**
 * Modelo de Configuración
 * Maneja las operaciones de configuración del sistema
 */

require_once __DIR__ . '/Database.php';

class ConfigModel {
    private static $cache = [];
    
    /**
     * Obtener configuración del sistema
     * @param string $clave
     * @param mixed $default
     * @return mixed
     */
    public static function get($clave, $default = null) {
        // Verificar cache
        if (isset(self::$cache[$clave])) {
            return self::$cache[$clave];
        }
        
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT valor_configuracion, tipo_configuracion FROM configuraciones WHERE clave_configuracion = ?");
            $stmt->execute([$clave]);
            $result = $stmt->fetch();
            
            if ($result) {
                $valor = $result['valor_configuracion'];
                
                // Convertir según el tipo
                switch ($result['tipo_configuracion']) {
                    case 'numero':
                        $valor = is_numeric($valor) ? (float)$valor : $default;
                        break;
                    case 'booleano':
                        $valor = filter_var($valor, FILTER_VALIDATE_BOOLEAN);
                        break;
                    case 'json':
                        $valor = json_decode($valor, true);
                        break;
                }
                
                self::$cache[$clave] = $valor;
                return $valor;
            }
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error al obtener configuración: " . $e->getMessage());
            }
        }
        
        return $default;
    }
    
    /**
     * Establecer configuración del sistema
     * @param string $clave
     * @param mixed $valor
     * @param string $tipo
     * @return bool
     */
    public static function set($clave, $valor, $tipo = 'texto') {
        try {
            $pdo = getDB();
            
            // Convertir valor según tipo
            if ($tipo === 'json') {
                $valor = json_encode($valor);
            } elseif ($tipo === 'booleano') {
                $valor = $valor ? '1' : '0';
            }
            
            $stmt = $pdo->prepare("
                INSERT INTO configuraciones (clave_configuracion, valor_configuracion, tipo_configuracion) 
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE valor_configuracion = ?, tipo_configuracion = ?
            ");
            
            $result = $stmt->execute([$clave, $valor, $tipo, $valor, $tipo]);
            
            // Limpiar cache
            if (isset(self::$cache[$clave])) {
                unset(self::$cache[$clave]);
            }
            
            return $result;
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error al establecer configuración: " . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Obtener todas las configuraciones de un grupo
     * @param string $grupo
     * @return array
     */
    public static function getByGroup($grupo) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT clave_configuracion, valor_configuracion, tipo_configuracion FROM configuraciones WHERE grupo_configuracion = ?");
            $stmt->execute([$grupo]);
            $results = $stmt->fetchAll();
            
            $config = [];
            foreach ($results as $row) {
                $valor = $row['valor_configuracion'];
                
                // Convertir según el tipo
                switch ($row['tipo_configuracion']) {
                    case 'numero':
                        $valor = is_numeric($valor) ? (float)$valor : null;
                        break;
                    case 'booleano':
                        $valor = filter_var($valor, FILTER_VALIDATE_BOOLEAN);
                        break;
                    case 'json':
                        $valor = json_decode($valor, true);
                        break;
                }
                
                $config[$row['clave_configuracion']] = $valor;
            }
            
            return $config;
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error al obtener configuraciones por grupo: " . $e->getMessage());
            }
            return [];
        }
    }
    
    /**
     * Limpiar cache de configuración
     */
    public static function clearCache() {
        self::$cache = [];
    }
}

