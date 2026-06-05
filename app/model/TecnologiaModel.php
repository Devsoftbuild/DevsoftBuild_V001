<?php
/**
 * Modelo de Tecnologías
 * DevSoft Build SAC
 */

require_once __DIR__ . '/Database.php';

class TecnologiaModel {
    /** @var string|null Último error PDO (para mensajes en panel) */
    private static $lastError = null;

    public static function getLastError() {
        return self::$lastError;
    }

    /**
     * Slug URL-safe a partir del nombre (máx. longitud acotada a 140 base para sufijos -n).
     */
    private static function slugifyNombre($nombre) {
        $s = mb_strtolower(trim((string) $nombre), 'UTF-8');
        $s = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü'], ['a', 'e', 'i', 'o', 'u', 'n', 'u'], $s);
        $s = preg_replace('/[^a-z0-9]+/', '-', $s);
        $s = trim($s, '-');
        if ($s === '') {
            $s = 'tecnologia';
        }
        return mb_substr($s, 0, 140);
    }

    /**
     * Garantiza slug único en tabla tecnologias.
     */
    private static function uniqueSlug(PDO $pdo, $baseSlug, $excludeId = null) {
        $slug = $baseSlug;
        $n = 0;
        while (true) {
            $sql = 'SELECT id_tecnologia FROM tecnologias WHERE slug_tecnologia = ?';
            $params = [$slug];
            if ($excludeId !== null) {
                $sql .= ' AND id_tecnologia <> ?';
                $params[] = (int) $excludeId;
            }
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            if (!$stmt->fetch()) {
                return $slug;
            }
            $n++;
            $slug = mb_substr($baseSlug, 0, 120) . '-' . $n;
        }
    }

    private static function nullableString($v) {
        if ($v === null || $v === '') {
            return null;
        }
        return is_string($v) ? $v : (string) $v;
    }

    public static function getAll($filters = []) {
        try {
            $pdo = getDB();
            $sql = "SELECT * FROM tecnologias WHERE 1=1";
            $params = [];
            
            if (isset($filters['estado_tecnologia'])) {
                $sql .= " AND estado_tecnologia = ?";
                $params[] = $filters['estado_tecnologia'];
            }
            
            if (!empty($filters['categoria_tecnologia'])) {
                $sql .= " AND categoria_tecnologia = ?";
                $params[] = $filters['categoria_tecnologia'];
            }

            if (!empty($filters['buscar'])) {
                $sql .= " AND (nombre_tecnologia LIKE ? OR descripcion_tecnologia LIKE ? OR categoria_tecnologia LIKE ?)";
                $like = '%' . $filters['buscar'] . '%';
                $params[] = $like;
                $params[] = $like;
                $params[] = $like;
            }
            
            $sql .= " ORDER BY categoria_tecnologia ASC, orden_tecnologia ASC, nombre_tecnologia ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            if (DEBUG_MODE) error_log("Error en TecnologiaModel::getAll: " . $e->getMessage());
            return [];
        }
    }
    
    public static function getById($id) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT * FROM tecnologias WHERE id_tecnologia = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public static function getByCategoria($categoria) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT * FROM tecnologias WHERE categoria_tecnologia = ? AND estado_tecnologia = 1 ORDER BY orden_tecnologia ASC");
            $stmt->execute([$categoria]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * @param array $data
     * @return bool
     */
    public static function create($data) {
        self::$lastError = null;
        try {
            $pdo = getDB();
            $nombre = mb_substr(trim((string) ($data['nombre_tecnologia'] ?? '')), 0, 100);
            $base = self::slugifyNombre($nombre);
            $slug = self::uniqueSlug($pdo, $base, null);

            $nivel = $data['nivel_tecnologia'] ?? 'intermedio';
            $allowedNivel = ['basico', 'intermedio', 'avanzado', 'experto'];
            if (!in_array($nivel, $allowedNivel, true)) {
                $nivel = 'intermedio';
            }

            $sql = 'INSERT INTO tecnologias (
                nombre_tecnologia, slug_tecnologia, categoria_tecnologia, logo_tecnologia, descripcion_tecnologia,
                nivel_tecnologia, orden_tecnologia, estado_tecnologia
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $nombre,
                $slug,
                $data['categoria_tecnologia'] ?? 'otro',
                self::nullableString($data['logo_tecnologia'] ?? null),
                self::nullableString($data['descripcion_tecnologia'] ?? null),
                $nivel,
                (int) ($data['orden_tecnologia'] ?? 0),
                !empty($data['estado_tecnologia']) ? 1 : 0,
            ]);
        } catch (PDOException $e) {
            self::$lastError = $e->getMessage();
            if (DEBUG_MODE) {
                error_log('Error en TecnologiaModel::create: ' . $e->getMessage());
            }
            return false;
        }
    }

    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function update($id, $data) {
        self::$lastError = null;
        try {
            $pdo = getDB();
            $nombre = mb_substr(trim((string) ($data['nombre_tecnologia'] ?? '')), 0, 100);
            $base = self::slugifyNombre($nombre);
            $slug = self::uniqueSlug($pdo, $base, (int) $id);

            $nivel = $data['nivel_tecnologia'] ?? 'intermedio';
            $allowedNivel = ['basico', 'intermedio', 'avanzado', 'experto'];
            if (!in_array($nivel, $allowedNivel, true)) {
                $nivel = 'intermedio';
            }

            $sql = 'UPDATE tecnologias SET
                nombre_tecnologia = ?, slug_tecnologia = ?, categoria_tecnologia = ?, logo_tecnologia = ?, descripcion_tecnologia = ?,
                nivel_tecnologia = ?, orden_tecnologia = ?, estado_tecnologia = ?
                WHERE id_tecnologia = ?';
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $nombre,
                $slug,
                $data['categoria_tecnologia'] ?? 'otro',
                self::nullableString($data['logo_tecnologia'] ?? null),
                self::nullableString($data['descripcion_tecnologia'] ?? null),
                $nivel,
                (int) ($data['orden_tecnologia'] ?? 0),
                !empty($data['estado_tecnologia']) ? 1 : 0,
                (int) $id,
            ]);
        } catch (PDOException $e) {
            self::$lastError = $e->getMessage();
            if (DEBUG_MODE) {
                error_log('Error en TecnologiaModel::update: ' . $e->getMessage());
            }
            return false;
        }
    }

    /**
     * @param int $id
     * @return bool
     */
    public static function delete($id) {
        self::$lastError = null;
        try {
            $pdo = getDB();
            $pdo->prepare('DELETE FROM tecnologias_proyectos WHERE tecnologia_id_tecnologia_proyecto = ?')
                ->execute([(int) $id]);
            $stmt = $pdo->prepare('DELETE FROM tecnologias WHERE id_tecnologia = ?');
            return $stmt->execute([(int) $id]);
        } catch (PDOException $e) {
            self::$lastError = $e->getMessage();
            if (DEBUG_MODE) {
                error_log('Error en TecnologiaModel::delete: ' . $e->getMessage());
            }
            return false;
        }
    }
}


