<?php
$page_title = 'Galería de Trabajos';
$current_page = 'galeria';
require_once BASE_PATH . '/app/model/GaleriaModel.php';
require_once BASE_PATH . '/app/model/Database.php';
$pdo = getDB();
$servicios = $pdo->query("SELECT id, nombre FROM servicios WHERE activo = 1 ORDER BY nombre")->fetchAll();

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$message = '';
$message_type = '';

// Cargar trabajo si estamos editando (necesario antes de procesar POST)
$trabajo = ($id && ($action === 'edit' || $action === 'view')) ? GaleriaModel::getById($id) : null;

// Crear directorio de uploads si no existe
$upload_dir = PUBLIC_PATH . '/uploads/galeria/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Procesar subida de imágenes (antes de procesar el POST)
$imagen_antes_subida = false;
$imagen_despues_subida = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar imagen "antes"
    if (isset($_FILES['imagen_antes_file']) && $_FILES['imagen_antes_file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['imagen_antes_file'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if (in_array($file['type'], $allowed_types) && $file['size'] <= $max_size) {
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'antes_' . time() . '_' . uniqid() . '.' . $extension;
            $filepath = $upload_dir . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                $_POST['imagen_antes'] = URL_SERV . '/app/public_root/uploads/galeria/' . $filename;
                $imagen_antes_subida = true;
            }
        }
    }
    
    // Procesar imagen "después"
    if (isset($_FILES['imagen_despues_file']) && $_FILES['imagen_despues_file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['imagen_despues_file'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if (in_array($file['type'], $allowed_types) && $file['size'] <= $max_size) {
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'despues_' . time() . '_' . uniqid() . '.' . $extension;
            $filepath = $upload_dir . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                $_POST['imagen_despues'] = URL_SERV . '/app/public_root/uploads/galeria/' . $filename;
                $imagen_despues_subida = true;
            }
        }
    }
    
    // Si no se subieron imágenes pero hay URLs, usar las URLs
    if (!$imagen_antes_subida && !empty($_POST['imagen_antes']) && filter_var($_POST['imagen_antes'], FILTER_VALIDATE_URL)) {
        // La URL ya está en $_POST['imagen_antes'], no hacer nada
    } elseif (!$imagen_antes_subida && empty($_POST['imagen_antes'])) {
        // Si estamos editando y no se subió nueva imagen ni hay URL, mantener la existente
        if (isset($trabajo) && !empty($trabajo['imagen_antes'])) {
            $_POST['imagen_antes'] = $trabajo['imagen_antes'];
        }
    }
    
    if (!$imagen_despues_subida && !empty($_POST['imagen_despues']) && filter_var($_POST['imagen_despues'], FILTER_VALIDATE_URL)) {
        // La URL ya está en $_POST['imagen_despues'], no hacer nada
    } elseif (!$imagen_despues_subida && empty($_POST['imagen_despues'])) {
        // Si estamos editando y no se subió nueva imagen ni hay URL, mantener la existente
        if (isset($trabajo) && !empty($trabajo['imagen_despues'])) {
            $_POST['imagen_despues'] = $trabajo['imagen_despues'];
        }
    }
    
    // Determinar tipo basado en las imágenes
    if (!empty($_POST['imagen_antes']) && !empty($_POST['imagen_despues'])) {
        $_POST['tipo'] = 'antes_despues';
    } elseif (!empty($_POST['imagen_despues'])) {
        $_POST['tipo'] = 'solo_despues';
    } elseif (!empty($_POST['imagen_antes'])) {
        $_POST['tipo'] = 'solo_antes';
    } else {
        $_POST['tipo'] = 'solo_despues';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                if (GaleriaModel::create($_POST)) {
                    $message = 'Trabajo agregado correctamente';
                    $message_type = 'success';
                    $action = 'list';
                } else {
                    $message = 'Error al agregar trabajo';
                    $message_type = 'error';
                }
                break;
                
            case 'update':
                if (GaleriaModel::update($_POST['id'], $_POST)) {
                    $message = 'Trabajo actualizado correctamente';
                    $message_type = 'success';
                    $action = 'list';
                } else {
                    $message = 'Error al actualizar trabajo';
                    $message_type = 'error';
                }
                break;
                
            case 'delete':
                if (GaleriaModel::delete($_POST['id'])) {
                    $message = 'Trabajo eliminado correctamente';
                    $message_type = 'success';
                }
                $action = 'list';
                break;
        }
    }
}

$galeria = GaleriaModel::getAll(['activo' => 1]);

$content_file = BASE_PATH . '/app/view/backend/pages/galeria_content.php';
include BASE_PATH . '/app/view/backend/includes/layout.php';
?>

