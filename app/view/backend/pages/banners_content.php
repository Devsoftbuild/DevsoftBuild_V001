<?php
/**
 * Contenido de Gestión de Banners
 * DevSoft Build SAC
 */
?>
<div class="page-header">
    <div class="header-actions">
        <a href="<?php echo URL_SERV; ?>/dw-panel/banners?action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Banner
        </a>
    </div>
</div>

<?php if ($message): ?>
    <div class="alert alert-<?php echo $message_type; ?>">
        <i class="fas fa-<?php echo $message_type === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<?php if ($action === 'create' || $action === 'edit'): ?>
    <div class="card">
        <h2><?php echo $action === 'create' ? 'Nuevo Banner' : 'Editar Banner'; ?></h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?php echo $action === 'create' ? 'create' : 'update'; ?>">
            <?php if ($action === 'edit'): ?><input type="hidden" name="id" value="<?php echo $banner['id_banner']; ?>"><?php endif; ?>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>Título</label>
                    <input type="text" name="titulo_banner" value="<?php echo htmlspecialchars($banner['titulo_banner'] ?? ''); ?>" placeholder="Título del banner">
                </div>
                
                <div class="form-group">
                    <label>Posición</label>
                    <select name="posicion_banner">
                        <option value="home" <?php echo ($banner['posicion_banner'] ?? 'home') === 'home' ? 'selected' : ''; ?>>Home</option>
                        <option value="servicios" <?php echo ($banner['posicion_banner'] ?? '') === 'servicios' ? 'selected' : ''; ?>>Servicios</option>
                        <option value="proyectos" <?php echo ($banner['posicion_banner'] ?? '') === 'proyectos' ? 'selected' : ''; ?>>Proyectos</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Enlace</label>
                    <input type="url" name="enlace_banner" value="<?php echo htmlspecialchars($banner['enlace_banner'] ?? ''); ?>" placeholder="URL de destino">
                </div>
                
                <div class="form-group">
                    <label>Texto del Botón</label>
                    <input type="text" name="texto_boton_banner" value="<?php echo htmlspecialchars($banner['texto_boton_banner'] ?? ''); ?>" placeholder="Ej: Cotizar Proyecto">
                </div>
                
                <div class="form-group">
                    <label>Orden</label>
                    <input type="number" name="orden_banner" value="<?php echo $banner['orden_banner'] ?? 0; ?>" min="0">
                </div>
                
                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado_banner">
                        <option value="1" <?php echo ($banner['estado_banner'] ?? 1) == 1 ? 'selected' : ''; ?>>Activo</option>
                        <option value="0" <?php echo ($banner['estado_banner'] ?? 1) == 0 ? 'selected' : ''; ?>>Inactivo</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Fecha Inicio</label>
                    <input type="date" name="fecha_inicio_banner" value="<?php echo $banner['fecha_inicio_banner'] ?? ''; ?>">
                </div>
                
                <div class="form-group">
                    <label>Fecha Fin</label>
                    <input type="date" name="fecha_fin_banner" value="<?php echo $banner['fecha_fin_banner'] ?? ''; ?>">
                </div>
                
                <div class="form-group full-width">
                    <label>Subir Imagen <?php echo ($action === 'create') ? '*' : '(opcional)'; ?></label>
                    <input type="file" name="imagen_banner_file" id="imagen_banner_file" accept="image/jpeg,image/png,image/gif,image/webp" <?php echo ($action === 'create') ? 'required' : ''; ?> onchange="previewImage(this)">
                    <small style="display: block; margin-top: 5px; color: #7F8C8D;">Formatos: JPG, PNG, GIF, WEBP (máx 5MB)<?php echo ($action === 'edit') ? '. Dejar vacío para mantener la imagen actual.' : ''; ?></small>
                </div>
                
                <div class="form-group full-width">
                    <label>O usar URL de Imagen</label>
                    <input type="text" name="imagen_banner" id="imagen_banner_url" value="<?php echo htmlspecialchars($banner['imagen_banner'] ?? ''); ?>" placeholder="https://... o dejar vacío si subes archivo">
                    <small style="display: block; margin-top: 5px; color: #7F8C8D;">Si subes archivo, esta URL se ignorará</small>
                </div>
            </div>
            
            <div id="imagePreview" style="margin: 20px 0; display: none;">
                <label>Vista Previa:</label>
                <div style="margin-top: 10px; border: 2px solid #E0E0E0; border-radius: 8px; overflow: hidden; max-width: 600px;">
                    <img id="previewImg" src="" alt="Preview" style="width: 100%; display: block; max-height: 300px; object-fit: cover;">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                <a href="<?php echo URL_SERV; ?>/dw-panel/banners" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-header"><h2>Banners (<?php echo count($banners); ?>)</h2></div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Título</th>
                        <th>Posición</th>
                        <th>Enlace</th>
                        <th>Orden</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($banners)): ?>
                        <tr>
                            <td colspan="8" class="text-center">No hay banners registrados</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($banners as $b): ?>
                            <tr>
                                <td>#<?php echo $b['id_banner']; ?></td>
                                <td>
                                    <?php if (!empty($b['imagen_banner'])): ?>
                                        <img src="<?php echo htmlspecialchars($b['imagen_banner']); ?>" alt="Banner" style="width: 100px; height: 50px; object-fit: cover; border-radius: 5px;">
                                    <?php else: ?>
                                        <span style="color: #BDC3C7;">Sin imagen</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($b['titulo_banner'] ?? 'Sin título'); ?></td>
                                <td><?php echo ucfirst($b['posicion_banner'] ?? '-'); ?></td>
                                <td><?php echo !empty($b['enlace_banner']) ? '<a href="' . htmlspecialchars($b['enlace_banner']) . '" target="_blank">Ver</a>' : '-'; ?></td>
                                <td><?php echo $b['orden_banner']; ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $b['estado_banner'] == 1 ? 'success' : 'danger'; ?>">
                                        <?php echo $b['estado_banner'] == 1 ? 'Activo' : 'Inactivo'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?php echo URL_SERV; ?>/dw-panel/banners?action=edit&id=<?php echo $b['id_banner']; ?>" class="btn-icon" title="Editar"><i class="fas fa-edit"></i></a>
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar banner?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $b['id_banner']; ?>">
                                            <button type="submit" class="btn-icon btn-danger" title="Eliminar"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}

<?php if (isset($banner) && !empty($banner['imagen_banner'])): ?>
document.addEventListener('DOMContentLoaded', function() {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    if (preview && previewImg) {
        previewImg.src = '<?php echo htmlspecialchars($banner['imagen_banner']); ?>';
        preview.style.display = 'block';
    }
});
<?php endif; ?>
</script>
