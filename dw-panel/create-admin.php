<?php
/**
 * Script para crear usuario administrador inicial
 * DevSoft Build SAC
 * 
 * Ejecutar una vez: http://localhost/devsoftbuild/dw-panel/create-admin.php
 * Luego eliminar este archivo por seguridad
 */

// Cargar core del sistema
require_once __DIR__ . '/../core.php';

// Cargar modelo de autenticación
require_once BASE_PATH . '/app/model/AuthModel.php';

// Credenciales por defecto
$email = 'admin@devsoftbuild.com';
$password = 'admin123';
$nombre = 'Administrador';

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Crear Usuario Administrador</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0a1a1f 0%, #1a3a47 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        h1 {
            color: #0a1a1f;
            margin-bottom: 20px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border: 1px solid #f5c6cb;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border: 1px solid #bee5eb;
        }
        pre {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            overflow-x: auto;
        }
        .credentials {
            background: #fff3cd;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 2px solid #ffc107;
        }
        .credentials h3 {
            margin-top: 0;
            color: #856404;
        }
        .credentials p {
            margin: 10px 0;
            font-size: 1.1rem;
        }
        .credentials strong {
            color: #0a1a1f;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔐 Crear Usuario Administrador</h1>";

try {
    $pdo = getDB();
    
    // Verificar si el usuario ya existe
    $stmt = $pdo->prepare("SELECT id_usuario, nombre_usuario, email_usuario, estado_usuario FROM usuarios WHERE email_usuario = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "<div class='info'>
            <strong>✓ Usuario ya existe:</strong><br>
            ID: {$user['id_usuario']}<br>
            Nombre: {$user['nombre_usuario']}<br>
            Email: {$user['email_usuario']}<br>
            Estado: " . ($user['estado_usuario'] == 1 ? 'Activo' : 'Inactivo') . "
        </div>";
        
        // Actualizar contraseña
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $update_stmt = $pdo->prepare("UPDATE usuarios SET password_usuario = ?, estado_usuario = 1 WHERE email_usuario = ?");
        $update_stmt->execute([$password_hash, $email]);
        
        echo "<div class='success'>
            <strong>✓ Contraseña actualizada correctamente</strong>
        </div>";
    } else {
        // Crear nuevo usuario
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $insert_stmt = $pdo->prepare("INSERT INTO usuarios (nombre_usuario, email_usuario, password_usuario, rol_usuario, estado_usuario) VALUES (?, ?, ?, ?, ?)");
        $insert_stmt->execute([$nombre, $email, $password_hash, 'admin', 1]);
        
        echo "<div class='success'>
            <strong>✓ Usuario creado correctamente</strong>
        </div>";
    }
    
    echo "<div class='credentials'>
        <h3>📋 Credenciales de Acceso</h3>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Contraseña:</strong> {$password}</p>
        <p><strong>URL de acceso:</strong> <a href='" . URL_SERV . "/dw-panel/login.php' target='_blank'>" . URL_SERV . "/dw-panel/login.php</a></p>
    </div>";
    
    echo "<div class='info'>
        <strong>⚠️ IMPORTANTE:</strong><br>
        Por seguridad, elimina este archivo (create-admin.php) después de crear el usuario.
    </div>";
    
} catch (PDOException $e) {
    echo "<div class='error'>
        <strong>✗ Error:</strong><br>
        " . htmlspecialchars($e->getMessage()) . "
    </div>";
    
    if (DEBUG_MODE) {
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
}

echo "</div></body></html>";
?>


