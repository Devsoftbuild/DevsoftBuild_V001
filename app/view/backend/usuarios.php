<?php
/**
 * Gestión de Usuarios
 * DevSoft Build SAC
 */

$page_title = 'Gestión de Usuarios';
$current_page = 'usuarios';
require_once BASE_PATH . '/app/model/Database.php';

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$message = '';
$message_type = '';

$pdo = getDB();

// Cargar usuario si estamos editando
$usuario = null;
if ($id && ($action === 'edit' || $action === 'view')) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$id]);
    $usuario = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'create') {
        $password_hash = password_hash($_POST['password_usuario'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre_usuario, email_usuario, password_usuario, rol_usuario, estado_usuario) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$_POST['nombre_usuario'], $_POST['email_usuario'], $password_hash, $_POST['rol_usuario'], isset($_POST['estado_usuario']) ? 1 : 0])) {
            $message = 'Usuario creado correctamente';
            $message_type = 'success';
            $action = 'list';
        } else {
            $message = 'Error al crear usuario';
            $message_type = 'error';
        }
    } elseif ($_POST['action'] === 'update') {
        $data = [$_POST['nombre_usuario'], $_POST['email_usuario'], $_POST['rol_usuario'], isset($_POST['estado_usuario']) ? 1 : 0, $id];
        if (!empty($_POST['password_usuario'])) {
            $password_hash = password_hash($_POST['password_usuario'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE usuarios SET nombre_usuario = ?, email_usuario = ?, password_usuario = ?, rol_usuario = ?, estado_usuario = ? WHERE id_usuario = ?");
            $data[] = $id;
            $data[2] = $password_hash;
        } else {
            $stmt = $pdo->prepare("UPDATE usuarios SET nombre_usuario = ?, email_usuario = ?, rol_usuario = ?, estado_usuario = ? WHERE id_usuario = ?");
        }
        if ($stmt->execute($data)) {
            $message = 'Usuario actualizado correctamente';
            $message_type = 'success';
            $action = 'list';
        } else {
            $message = 'Error al actualizar usuario';
            $message_type = 'error';
        }
    } elseif ($_POST['action'] === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
        if ($stmt->execute([$id])) {
            $message = 'Usuario eliminado correctamente';
            $message_type = 'success';
        }
        $action = 'list';
    }
}

$usuarios = $pdo->query("SELECT * FROM usuarios ORDER BY nombre_usuario")->fetchAll();

$content_file = BASE_PATH . '/app/view/backend/pages/usuarios_content.php';
include BASE_PATH . '/app/view/backend/includes/layout.php';
?>


