<?php
/**
 * Gestión de Blog
 * DevSoft Build SAC
 */

$page_title = 'Gestión de Blog';
$current_page = 'blog';
require_once BASE_PATH . '/app/model/Database.php';
$pdo = getDB();

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$message = '';
$message_type = '';

// Cargar artículo si estamos editando
$articulo = null;
if ($id && ($action === 'edit' || $action === 'view')) {
    $stmt = $pdo->prepare("SELECT * FROM articulos WHERE id_articulo = ?");
    $stmt->execute([$id]);
    $articulo = $stmt->fetch();
}

// Crear directorio de uploads si no existe
$upload_dir = PUBLIC_PATH . '/uploads/articulos/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Procesar subida de imagen
$imagen_subida = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagen_articulo_file']) && $_FILES['imagen_articulo_file']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['imagen_articulo_file'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    if (in_array($file['type'], $allowed_types) && $file['size'] <= $max_size) {
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'articulo_' . time() . '_' . uniqid() . '.' . $extension;
        $filepath = $upload_dir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            $_POST['imagen_articulo'] = URL_SERV . '/app/public_root/uploads/articulos/' . $filename;
            $imagen_subida = true;
        } else {
            $message = 'Error al subir la imagen';
            $message_type = 'error';
        }
    } else {
        $message = 'Formato de imagen no válido o archivo muy grande (máx 5MB)';
        $message_type = 'error';
    }
}

// Si no se subió imagen pero hay URL, usar la URL
if (!$imagen_subida && !empty($_POST['imagen_articulo']) && filter_var($_POST['imagen_articulo'], FILTER_VALIDATE_URL)) {
    // La URL ya está en $_POST['imagen_articulo'], no hacer nada
} elseif (!$imagen_subida && empty($_POST['imagen_articulo'])) {
    // Si estamos editando y no se subió nueva imagen ni hay URL, mantener la existente
    if (isset($articulo) && !empty($articulo['imagen_articulo'])) {
        $_POST['imagen_articulo'] = $articulo['imagen_articulo'];
    }
}

// Generar slug si no existe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['titulo_articulo'])) {
    if (empty($_POST['slug_articulo'])) {
        $slug = strtolower(trim($_POST['titulo_articulo']));
        $slug = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ñ'], ['a', 'e', 'i', 'o', 'u', 'n'], $slug);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        $_POST['slug_articulo'] = $slug;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'create') {
        $stmt = $pdo->prepare("INSERT INTO articulos (
            titulo_articulo, slug_articulo, resumen_articulo, contenido_articulo, 
            imagen_articulo, autor_articulo, estado_articulo, fecha_publicacion_articulo,
            meta_titulo_articulo, meta_descripcion_articulo, meta_keywords_articulo
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        if ($stmt->execute([
            $_POST['titulo_articulo'] ?? '',
            $_POST['slug_articulo'] ?? '',
            $_POST['resumen_articulo'] ?? '',
            $_POST['contenido_articulo'] ?? '',
            $_POST['imagen_articulo'] ?? null,
            $_POST['autor_articulo'] ?? 'Admin',
            isset($_POST['estado_articulo']) ? 1 : 0,
            !empty($_POST['fecha_publicacion_articulo']) ? $_POST['fecha_publicacion_articulo'] : date('Y-m-d H:i:s'),
            $_POST['meta_titulo_articulo'] ?? '',
            $_POST['meta_descripcion_articulo'] ?? '',
            $_POST['meta_keywords_articulo'] ?? ''
        ])) {
            $message = 'Artículo creado correctamente';
            $message_type = 'success';
            $action = 'list';
        } else {
            $message = 'Error al crear artículo';
            $message_type = 'error';
        }
    } elseif ($_POST['action'] === 'update') {
        $stmt = $pdo->prepare("UPDATE articulos SET 
            titulo_articulo = ?, slug_articulo = ?, resumen_articulo = ?, contenido_articulo = ?, 
            imagen_articulo = ?, autor_articulo = ?, estado_articulo = ?, fecha_publicacion_articulo = ?,
            meta_titulo_articulo = ?, meta_descripcion_articulo = ?, meta_keywords_articulo = ?
            WHERE id_articulo = ?");
        
        if ($stmt->execute([
            $_POST['titulo_articulo'] ?? '',
            $_POST['slug_articulo'] ?? '',
            $_POST['resumen_articulo'] ?? '',
            $_POST['contenido_articulo'] ?? '',
            $_POST['imagen_articulo'] ?? null,
            $_POST['autor_articulo'] ?? 'Admin',
            isset($_POST['estado_articulo']) ? 1 : 0,
            !empty($_POST['fecha_publicacion_articulo']) ? $_POST['fecha_publicacion_articulo'] : date('Y-m-d H:i:s'),
            $_POST['meta_titulo_articulo'] ?? '',
            $_POST['meta_descripcion_articulo'] ?? '',
            $_POST['meta_keywords_articulo'] ?? '',
            $id
        ])) {
            $message = 'Artículo actualizado correctamente';
            $message_type = 'success';
            $action = 'list';
        } else {
            $message = 'Error al actualizar artículo';
            $message_type = 'error';
        }
    } elseif ($_POST['action'] === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM articulos WHERE id_articulo = ?");
        if ($stmt->execute([$id])) {
            $message = 'Artículo eliminado correctamente';
            $message_type = 'success';
        } else {
            $message = 'Error al eliminar artículo';
            $message_type = 'error';
        }
        $action = 'list';
    }
}

// Obtener artículos
try {
    $sql = "SELECT * FROM articulos ORDER BY created_at DESC";
    $articulos = $pdo->query($sql)->fetchAll();
} catch (PDOException $e) {
    $articulos = [];
    if (DEBUG_MODE) {
        error_log("Error al obtener artículos: " . $e->getMessage());
    }
}

$content_file = BASE_PATH . '/app/view/backend/pages/blog_content.php';
include BASE_PATH . '/app/view/backend/includes/layout.php';
?>
