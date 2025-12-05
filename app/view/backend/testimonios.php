<?php
/**
 * Gestión de Testimonios
 * DevSoft Build SAC
 */

$page_title = 'Gestión de Testimonios';
$current_page = 'testimonios';
require_once BASE_PATH . '/app/model/TestimonioModel.php';

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$message = '';
$message_type = '';

// Cargar testimonio si estamos editando
$testimonio = ($id && ($action === 'edit' || $action === 'view')) ? TestimonioModel::getById($id) : null;

// Crear directorio de uploads si no existe
$upload_dir = PUBLIC_PATH . '/uploads/testimonios/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Procesar subida de imagen
$imagen_subida = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto_testimonio_file']) && $_FILES['foto_testimonio_file']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['foto_testimonio_file'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 2 * 1024 * 1024; // 2MB
    
    if (in_array($file['type'], $allowed_types) && $file['size'] <= $max_size) {
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'testimonio_' . time() . '_' . uniqid() . '.' . $extension;
        $filepath = $upload_dir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            $_POST['foto_testimonio'] = URL_SERV . '/app/public_root/uploads/testimonios/' . $filename;
            $imagen_subida = true;
        } else {
            $message = 'Error al subir la foto';
            $message_type = 'error';
        }
    } else {
        $message = 'Formato de imagen no válido o archivo muy grande (máx 2MB)';
        $message_type = 'error';
    }
}

// Si no se subió imagen pero hay URL, usar la URL
if (!$imagen_subida && !empty($_POST['foto_testimonio']) && filter_var($_POST['foto_testimonio'], FILTER_VALIDATE_URL)) {
    // La URL ya está en $_POST['foto_testimonio'], no hacer nada
} elseif (!$imagen_subida && empty($_POST['foto_testimonio'])) {
    // Si estamos editando y no se subió nueva imagen ni hay URL, mantener la existente
    if (isset($testimonio) && !empty($testimonio['foto_testimonio'])) {
        $_POST['foto_testimonio'] = $testimonio['foto_testimonio'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'create') {
        $data = [
            'nombre_testimonio' => $_POST['nombre_testimonio'] ?? '',
            'cargo_testimonio' => $_POST['cargo_testimonio'] ?? '',
            'empresa_testimonio' => $_POST['empresa_testimonio'] ?? '',
            'foto_testimonio' => $_POST['foto_testimonio'] ?? '',
            'testimonio_texto' => $_POST['testimonio_texto'] ?? '',
            'calificacion_testimonio' => $_POST['calificacion_testimonio'] ?? 5,
            'orden_testimonio' => $_POST['orden_testimonio'] ?? 0,
            'estado_testimonio' => isset($_POST['estado_testimonio']) ? 1 : 0,
        ];
        
        if (TestimonioModel::create($data)) {
            $message = 'Testimonio creado correctamente';
            $message_type = 'success';
            $action = 'list';
        } else {
            $message = 'Error al crear testimonio';
            $message_type = 'error';
        }
    } elseif ($_POST['action'] === 'update') {
        $data = [
            'nombre_testimonio' => $_POST['nombre_testimonio'] ?? '',
            'cargo_testimonio' => $_POST['cargo_testimonio'] ?? '',
            'empresa_testimonio' => $_POST['empresa_testimonio'] ?? '',
            'foto_testimonio' => $_POST['foto_testimonio'] ?? '',
            'testimonio_texto' => $_POST['testimonio_texto'] ?? '',
            'calificacion_testimonio' => $_POST['calificacion_testimonio'] ?? 5,
            'orden_testimonio' => $_POST['orden_testimonio'] ?? 0,
            'estado_testimonio' => isset($_POST['estado_testimonio']) ? 1 : 0,
        ];
        
        if (TestimonioModel::update($id, $data)) {
            $message = 'Testimonio actualizado correctamente';
            $message_type = 'success';
            $action = 'list';
        } else {
            $message = 'Error al actualizar testimonio';
            $message_type = 'error';
        }
    } elseif ($_POST['action'] === 'delete') {
        if (TestimonioModel::delete($id)) {
            $message = 'Testimonio eliminado correctamente';
            $message_type = 'success';
        }
        $action = 'list';
    }
}

$filters = ['buscar' => $_GET['buscar'] ?? ''];
if (!isset($_GET['mostrar_inactivos'])) {
    $filters['estado_testimonio'] = 1;
}
$testimonios = TestimonioModel::getAll($filters);

$content_file = BASE_PATH . '/app/view/backend/pages/testimonios_content.php';
include BASE_PATH . '/app/view/backend/includes/layout.php';
?>

