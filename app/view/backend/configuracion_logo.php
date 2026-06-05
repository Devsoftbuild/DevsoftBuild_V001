<?php
/**
 * Subir Logo
 * Panel de Administración
 */

require_once BASE_PATH . '/app/model/ConfigModel.php';
require_once BASE_PATH . '/app/model/Database.php';

$message = '';
$message_type = '';

// Crear directorio de uploads si no existe
$upload_dir = PUBLIC_PATH . '/img/logo/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Procesar subida de logo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['logo_file'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
    $max_size = 2 * 1024 * 1024; // 2MB
    
    if (in_array($file['type'], $allowed_types) && $file['size'] <= $max_size) {
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'logo_' . time() . '.' . $extension;
        $filepath = $upload_dir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            $logo_url = URL_SERV . '/app/public_root/img/logo/' . $filename;
            if (ConfigModel::set('logo_url', $logo_url, 'texto', 'general')) {
                ConfigModel::set('logo', $logo_url, 'texto', 'general');
                $message = 'Logo subido correctamente';
                $message_type = 'success';
            } else {
                $message = 'Error al guardar el logo';
                $message_type = 'error';
            }
        } else {
            $message = 'Error al subir el archivo';
            $message_type = 'error';
        }
    } else {
        $message = 'Formato de imagen no válido o archivo muy grande (máx 2MB)';
        $message_type = 'error';
    }
}

// Obtener logo actual
$logo_actual = ConfigModel::get('logo_url', '');

$page_title = 'Subir Logo';
$current_page = 'configuracion';
$content = '
<div class="card">
    <h2>Gestionar Logo del Sitio</h2>
    
    ' . ($message ? '<div class="alert alert-' . $message_type . '"><i class="fas fa-' . ($message_type === 'success' ? 'check-circle' : 'exclamation-circle') . '"></i> ' . htmlspecialchars($message) . '</div>' : '') . '
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 20px;">
        <div>
            <h3 style="margin-bottom: 15px;">Subir Nuevo Logo</h3>
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="logo_file">Seleccionar Imagen *</label>
                    <input type="file" name="logo_file" id="logo_file" accept="image/jpeg,image/png,image/gif,image/webp,image/svg+xml" required onchange="previewLogo(this)">
                    <small style="display: block; margin-top: 5px; color: #7F8C8D;">Formatos: JPG, PNG, GIF, WEBP, SVG (máx 2MB)</small>
                </div>
                <div id="logoPreview" style="margin: 20px 0; display: none;">
                    <label>Vista Previa:</label>
                    <div style="margin-top: 10px; border: 2px solid #E0E0E0; border-radius: 8px; padding: 15px; background: #F5F7FA;">
                        <img id="previewLogoImg" src="" alt="Preview" style="max-height: 100px; display: block;">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-upload"></i> Subir Logo
                </button>
            </form>
        </div>
        
        <div>
            <h3 style="margin-bottom: 15px;">Logo Actual</h3>
            ' . ($logo_actual ? '
                <div style="border: 2px solid #E0E0E0; border-radius: 8px; padding: 20px; background: #F5F7FA; text-align: center;">
                    <img src="' . htmlspecialchars($logo_actual) . '" alt="Logo Actual" style="max-height: 150px; max-width: 100%;">
                    <p style="margin-top: 15px; color: #7F8C8D; word-break: break-all; font-size: 0.9rem;">' . htmlspecialchars($logo_actual) . '</p>
                    <form method="POST" action="" style="margin-top: 15px;" onsubmit="return confirm(\'¿Eliminar logo?\');">
                        <input type="hidden" name="eliminar_logo" value="1">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Eliminar Logo
                        </button>
                    </form>
                </div>
            ' : '<p style="color: #7F8C8D;">No hay logo configurado</p>') . '
        </div>
    </div>
    
    <div style="margin-top: 30px;">
        <a href="' . URL_SERV . '/dw-panel/configuracion" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Configuración
        </a>
    </div>
</div>

<script>
function previewLogo(input) {
    const preview = document.getElementById("logoPreview");
    const previewImg = document.getElementById("previewLogoImg");
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = "block";
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_logo'])) {
    if (ConfigModel::set('logo_url', '', 'texto', 'general')) {
        ConfigModel::set('logo', '', 'texto', 'general');
        $message = 'Logo eliminado correctamente';
        $message_type = 'success';
        $logo_actual = '';
    }
}

include BASE_PATH . '/app/view/backend/includes/layout.php';
?>

