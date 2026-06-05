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
     * @param string|null $grupo Valor de grupo_configuracion (requerido para que getByGroup() encuentre la fila)
     * @return bool
     */
    public static function set($clave, $valor, $tipo = 'texto', $grupo = null) {
        try {
            $pdo = getDB();
            
            // Convertir valor según tipo
            if ($tipo === 'json') {
                $valor = json_encode($valor);
            } elseif ($tipo === 'booleano') {
                $valor = $valor ? '1' : '0';
            }
            
            if ($grupo !== null && $grupo !== '') {
                $stmt = $pdo->prepare("
                    INSERT INTO configuraciones (clave_configuracion, valor_configuracion, tipo_configuracion, grupo_configuracion)
                    VALUES (?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE valor_configuracion = ?, tipo_configuracion = ?, grupo_configuracion = ?
                ");
                $result = $stmt->execute([$clave, $valor, $tipo, $grupo, $valor, $tipo, $grupo]);
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO configuraciones (clave_configuracion, valor_configuracion, tipo_configuracion)
                    VALUES (?, ?, ?)
                    ON DUPLICATE KEY UPDATE valor_configuracion = ?, tipo_configuracion = ?
                ");
                $result = $stmt->execute([$clave, $valor, $tipo, $valor, $tipo]);
            }
            
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
     * Asigna grupo_configuracion a claves conocidas que quedaron en NULL (guardados antes de guardar el grupo).
     */
    public static function repairGruposConocidos() {
        $map = [
            'nombre_empresa' => 'general',
            'logo' => 'general',
            'telefono' => 'general',
            'email' => 'general',
            'direccion' => 'general',
            'ciudad' => 'general',
            'pais' => 'general',
            'logo_url' => 'general',
            'email_contacto' => 'contacto',
            'telefono_contacto' => 'contacto',
            'whatsapp' => 'contacto',
            'facebook' => 'redes_sociales',
            'instagram' => 'redes_sociales',
            'twitter' => 'redes_sociales',
            'linkedin' => 'redes_sociales',
            'youtube' => 'redes_sociales',
            'github' => 'redes_sociales',
            'meta_titulo_default' => 'seo',
            'meta_descripcion_default' => 'seo',
            'meta_keywords_default' => 'seo',
        ];
        try {
            $pdo = getDB();
            $sql = "UPDATE configuraciones SET grupo_configuracion = ? WHERE clave_configuracion = ? AND (grupo_configuracion IS NULL OR grupo_configuracion = '')";
            $stmt = $pdo->prepare($sql);
            foreach ($map as $clave => $grupo) {
                $stmt->execute([$grupo, $clave]);
            }
            self::clearCache();
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log('repairGruposConocidos: ' . $e->getMessage());
            }
        }
    }

    /**
     * Fuerza tipo "texto" en claves del formulario de configuración (corrige whatsapp/tel guardados como numero).
     */
    public static function normalizarTiposTextoFormulario() {
        try {
            $pdo = getDB();
            $pdo->exec("UPDATE configuraciones SET tipo_configuracion = 'texto' WHERE clave_configuracion IN (
                'whatsapp','telefono_contacto','telefono','email','email_contacto',
                'facebook','instagram','twitter','linkedin','youtube','github',
                'logo','logo_url','direccion','ciudad','pais','nombre_empresa',
                'meta_titulo_default','meta_descripcion_default','meta_keywords_default'
            )");
            self::clearCache();
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log('normalizarTiposTextoFormulario: ' . $e->getMessage());
            }
        }
    }

    /**
     * Limpiar cache de configuración
     */
    public static function clearCache() {
        self::$cache = [];
    }
}

