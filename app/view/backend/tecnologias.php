<?php
/**
 * Gestión de Tecnologías
 * DevSoft Build SAC
 */

$page_title = 'Gestión de Tecnologías';
$current_page = 'tecnologias';
require_once BASE_PATH . '/app/model/TecnologiaModel.php';

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$message = '';
$message_type = '';

// Cargar tecnología si estamos editando
$tecnologia = ($id && ($action === 'edit' || $action === 'view')) ? TecnologiaModel::getById($id) : null;

// Crear directorio de uploads si no existe
$upload_dir = PUBLIC_PATH . '/uploads/tecnologias/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Procesar subida de imagen
$imagen_subida = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['logo_tecnologia_file']) && $_FILES['logo_tecnologia_file']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['logo_tecnologia_file'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
    $max_size = 2 * 1024 * 1024; // 2MB
    
    if (in_array($file['type'], $allowed_types) && $file['size'] <= $max_size) {
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'tecnologia_' . time() . '_' . uniqid() . '.' . $extension;
        $filepath = $upload_dir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            $_POST['logo_tecnologia'] = URL_SERV . '/app/public_root/uploads/tecnologias/' . $filename;
            $imagen_subida = true;
        } else {
            $message = 'Error al subir el logo';
            $message_type = 'error';
        }
    } else {
        $message = 'Formato de imagen no válido o archivo muy grande (máx 2MB)';
        $message_type = 'error';
    }
}

// Si no se subió imagen pero hay URL, usar la URL
if (!$imagen_subida && !empty($_POST['logo_tecnologia']) && filter_var($_POST['logo_tecnologia'], FILTER_VALIDATE_URL)) {
    // La URL ya está en $_POST['logo_tecnologia'], no hacer nada
} elseif (!$imagen_subida && empty($_POST['logo_tecnologia'])) {
    // Si estamos editando y no se subió nueva imagen ni hay URL, mantener la existente
    if (isset($tecnologia) && !empty($tecnologia['logo_tecnologia'])) {
        $_POST['logo_tecnologia'] = $tecnologia['logo_tecnologia'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'create') {
        $data = [
            'nombre_tecnologia' => $_POST['nombre_tecnologia'] ?? '',
            'categoria_tecnologia' => $_POST['categoria_tecnologia'] ?? '',
            'logo_tecnologia' => $_POST['logo_tecnologia'] ?? '',
            'descripcion_tecnologia' => $_POST['descripcion_tecnologia'] ?? '',
            'url_tecnologia' => $_POST['url_tecnologia'] ?? null,
            'orden_tecnologia' => $_POST['orden_tecnologia'] ?? 0,
            'estado_tecnologia' => isset($_POST['estado_tecnologia']) ? 1 : 0,
        ];
        
        if (TecnologiaModel::create($data)) {
            $message = 'Tecnología creada correctamente';
            $message_type = 'success';
            $action = 'list';
        } else {
            $message = 'Error al crear tecnología';
            $message_type = 'error';
        }
    } elseif ($_POST['action'] === 'update') {
        $data = [
            'nombre_tecnologia' => $_POST['nombre_tecnologia'] ?? '',
            'categoria_tecnologia' => $_POST['categoria_tecnologia'] ?? '',
            'logo_tecnologia' => $_POST['logo_tecnologia'] ?? '',
            'descripcion_tecnologia' => $_POST['descripcion_tecnologia'] ?? '',
            'url_tecnologia' => $_POST['url_tecnologia'] ?? null,
            'orden_tecnologia' => $_POST['orden_tecnologia'] ?? 0,
            'estado_tecnologia' => isset($_POST['estado_tecnologia']) ? 1 : 0,
        ];
        
        if (TecnologiaModel::update($id, $data)) {
            $message = 'Tecnología actualizada correctamente';
            $message_type = 'success';
            $action = 'list';
        } else {
            $message = 'Error al actualizar tecnología';
            $message_type = 'error';
        }
    } elseif ($_POST['action'] === 'delete') {
        if (TecnologiaModel::delete($id)) {
            $message = 'Tecnología eliminada correctamente';
            $message_type = 'success';
        }
        $action = 'list';
    }
}

$filters = ['buscar' => $_GET['buscar'] ?? ''];
if (!isset($_GET['mostrar_inactivos'])) {
    $filters['estado_tecnologia'] = 1;
}
$tecnologias = TecnologiaModel::getAll($filters);

$content_file = BASE_PATH . '/app/view/backend/pages/tecnologias_content.php';
include BASE_PATH . '/app/view/backend/includes/layout.php';
?>


