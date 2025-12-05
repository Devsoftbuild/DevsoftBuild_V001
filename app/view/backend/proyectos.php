<?php
/**
 * Gestión de Proyectos
 * DevSoft Build SAC
 */

$page_title = 'Gestión de Proyectos';
$current_page = 'proyectos';
require_once BASE_PATH . '/app/model/ProyectoModel.php';
require_once BASE_PATH . '/app/model/ServicioModel.php';
require_once BASE_PATH . '/app/model/TecnologiaModel.php';

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$message = '';
$message_type = '';

$servicios = ServicioModel::getAll();
$tecnologias = TecnologiaModel::getAll();

// Cargar proyecto si estamos editando
$proyecto = ($id && ($action === 'edit' || $action === 'view')) ? ProyectoModel::getById($id) : null;

// Crear directorio de uploads si no existe
$upload_dir = PUBLIC_PATH . '/uploads/proyectos/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Procesar subida de imagen
$imagen_subida = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagen_proyecto_file']) && $_FILES['imagen_proyecto_file']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['imagen_proyecto_file'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    if (in_array($file['type'], $allowed_types) && $file['size'] <= $max_size) {
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'proyecto_' . time() . '_' . uniqid() . '.' . $extension;
        $filepath = $upload_dir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            $_POST['imagen_proyecto'] = URL_SERV . '/app/public_root/uploads/proyectos/' . $filename;
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
if (!$imagen_subida && !empty($_POST['imagen_proyecto']) && filter_var($_POST['imagen_proyecto'], FILTER_VALIDATE_URL)) {
    // La URL ya está en $_POST['imagen_proyecto'], no hacer nada
} elseif (!$imagen_subida && empty($_POST['imagen_proyecto'])) {
    // Si estamos editando y no se subió nueva imagen ni hay URL, mantener la existente
    if (isset($proyecto) && !empty($proyecto['imagen_proyecto'])) {
        $_POST['imagen_proyecto'] = $proyecto['imagen_proyecto'];
    }
}

// Generar slug si no existe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nombre_proyecto'])) {
    if (empty($_POST['slug_proyecto'])) {
        $slug = strtolower(trim($_POST['nombre_proyecto']));
        $slug = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ñ'], ['a', 'e', 'i', 'o', 'u', 'n'], $slug);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        $_POST['slug_proyecto'] = $slug;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'create') {
        $data = [
            'nombre_proyecto' => $_POST['nombre_proyecto'] ?? '',
            'slug_proyecto' => $_POST['slug_proyecto'] ?? '',
            'descripcion_corta_proyecto' => $_POST['descripcion_corta_proyecto'] ?? '',
            'descripcion_larga_proyecto' => $_POST['descripcion_larga_proyecto'] ?? '',
            'imagen_proyecto' => $_POST['imagen_proyecto'] ?? '',
            'tipo_proyecto' => $_POST['tipo_proyecto'] ?? '',
            'cliente_proyecto' => $_POST['cliente_proyecto'] ?? '',
            'fecha_inicio_proyecto' => !empty($_POST['fecha_inicio_proyecto']) ? $_POST['fecha_inicio_proyecto'] : null,
            'fecha_fin_proyecto' => !empty($_POST['fecha_fin_proyecto']) ? $_POST['fecha_fin_proyecto'] : null,
            'url_proyecto' => $_POST['url_proyecto'] ?? null,
            'estado_proyecto' => $_POST['estado_proyecto'] ?? 'en_proceso',
            'destacado_proyecto' => isset($_POST['destacado_proyecto']) ? 1 : 0,
            'orden_proyecto' => $_POST['orden_proyecto'] ?? 0,
            'meta_titulo_proyecto' => $_POST['meta_titulo_proyecto'] ?? '',
            'meta_descripcion_proyecto' => $_POST['meta_descripcion_proyecto'] ?? '',
            'meta_keywords_proyecto' => $_POST['meta_keywords_proyecto'] ?? '',
        ];
        
        if (ProyectoModel::create($data)) {
            $message = 'Proyecto creado correctamente';
            $message_type = 'success';
            $action = 'list';
        } else {
            $message = 'Error al crear proyecto';
            $message_type = 'error';
        }
    } elseif ($_POST['action'] === 'update') {
        $data = [
            'nombre_proyecto' => $_POST['nombre_proyecto'] ?? '',
            'slug_proyecto' => $_POST['slug_proyecto'] ?? '',
            'descripcion_corta_proyecto' => $_POST['descripcion_corta_proyecto'] ?? '',
            'descripcion_larga_proyecto' => $_POST['descripcion_larga_proyecto'] ?? '',
            'imagen_proyecto' => $_POST['imagen_proyecto'] ?? '',
            'tipo_proyecto' => $_POST['tipo_proyecto'] ?? '',
            'cliente_proyecto' => $_POST['cliente_proyecto'] ?? '',
            'fecha_inicio_proyecto' => !empty($_POST['fecha_inicio_proyecto']) ? $_POST['fecha_inicio_proyecto'] : null,
            'fecha_fin_proyecto' => !empty($_POST['fecha_fin_proyecto']) ? $_POST['fecha_fin_proyecto'] : null,
            'url_proyecto' => $_POST['url_proyecto'] ?? null,
            'estado_proyecto' => $_POST['estado_proyecto'] ?? 'en_proceso',
            'destacado_proyecto' => isset($_POST['destacado_proyecto']) ? 1 : 0,
            'orden_proyecto' => $_POST['orden_proyecto'] ?? 0,
            'meta_titulo_proyecto' => $_POST['meta_titulo_proyecto'] ?? '',
            'meta_descripcion_proyecto' => $_POST['meta_descripcion_proyecto'] ?? '',
            'meta_keywords_proyecto' => $_POST['meta_keywords_proyecto'] ?? '',
        ];
        
        if (ProyectoModel::update($id, $data)) {
            $message = 'Proyecto actualizado correctamente';
            $message_type = 'success';
            $action = 'list';
        } else {
            $message = 'Error al actualizar proyecto';
            $message_type = 'error';
        }
    } elseif ($_POST['action'] === 'delete') {
        if (ProyectoModel::delete($id)) {
            $message = 'Proyecto eliminado correctamente';
            $message_type = 'success';
        }
        $action = 'list';
    }
}

$filters = ['buscar' => $_GET['buscar'] ?? ''];
if (!empty($_GET['estado'])) {
    $filters['estado_proyecto'] = $_GET['estado'];
}
$proyectos = ProyectoModel::getAll($filters);

$content_file = BASE_PATH . '/app/view/backend/pages/proyectos_content.php';
include BASE_PATH . '/app/view/backend/includes/layout.php';
?>

