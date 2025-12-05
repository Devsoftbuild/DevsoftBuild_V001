<?php
/**
 * Contenido de Gestión de Blog
 * DevSoft Build SAC
 */
?>
<div class="page-header">
    <div class="header-actions">
        <a href="<?php echo URL_SERV; ?>/dw-panel/blog?action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Artículo
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
        <h2><?php echo $action === 'create' ? 'Nuevo Artículo' : 'Editar Artículo'; ?></h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?php echo $action === 'create' ? 'create' : 'update'; ?>">
            <?php if ($action === 'edit'): ?><input type="hidden" name="id" value="<?php echo $articulo['id_articulo']; ?>"><?php endif; ?>
            
            <div class="form-grid">
                <div class="form-group full-width">
                    <label>Título *</label>
                    <input type="text" name="titulo_articulo" value="<?php echo htmlspecialchars($articulo['titulo_articulo'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group full-width">
                    <label>Slug (URL amigable)</label>
                    <input type="text" name="slug_articulo" value="<?php echo htmlspecialchars($articulo['slug_articulo'] ?? ''); ?>" placeholder="Se genera automáticamente si se deja vacío">
                </div>
                
                <div class="form-group">
                    <label>Autor</label>
                    <input type="text" name="autor_articulo" value="<?php echo htmlspecialchars($articulo['autor_articulo'] ?? 'Admin'); ?>">
                </div>
                
                <div class="form-group">
                    <label>Fecha Publicación</label>
                    <input type="datetime-local" name="fecha_publicacion_articulo" value="<?php echo !empty($articulo['fecha_publicacion_articulo']) ? date('Y-m-d\TH:i', strtotime($articulo['fecha_publicacion_articulo'])) : date('Y-m-d\TH:i'); ?>">
                </div>
                
                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado_articulo">
                        <option value="1" <?php echo ($articulo['estado_articulo'] ?? 0) == 1 ? 'selected' : ''; ?>>Publicado</option>
                        <option value="0" <?php echo ($articulo['estado_articulo'] ?? 0) == 0 ? 'selected' : ''; ?>>Borrador</option>
                    </select>
                </div>
                
                <div class="form-group full-width">
                    <label>Subir Imagen <?php echo ($action === 'create') ? '' : '(opcional)'; ?></label>
                    <input type="file" name="imagen_articulo_file" id="imagen_articulo_file" accept="image/jpeg,image/png,image/gif,image/webp" onchange="previewImage(this)">
                    <small style="display: block; margin-top: 5px; color: #7F8C8D;">Formatos: JPG, PNG, GIF, WEBP (máx 5MB)</small>
                </div>
                
                <div class="form-group full-width">
                    <label>O usar URL de Imagen</label>
                    <input type="text" name="imagen_articulo" id="imagen_articulo_url" value="<?php echo htmlspecialchars($articulo['imagen_articulo'] ?? ''); ?>" placeholder="https://...">
                </div>
                
                <div class="form-group full-width">
                    <label>Resumen</label>
                    <textarea name="resumen_articulo" rows="3"><?php echo htmlspecialchars($articulo['resumen_articulo'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group full-width">
                    <label>Contenido *</label>
                    <textarea name="contenido_articulo" rows="15" required><?php echo htmlspecialchars($articulo['contenido_articulo'] ?? ''); ?></textarea>
                </div>
            </div>
            
            <div class="form-section">
                <h3>SEO</h3>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Meta Título</label>
                        <input type="text" name="meta_titulo_articulo" value="<?php echo htmlspecialchars($articulo['meta_titulo_articulo'] ?? ''); ?>" maxlength="255">
                    </div>
                    <div class="form-group full-width">
                        <label>Meta Descripción</label>
                        <textarea name="meta_descripcion_articulo" rows="2" maxlength="500"><?php echo htmlspecialchars($articulo['meta_descripcion_articulo'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group full-width">
                        <label>Meta Keywords</label>
                        <input type="text" name="meta_keywords_articulo" value="<?php echo htmlspecialchars($articulo['meta_keywords_articulo'] ?? ''); ?>" placeholder="palabra1, palabra2, palabra3">
                    </div>
                </div>
            </div>
            
            <div id="imagePreview" style="margin: 20px 0; display: none;">
                <label>Vista Previa:</label>
                <div style="margin-top: 10px; border: 2px solid #E0E0E0; border-radius: 8px; overflow: hidden; max-width: 400px;">
                    <img id="previewImg" src="" alt="Preview" style="width: 100%; display: block; max-height: 300px; object-fit: cover;">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                <a href="<?php echo URL_SERV; ?>/dw-panel/blog" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-header"><h2>Artículos del Blog (<?php echo count($articulos); ?>)</h2></div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($articulos)): ?>
                        <tr>
                            <td colspan="6" class="text-center">No hay artículos registrados</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($articulos as $a): ?>
                            <tr>
                                <td>#<?php echo $a['id_articulo']; ?></td>
                                <td><strong><?php echo htmlspecialchars($a['titulo_articulo'] ?? 'Sin título'); ?></strong></td>
                                <td><?php echo htmlspecialchars($a['autor_articulo'] ?? 'Admin'); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo ($a['estado_articulo'] ?? 0) == 1 ? 'success' : 'warning'; ?>">
                                        <?php echo ($a['estado_articulo'] ?? 0) == 1 ? 'Publicado' : 'Borrador'; ?>
                                    </span>
                                </td>
                                <td><?php echo !empty($a['fecha_publicacion_articulo']) ? date('d/m/Y', strtotime($a['fecha_publicacion_articulo'])) : '-'; ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?php echo URL_SERV; ?>/dw-panel/blog?action=edit&id=<?php echo $a['id_articulo']; ?>" class="btn-icon" title="Editar"><i class="fas fa-edit"></i></a>
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar artículo?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $a['id_articulo']; ?>">
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

<?php if (isset($articulo) && !empty($articulo['imagen_articulo'])): ?>
document.addEventListener('DOMContentLoaded', function() {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    if (preview && previewImg) {
        previewImg.src = '<?php echo htmlspecialchars($articulo['imagen_articulo']); ?>';
        preview.style.display = 'block';
    }
});
<?php endif; ?>
</script>
