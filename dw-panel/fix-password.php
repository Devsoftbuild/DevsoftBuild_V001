<?php
/**
 * Script para corregir la contraseña del usuario admin
 */

// Cargar core del sistema
require_once __DIR__ . '/../core.php';

// Cargar modelo de autenticación
require_once BASE_PATH . '/app/model/AuthModel.php';

$email = 'admin@sistemaestetica.com';
$password = 'admin123';

echo "<h1>Corregir Contraseña del Usuario Admin</h1>";
echo "<pre>";

try {
    $pdo = getDB();
    
    // Verificar si el usuario existe
    $stmt = $pdo->prepare("SELECT id, email FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if (!$user) {
        echo "✗ Usuario no encontrado. Creando usuario...\n";
        
        // Crear usuario
        $new_hash = password_hash($password, PASSWORD_DEFAULT);
        $insert_stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
        $insert_stmt->execute(['Administrador', $email, $new_hash, 'super_admin']);
        
        echo "✓ Usuario creado correctamente\n";
        echo "Email: " . $email . "\n";
        echo "Password: " . $password . "\n";
        echo "Hash: " . $new_hash . "\n";
    } else {
        echo "✓ Usuario encontrado (ID: " . $user['id'] . ")\n";
        echo "Actualizando contraseña...\n";
        
        // Generar nuevo hash
        $new_hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Actualizar contraseña
        $update_stmt = $pdo->prepare("UPDATE usuarios SET password = ? WHERE email = ?");
        $update_stmt->execute([$new_hash, $email]);
        
        echo "✓ Contraseña actualizada correctamente\n";
        echo "Email: " . $email . "\n";
        echo "Password: " . $password . "\n";
        echo "Nuevo Hash: " . $new_hash . "\n";
        
        // Verificar que funciona
        echo "\nVerificando login...\n";
        $test_user = AuthModel::login($email, $password);
        if ($test_user) {
            echo "✓ Login exitoso! El usuario puede iniciar sesión correctamente.\n";
        } else {
            echo "✗ Error: El login aún falla después de actualizar.\n";
        }
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN ===\n";
echo "</pre>";
echo "<p><a href='login.php'>Ir al Login</a></p>";

