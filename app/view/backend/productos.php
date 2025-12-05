<?php
$page_title = 'Gestión de Productos';
$current_page = 'productos';
require_once BASE_PATH . '/app/model/ProductoModel.php';
$pdo = getDB();
$categorias = $pdo->query("SELECT * FROM categorias_productos WHERE activo = 1 ORDER BY nombre")->fetchAll();

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'create') {
        if (ProductoModel::create($_POST)) {
            $message = 'Producto creado correctamente';
            $message_type = 'success';
            $action = 'list';
        } else {
            $message = 'Error al crear producto';
            $message_type = 'error';
        }
    } elseif ($_POST['action'] === 'update') {
        if (ProductoModel::update($_POST['id'], $_POST)) {
            $message = 'Producto actualizado correctamente';
            $message_type = 'success';
            $action = 'list';
        } else {
            $message = 'Error al actualizar producto';
            $message_type = 'error';
        }
    } elseif ($_POST['action'] === 'delete') {
        if (ProductoModel::delete($_POST['id'])) {
            $message = 'Producto eliminado correctamente';
            $message_type = 'success';
        }
        $action = 'list';
    } elseif ($_POST['action'] === 'update_stock') {
        if (ProductoModel::updateStock($_POST['id'], $_POST['cantidad'], $_POST['tipo'])) {
            $message = 'Stock actualizado correctamente';
            $message_type = 'success';
        } else {
            $message = 'Error al actualizar stock';
            $message_type = 'error';
        }
        $action = 'list';
    }
}

$filters = ['buscar' => $_GET['buscar'] ?? '', 'stock_bajo' => isset($_GET['stock_bajo'])];
$productos = ProductoModel::getAll($filters);
$producto = ($id && ($action === 'edit' || $action === 'view')) ? ProductoModel::getById($id) : null;

$content_file = BASE_PATH . '/app/view/backend/pages/productos_content.php';
include BASE_PATH . '/app/view/backend/includes/layout.php';
?>

