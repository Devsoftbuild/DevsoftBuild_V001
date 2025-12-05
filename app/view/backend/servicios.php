<?php
/**
 * Gestión de Servicios
 * DevSoft Build SAC
 */

$page_title = 'Gestión de Servicios';
$current_page = 'servicios';
require_once BASE_PATH . '/app/model/ServicioModel.php';

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$message = '';
$message_type = '';

// Cargar servicio si estamos editando
$servicio = ($id && ($action === 'edit' || $action === 'view')) ? ServicioModel::getById($id) : null;

// Crear directorio de uploads si no existe
$upload_dir = PUBLIC_PATH . '/uploads/servicios/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Procesar subida de imagen
$imagen_subida = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagen_servicio_file']) && $_FILES['imagen_servicio_file']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['imagen_servicio_file'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    if (in_array($file['type'], $allowed_types) && $file['size'] <= $max_size) {
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'servicio_' . time() . '_' . uniqid() . '.' . $extension;
        $filepath = $upload_dir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            $_POST['imagen_servicio'] = URL_SERV . '/app/public_root/uploads/servicios/' . $filename;
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
if (!$imagen_subida && !empty($_POST['imagen_servicio']) && filter_var($_POST['imagen_servicio'], FILTER_VALIDATE_URL)) {
    // La URL ya está en $_POST['imagen_servicio'], no hacer nada
} elseif (!$imagen_subida && empty($_POST['imagen_servicio'])) {
    // Si estamos editando y no se subió nueva imagen ni hay URL, mantener la existente
    if (isset($servicio) && !empty($servicio['imagen_servicio'])) {
        $_POST['imagen_servicio'] = $servicio['imagen_servicio'];
    }
}

// Generar slug si no existe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nombre_servicio'])) {
    if (empty($_POST['slug_servicio'])) {
        $slug = strtolower(trim($_POST['nombre_servicio']));
        $slug = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ñ'], ['a', 'e', 'i', 'o', 'u', 'n'], $slug);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        $_POST['slug_servicio'] = $slug;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'create') {
        $data = [
            'nombre_servicio' => $_POST['nombre_servicio'] ?? '',
            'slug_servicio' => $_POST['slug_servicio'] ?? '',
            'descripcion_corta_servicio' => $_POST['descripcion_corta_servicio'] ?? '',
            'descripcion_larga_servicio' => $_POST['descripcion_larga_servicio'] ?? '',
            'icono_servicio' => $_POST['icono_servicio'] ?? '',
            'imagen_servicio' => $_POST['imagen_servicio'] ?? '',
            'precio_desde_servicio' => !empty($_POST['precio_desde_servicio']) ? $_POST['precio_desde_servicio'] : null,
            'orden_servicio' => $_POST['orden_servicio'] ?? 0,
            'destacado_servicio' => isset($_POST['destacado_servicio']) ? 1 : 0,
            'estado_servicio' => isset($_POST['estado_servicio']) ? 1 : 0,
            'meta_titulo_servicio' => $_POST['meta_titulo_servicio'] ?? '',
            'meta_descripcion_servicio' => $_POST['meta_descripcion_servicio'] ?? '',
            'meta_keywords_servicio' => $_POST['meta_keywords_servicio'] ?? '',
        ];
        
        if (ServicioModel::create($data)) {
            $message = 'Servicio creado correctamente';
            $message_type = 'success';
            $action = 'list';
        } else {
            $message = 'Error al crear servicio';
            $message_type = 'error';
        }
    } elseif ($_POST['action'] === 'update') {
        $data = [
            'nombre_servicio' => $_POST['nombre_servicio'] ?? '',
            'slug_servicio' => $_POST['slug_servicio'] ?? '',
            'descripcion_corta_servicio' => $_POST['descripcion_corta_servicio'] ?? '',
            'descripcion_larga_servicio' => $_POST['descripcion_larga_servicio'] ?? '',
            'icono_servicio' => $_POST['icono_servicio'] ?? '',
            'imagen_servicio' => $_POST['imagen_servicio'] ?? '',
            'precio_desde_servicio' => !empty($_POST['precio_desde_servicio']) ? $_POST['precio_desde_servicio'] : null,
            'orden_servicio' => $_POST['orden_servicio'] ?? 0,
            'destacado_servicio' => isset($_POST['destacado_servicio']) ? 1 : 0,
            'estado_servicio' => isset($_POST['estado_servicio']) ? 1 : 0,
            'meta_titulo_servicio' => $_POST['meta_titulo_servicio'] ?? '',
            'meta_descripcion_servicio' => $_POST['meta_descripcion_servicio'] ?? '',
            'meta_keywords_servicio' => $_POST['meta_keywords_servicio'] ?? '',
        ];
        
        if (ServicioModel::update($id, $data)) {
            $message = 'Servicio actualizado correctamente';
            $message_type = 'success';
            $action = 'list';
        } else {
            $message = 'Error al actualizar servicio';
            $message_type = 'error';
        }
    } elseif ($_POST['action'] === 'delete') {
        if (ServicioModel::delete($id)) {
            $message = 'Servicio eliminado correctamente';
            $message_type = 'success';
        }
        $action = 'list';
    }
}

$filters = ['buscar' => $_GET['buscar'] ?? ''];
if (!isset($_GET['mostrar_inactivos'])) {
    $filters['estado_servicio'] = 1;
}
$servicios = ServicioModel::getAll($filters);

$content_file = BASE_PATH . '/app/view/backend/pages/servicios_content.php';
include BASE_PATH . '/app/view/backend/includes/layout.php';
?>
