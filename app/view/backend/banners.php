<?php
/**
 * Gestión de Banners
 * DevSoft Build SAC
 */

$page_title = 'Gestión de Banners';
$current_page = 'banners';
require_once BASE_PATH . '/app/model/BannerModel.php';

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$message = '';
$message_type = '';

// Cargar banner si estamos editando
$banner = ($id && ($action === 'edit' || $action === 'view')) ? BannerModel::getById($id) : null;

// Crear directorio de uploads si no existe
$upload_dir = PUBLIC_PATH . '/uploads/banners/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Procesar subida de imagen
$imagen_subida = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagen_banner_file']) && $_FILES['imagen_banner_file']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['imagen_banner_file'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    if (in_array($file['type'], $allowed_types) && $file['size'] <= $max_size) {
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'banner_' . time() . '_' . uniqid() . '.' . $extension;
        $filepath = $upload_dir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            $_POST['imagen_banner'] = URL_SERV . '/app/public_root/uploads/banners/' . $filename;
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
if (!$imagen_subida && !empty($_POST['imagen_banner']) && filter_var($_POST['imagen_banner'], FILTER_VALIDATE_URL)) {
    // La URL ya está en $_POST['imagen_banner'], no hacer nada
} elseif (!$imagen_subida && empty($_POST['imagen_banner'])) {
    // Si estamos editando y no se subió nueva imagen ni hay URL, mantener la existente
    if (isset($banner) && !empty($banner['imagen_banner'])) {
        $_POST['imagen_banner'] = $banner['imagen_banner'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'create') {
        $data = [
            'titulo_banner' => $_POST['titulo_banner'] ?? '',
            'imagen_banner' => $_POST['imagen_banner'] ?? '',
            'enlace_banner' => $_POST['enlace_banner'] ?? null,
            'texto_boton_banner' => $_POST['texto_boton_banner'] ?? null,
            'posicion_banner' => $_POST['posicion_banner'] ?? 'home',
            'orden_banner' => $_POST['orden_banner'] ?? 0,
            'estado_banner' => isset($_POST['estado_banner']) ? 1 : 0,
            'fecha_inicio_banner' => !empty($_POST['fecha_inicio_banner']) ? $_POST['fecha_inicio_banner'] : null,
            'fecha_fin_banner' => !empty($_POST['fecha_fin_banner']) ? $_POST['fecha_fin_banner'] : null,
        ];
        
        if (BannerModel::create($data)) {
            $message = 'Banner creado correctamente';
            $message_type = 'success';
            $action = 'list';
        } else {
            $message = 'Error al crear banner';
            $message_type = 'error';
        }
    } elseif ($_POST['action'] === 'update') {
        $data = [
            'titulo_banner' => $_POST['titulo_banner'] ?? '',
            'imagen_banner' => $_POST['imagen_banner'] ?? '',
            'enlace_banner' => $_POST['enlace_banner'] ?? null,
            'texto_boton_banner' => $_POST['texto_boton_banner'] ?? null,
            'posicion_banner' => $_POST['posicion_banner'] ?? 'home',
            'orden_banner' => $_POST['orden_banner'] ?? 0,
            'estado_banner' => isset($_POST['estado_banner']) ? 1 : 0,
            'fecha_inicio_banner' => !empty($_POST['fecha_inicio_banner']) ? $_POST['fecha_inicio_banner'] : null,
            'fecha_fin_banner' => !empty($_POST['fecha_fin_banner']) ? $_POST['fecha_fin_banner'] : null,
        ];
        
        if (BannerModel::update($id, $data)) {
            $message = 'Banner actualizado correctamente';
            $message_type = 'success';
            $action = 'list';
        } else {
            $message = 'Error al actualizar banner';
            $message_type = 'error';
        }
    } elseif ($_POST['action'] === 'delete') {
        if (BannerModel::delete($id)) {
            $message = 'Banner eliminado correctamente';
            $message_type = 'success';
        }
        $action = 'list';
    }
}

$filters = [];
if (!isset($_GET['mostrar_inactivos'])) {
    $filters['estado_banner'] = 1;
}
$banners = BannerModel::getAll($filters);

$content_file = BASE_PATH . '/app/view/backend/pages/banners_content.php';
include BASE_PATH . '/app/view/backend/includes/layout.php';
?>
