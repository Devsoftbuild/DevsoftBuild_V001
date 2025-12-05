<?php
/**
 * Página de Login
 * Panel de Administración
 */

// Cargar core del sistema
require_once __DIR__ . '/../core.php';

// Cargar modelo de autenticación
require_once BASE_PATH . '/app/model/AuthModel.php';

// Si ya está autenticado, redirigir al dashboard
if (AuthModel::isAuthenticated()) {
    header('Location: ' . URL_SERV . '/dw-panel/');
    exit;
}

$error = '';
$success = '';

// Procesar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if (empty($email) || empty($password)) {
        $error = 'Por favor, completa todos los campos';
    } else {
        // Debug: mostrar información de depuración si está en modo debug
        if (DEBUG_MODE) {
            error_log("Intento de login - Email: " . $email);
        }
        
        $user = AuthModel::login($email, $password);
        
        if ($user) {
            // Iniciar sesión
            $_SESSION['admin_user_id'] = $user['id'];
            $_SESSION['admin_user_name'] = $user['nombre'];
            $_SESSION['admin_user_role'] = $user['rol'];
            
            // Redirigir al dashboard
            header('Location: ' . URL_SERV . '/dw-panel/');
            exit;
        } else {
            $error = 'Email o contraseña incorrectos';
            
            // Debug adicional
            if (DEBUG_MODE) {
                try {
                    $pdo = getDB();
                    $stmt = $pdo->prepare("SELECT id_usuario, email_usuario, estado_usuario FROM usuarios WHERE email_usuario = ?");
                    $stmt->execute([$email]);
                    $db_user = $stmt->fetch();
                    
                    if ($db_user) {
                        $error .= ' (Usuario encontrado en BD, pero contraseña incorrecta o usuario inactivo)';
                    } else {
                        $error .= ' (Usuario no encontrado en BD)';
                    }
                } catch (Exception $e) {
                    $error .= ' (Error al verificar: ' . $e->getMessage() . ')';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Panel de Administración</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0a1a1f 0%, #1a3a47 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 450px;
            padding: 40px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header i {
            font-size: 3rem;
            color: #00d4ff;
            margin-bottom: 15px;
        }
        
        .login-header h1 {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.8rem;
            color: #2C3E50;
            margin-bottom: 5px;
        }
        
        .login-header p {
            color: #7F8C8D;
            font-size: 0.9rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2C3E50;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #E0E0E0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #00d4ff;
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #00d4ff, #1dd1e8);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 212, 255, 0.4);
        }
        
        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        
        .alert-error {
            background: #FEE;
            color: #E74C3C;
            border: 1px solid #E74C3C;
        }
        
        .alert-success {
            background: #EFE;
            color: #27AE60;
            border: 1px solid #27AE60;
        }
        
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-link a {
            color: #00d4ff;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-code"></i>
            <h1>Panel de Administración</h1>
            <p><?php echo APP_NAME; ?></p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">
                    <i class="fas fa-envelope"></i> Email
                </label>
                <input type="email" id="email" name="email" required 
                       placeholder="tu@email.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i> Contraseña
                </label>
                <input type="password" id="password" name="password" required 
                       placeholder="••••••••">
            </div>
            
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </button>
        </form>
        
        <div class="back-link">
            <a href="<?php echo URL_SERV; ?>">
                <i class="fas fa-arrow-left"></i> Volver al sitio web
            </a>
        </div>
    </div>
</body>
</html>

