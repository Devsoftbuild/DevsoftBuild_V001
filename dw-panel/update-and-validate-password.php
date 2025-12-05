<?php
/**
 * Script para actualizar y validar la contraseña del usuario admin
 */

// Cargar core del sistema
require_once __DIR__ . '/../core.php';

// Cargar modelo de autenticación
require_once BASE_PATH . '/app/model/AuthModel.php';

$email = 'admin@sistemaestetica.com';
$password = 'admin123';

echo "=== ACTUALIZAR Y VALIDAR CONTRASEÑA ===\n\n";

try {
    $pdo = getDB();
    
    // Paso 1: Verificar si el usuario existe
    echo "1. Verificando usuario...\n";
    $stmt = $pdo->prepare("SELECT id, email, nombre FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo "   ✗ Usuario no encontrado. Creando usuario...\n";
        
        // Crear usuario
        $new_hash = password_hash($password, PASSWORD_DEFAULT);
        $insert_stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
        $insert_stmt->execute(['Administrador', $email, $new_hash, 'super_admin']);
        
        echo "   ✓ Usuario creado correctamente\n";
        $user_id = $pdo->lastInsertId();
    } else {
        echo "   ✓ Usuario encontrado (ID: {$user['id']}, Nombre: {$user['nombre']})\n";
        $user_id = $user['id'];
    }
    
    // Paso 2: Generar nuevo hash
    echo "\n2. Generando nuevo hash de contraseña...\n";
    $new_hash = password_hash($password, PASSWORD_DEFAULT);
    echo "   Hash generado: " . substr($new_hash, 0, 30) . "...\n";
    
    // Paso 3: Actualizar contraseña
    echo "\n3. Actualizando contraseña en la base de datos...\n";
    $update_stmt = $pdo->prepare("UPDATE usuarios SET password = ? WHERE email = ?");
    $result = $update_stmt->execute([$new_hash, $email]);
    
    if ($result) {
        echo "   ✓ Contraseña actualizada correctamente\n";
    } else {
        echo "   ✗ Error al actualizar la contraseña\n";
        exit(1);
    }
    
    // Paso 4: Verificar que el hash se guardó correctamente
    echo "\n4. Verificando hash guardado...\n";
    $stmt = $pdo->prepare("SELECT password FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $saved_hash = $stmt->fetchColumn();
    
    if ($saved_hash === $new_hash) {
        echo "   ✓ Hash guardado correctamente\n";
    } else {
        echo "   ✗ El hash no coincide\n";
        exit(1);
    }
    
    // Paso 5: Validar contraseña con password_verify
    echo "\n5. Validando contraseña con password_verify()...\n";
    $verify_result = password_verify($password, $saved_hash);
    
    if ($verify_result) {
        echo "   ✓ password_verify() retorna TRUE - La contraseña es válida\n";
    } else {
        echo "   ✗ password_verify() retorna FALSE - La contraseña NO es válida\n";
        exit(1);
    }
    
    // Paso 6: Probar login completo con AuthModel
    echo "\n6. Probando login completo con AuthModel::login()...\n";
    $login_result = AuthModel::login($email, $password);
    
    if ($login_result) {
        echo "   ✓ Login exitoso!\n";
        echo "   - ID: {$login_result['id']}\n";
        echo "   - Nombre: {$login_result['nombre']}\n";
        echo "   - Email: {$login_result['email']}\n";
        echo "   - Rol: {$login_result['rol']}\n";
    } else {
        echo "   ✗ Login fallido\n";
        exit(1);
    }
    
    // Paso 7: Probar con contraseña incorrecta
    echo "\n7. Probando con contraseña incorrecta (debe fallar)...\n";
    $wrong_login = AuthModel::login($email, 'password_incorrecta');
    
    if (!$wrong_login) {
        echo "   ✓ Correcto: Login rechazado con contraseña incorrecta\n";
    } else {
        echo "   ✗ ERROR: Login aceptado con contraseña incorrecta!\n";
        exit(1);
    }
    
    echo "\n=== RESULTADO FINAL ===\n";
    echo "✓ Todo correcto! El usuario puede hacer login con:\n";
    echo "  Email: {$email}\n";
    echo "  Password: {$password}\n";
    echo "\nPuedes acceder al panel en: " . URL_SERV . "/dw-panel/login.php\n";
    
} catch (Exception $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

