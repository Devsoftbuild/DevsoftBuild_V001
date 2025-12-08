<?php
/**
 * Script para corregir la contraseña del usuario administrador
 * DevSoft Build SAC
 * 
 * Ejecutar: http://localhost/devsoftbuild/dw-panel/fix-admin-password.php
 * Luego eliminar este archivo por seguridad
 */

// Cargar core del sistema
require_once __DIR__ . '/../core.php';

// Cargar modelo de autenticación
require_once BASE_PATH . '/app/model/AuthModel.php';

// Credenciales
$email = 'admin@devsoftbuild.com';
$password = 'admin123';

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Corregir Contraseña Admin</title>
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
            font-size: 0.9rem;
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
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #00d4ff, #1dd1e8);
            color: #0a1a1f;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔧 Corregir Contraseña de Administrador</h1>";

try {
    $pdo = getDB();
    
    echo "<div class='info'>
        <strong>Buscando usuario:</strong> {$email}
    </div>";
    
    // Verificar si el usuario existe
    $stmt = $pdo->prepare("SELECT id_usuario, nombre_usuario, email_usuario, password_usuario, rol_usuario, estado_usuario FROM usuarios WHERE email_usuario = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo "<div class='error'>
            <strong>✗ Usuario no encontrado.</strong><br>
            Creando nuevo usuario administrador...
        </div>";
        
        // Crear usuario
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $insert_stmt = $pdo->prepare("INSERT INTO usuarios (nombre_usuario, email_usuario, password_usuario, rol_usuario, estado_usuario) VALUES (?, ?, ?, ?, ?)");
        $insert_stmt->execute(['Administrador', $email, $password_hash, 'admin', 1]);
        
        echo "<div class='success'>
            <strong>✓ Usuario creado correctamente</strong>
        </div>";
    } else {
        echo "<div class='info'>
            <strong>✓ Usuario encontrado:</strong><br>
            ID: {$user['id_usuario']}<br>
            Nombre: {$user['nombre_usuario']}<br>
            Email: {$user['email_usuario']}<br>
            Rol: {$user['rol_usuario']}<br>
            Estado: " . ($user['estado_usuario'] == 1 ? 'Activo' : '<strong style=\"color: red;\">INACTIVO</strong>') . "<br>
            Hash actual: " . substr($user['password_usuario'], 0, 30) . "...<br>
            Longitud hash: " . strlen($user['password_usuario']) . " caracteres
        </div>";
        
        // Verificar si la contraseña actual funciona
        $password_works = password_verify($password, $user['password_usuario']);
        
        if ($password_works) {
            echo "<div class='info'>
                <strong>✓ La contraseña actual ya funciona correctamente</strong>
            </div>";
        } else {
            echo "<div class='error'>
                <strong>✗ La contraseña actual NO funciona</strong><br>
                Actualizando contraseña...
            </div>";
        }
        
        // Actualizar contraseña y activar usuario
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $update_stmt = $pdo->prepare("UPDATE usuarios SET password_usuario = ?, estado_usuario = 1 WHERE email_usuario = ?");
        $update_stmt->execute([$password_hash, $email]);
        
        echo "<div class='success'>
            <strong>✓ Contraseña actualizada y usuario activado correctamente</strong>
        </div>";
        
        // Verificar que funciona
        $test_user = AuthModel::login($email, $password);
        if ($test_user) {
            echo "<div class='success'>
                <strong>✓ Verificación exitosa: El login funciona correctamente</strong>
            </div>";
        } else {
            echo "<div class='error'>
                <strong>✗ Error: El login aún no funciona. Verifica la estructura de la tabla.</strong>
            </div>";
        }
    }
    
    echo "<div class='credentials'>
        <h3>📋 Credenciales de Acceso</h3>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Contraseña:</strong> {$password}</p>
        <p><a href='" . URL_SERV . "/dw-panel/login.php' class='btn'>🔐 Ir al Login</a></p>
    </div>";
    
    echo "<div class='info'>
        <strong>⚠️ IMPORTANTE:</strong><br>
        Por seguridad, elimina este archivo (fix-admin-password.php) después de corregir la contraseña.
    </div>";
    
} catch (PDOException $e) {
    echo "<div class='error'>
        <strong>✗ Error de Base de Datos:</strong><br>
        " . htmlspecialchars($e->getMessage()) . "
    </div>";
    
    if (DEBUG_MODE) {
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
}

echo "</div></body></html>";
?>


