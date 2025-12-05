<?php
/**
 * Script de Prueba para Login
 * Diagnóstico de problemas de autenticación
 */

// Cargar core del sistema
require_once __DIR__ . '/../core.php';

// Cargar modelo de autenticación
require_once BASE_PATH . '/app/model/AuthModel.php';

echo "<h1>Test de Login - Diagnóstico</h1>";
echo "<pre>";

// 1. Verificar conexión a BD
echo "=== 1. VERIFICAR CONEXIÓN A BD ===\n";
try {
    $pdo = getDB();
    echo "✓ Conexión exitosa\n\n";
} catch (Exception $e) {
    echo "✗ Error de conexión: " . $e->getMessage() . "\n\n";
    exit;
}

// 2. Verificar si existe el usuario
echo "=== 2. VERIFICAR USUARIO EN BD ===\n";
$email = 'admin@sistemaestetica.com';
$stmt = $pdo->prepare("SELECT id, nombre, email, password, rol, activo FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo "✓ Usuario encontrado:\n";
    echo "  - ID: " . $user['id'] . "\n";
    echo "  - Nombre: " . $user['nombre'] . "\n";
    echo "  - Email: " . $user['email'] . "\n";
    echo "  - Rol: " . $user['rol'] . "\n";
    echo "  - Activo: " . ($user['activo'] ? 'Sí' : 'No') . "\n";
    echo "  - Password Hash: " . substr($user['password'], 0, 20) . "...\n";
    echo "  - Longitud Hash: " . strlen($user['password']) . " caracteres\n\n";
} else {
    echo "✗ Usuario NO encontrado en la BD\n";
    echo "  Email buscado: " . $email . "\n\n";
    
    // Listar todos los usuarios
    echo "=== USUARIOS EN LA BD ===\n";
    $stmt = $pdo->query("SELECT id, nombre, email, activo FROM usuarios");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($users)) {
        echo "✗ No hay usuarios en la base de datos\n\n";
    } else {
        foreach ($users as $u) {
            echo "  - ID: {$u['id']}, Email: {$u['email']}, Nombre: {$u['nombre']}, Activo: {$u['activo']}\n";
        }
        echo "\n";
    }
    exit;
}

// 3. Probar contraseña
echo "=== 3. PROBAR CONTRASEÑA ===\n";
$password_test = 'admin123';
echo "Contraseña a probar: " . $password_test . "\n";
echo "Hash en BD: " . $user['password'] . "\n";

$verify_result = password_verify($password_test, $user['password']);
echo "Resultado password_verify: " . ($verify_result ? '✓ CORRECTO' : '✗ INCORRECTO') . "\n\n";

if (!$verify_result) {
    echo "=== 4. GENERAR NUEVO HASH ===\n";
    $new_hash = password_hash($password_test, PASSWORD_DEFAULT);
    echo "Nuevo hash generado: " . $new_hash . "\n";
    echo "Verificar nuevo hash: " . (password_verify($password_test, $new_hash) ? '✓ CORRECTO' : '✗ INCORRECTO') . "\n\n";
    
    echo "=== 5. ACTUALIZAR CONTRASEÑA EN BD ===\n";
    echo "¿Deseas actualizar la contraseña en la BD? (Esto actualizará el hash)\n";
    echo "Ejecuta este SQL en phpMyAdmin:\n";
    echo "UPDATE usuarios SET password = '" . $new_hash . "' WHERE email = '" . $email . "';\n\n";
}

// 4. Probar login completo
echo "=== 6. PROBAR LOGIN COMPLETO ===\n";
$login_result = AuthModel::login($email, $password_test);
if ($login_result) {
    echo "✓ Login exitoso\n";
    echo "  Usuario retornado: " . print_r($login_result, true) . "\n";
} else {
    echo "✗ Login fallido\n";
    echo "  Email: " . $email . "\n";
    echo "  Password: " . $password_test . "\n";
}

echo "\n=== FIN DEL TEST ===\n";
echo "</pre>";

// Botón para actualizar contraseña
if (!$verify_result) {
    echo "<hr>";
    echo "<h2>Actualizar Contraseña</h2>";
    echo "<form method='POST'>";
    echo "<input type='hidden' name='update_password' value='1'>";
    echo "<button type='submit' style='padding: 10px 20px; background: #9B59B6; color: white; border: none; border-radius: 5px; cursor: pointer;'>Actualizar Contraseña en BD</button>";
    echo "</form>";
    
    if (isset($_POST['update_password'])) {
        $new_hash = password_hash($password_test, PASSWORD_DEFAULT);
        $update_stmt = $pdo->prepare("UPDATE usuarios SET password = ? WHERE email = ?");
        if ($update_stmt->execute([$new_hash, $email])) {
            echo "<p style='color: green;'>✓ Contraseña actualizada correctamente. Intenta hacer login nuevamente.</p>";
        } else {
            echo "<p style='color: red;'>✗ Error al actualizar la contraseña.</p>";
        }
    }
}

