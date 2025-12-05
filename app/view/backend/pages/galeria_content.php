<?php
?>
<div class="page-header">
    <div class="header-actions">
        <a href="<?php echo URL_SERV; ?>/dw-panel/galeria?action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Agregar Trabajo
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
        <h2><?php echo $action === 'create' ? 'Agregar Trabajo' : 'Editar Trabajo'; ?></h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?php echo $action === 'create' ? 'create' : 'update'; ?>">
            <?php if ($action === 'edit'): ?><input type="hidden" name="id" value="<?php echo $trabajo['id']; ?>"><?php endif; ?>
            
            <div class="form-grid">
                <div class="form-group"><label>Servicio</label>
                    <select name="servicio_id">
                        <option value="">Seleccione</option>
                        <?php foreach ($servicios as $s): ?>
                            <option value="<?php echo $s['id']; ?>" <?php echo ($trabajo && $trabajo['servicio_id'] == $s['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($s['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Título</label><input type="text" name="titulo" value="<?php echo $trabajo['titulo'] ?? ''; ?>"></div>
                <div class="form-group"><label>Destacado</label>
                    <select name="destacado">
                        <option value="0" <?php echo ($trabajo['destacado'] ?? 0) ? '' : 'selected'; ?>>No</option>
                        <option value="1" <?php echo ($trabajo['destacado'] ?? 0) ? 'selected' : ''; ?>>Sí</option>
                    </select>
                </div>
                <div class="form-group"><label>Estado</label>
                    <select name="activo">
                        <option value="1" <?php echo ($trabajo['activo'] ?? 1) ? 'selected' : ''; ?>>Activo</option>
                        <option value="0" <?php echo ($trabajo['activo'] ?? 1) ? '' : 'selected'; ?>>Inactivo</option>
                    </select>
                </div>
            </div>
            <div class="form-group"><label>Descripción</label><textarea name="descripcion" rows="4"><?php echo $trabajo['descripcion'] ?? ''; ?></textarea></div>
            
            <div class="form-group full-width"><label>Subir Imagen "Antes" <?php echo ($action === 'create') ? '' : '(opcional)'; ?></label>
                <input type="file" name="imagen_antes_file" id="imagen_antes_file" accept="image/jpeg,image/png,image/gif,image/webp" onchange="previewImage(this, 'previewAntes')">
                <small style="display: block; margin-top: 5px; color: #7F8C8D;">Formatos: JPG, PNG, GIF, WEBP (máx 5MB)</small>
            </div>
            <div class="form-group full-width"><label>O usar URL de Imagen "Antes"</label>
                <input type="text" name="imagen_antes" id="imagen_antes_url" value="<?php echo $trabajo['imagen_antes'] ?? ''; ?>" placeholder="https://... o dejar vacío si subes archivo">
            </div>
            
            <div class="form-group full-width"><label>Subir Imagen "Después" <?php echo ($action === 'create') ? '*' : '(opcional)'; ?></label>
                <input type="file" name="imagen_despues_file" id="imagen_despues_file" accept="image/jpeg,image/png,image/gif,image/webp" onchange="previewImage(this, 'previewDespues')">
                <small style="display: block; margin-top: 5px; color: #7F8C8D;">Formatos: JPG, PNG, GIF, WEBP (máx 5MB)</small>
            </div>
            <div class="form-group full-width"><label>O usar URL de Imagen "Después"</label>
                <input type="text" name="imagen_despues" id="imagen_despues_url" value="<?php echo $trabajo['imagen_despues'] ?? ''; ?>" placeholder="https://... o dejar vacío si subes archivo">
            </div>
            
            <div id="imagePreviews" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0;">
                <div id="previewAntes" style="display: none;">
                    <label>Vista Previa "Antes":</label>
                    <div style="margin-top: 10px; border: 2px solid #E0E0E0; border-radius: 8px; overflow: hidden;">
                        <img id="previewAntesImg" src="" alt="Preview Antes" style="width: 100%; display: block; max-height: 250px; object-fit: cover;">
                    </div>
                </div>
                <div id="previewDespues" style="display: none;">
                    <label>Vista Previa "Después":</label>
                    <div style="margin-top: 10px; border: 2px solid #E0E0E0; border-radius: 8px; overflow: hidden;">
                        <img id="previewDespuesImg" src="" alt="Preview Después" style="width: 100%; display: block; max-height: 250px; object-fit: cover;">
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                <a href="<?php echo URL_SERV; ?>/dw-panel/galeria" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-header"><h2>Galería de Trabajos (<?php echo count($galeria); ?>)</h2></div>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; padding: 20px;">
            <?php if (empty($galeria)): ?>
                <div class="text-center" style="grid-column: 1/-1; padding: 40px;">
                    <i class="fas fa-images" style="font-size: 3rem; color: #BDC3C7;"></i>
                    <p style="color: #7F8C8D;">No hay trabajos en la galería</p>
                </div>
            <?php else: ?>
                <?php foreach ($galeria as $g): ?>
                    <div class="card" style="padding: 0; overflow: hidden;">
                        <?php if ($g['imagen_despues']): ?>
                            <img src="<?php echo htmlspecialchars($g['imagen_despues']); ?>" alt="<?php echo htmlspecialchars($g['titulo'] ?? ''); ?>" 
                                 style="width: 100%; height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div style="width: 100%; height: 200px; background: #F5F7FA; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-image" style="font-size: 3rem; color: #BDC3C7;"></i>
                            </div>
                        <?php endif; ?>
                        <div style="padding: 15px;">
                            <h4 style="margin: 0 0 10px 0;"><?php echo htmlspecialchars($g['titulo'] ?? 'Sin título'); ?></h4>
                            <p style="margin: 5px 0; color: #7F8C8D; font-size: 0.9rem;">
                                <?php echo htmlspecialchars($g['servicio_nombre'] ?? '-'); ?>
                            </p>
                            <?php if ($g['destacado']): ?>
                                <span class="badge badge-confirmada" style="margin-top: 10px; display: inline-block;">Destacado</span>
                            <?php endif; ?>
                            <div class="action-buttons" style="margin-top: 15px;">
                                <a href="<?php echo URL_SERV; ?>/dw-panel/galeria?action=edit&id=<?php echo $g['id']; ?>" class="btn-icon" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar trabajo?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $g['id']; ?>">
                                    <button type="submit" class="btn-icon btn-danger" title="Eliminar"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const previewImg = document.getElementById(previewId + 'Img');
    
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

// Mostrar preview si hay imágenes existentes al editar
<?php if (isset($trabajo) && $trabajo): ?>
document.addEventListener('DOMContentLoaded', function() {
    <?php if (!empty($trabajo['imagen_antes'])): ?>
    const previewAntes = document.getElementById('previewAntes');
    const previewAntesImg = document.getElementById('previewAntesImg');
    if (previewAntes && previewAntesImg) {
        previewAntesImg.src = '<?php echo htmlspecialchars($trabajo['imagen_antes']); ?>';
        previewAntes.style.display = 'block';
    }
    <?php endif; ?>
    
    <?php if (!empty($trabajo['imagen_despues'])): ?>
    const previewDespues = document.getElementById('previewDespues');
    const previewDespuesImg = document.getElementById('previewDespuesImg');
    if (previewDespues && previewDespuesImg) {
        previewDespuesImg.src = '<?php echo htmlspecialchars($trabajo['imagen_despues']); ?>';
        previewDespues.style.display = 'block';
    }
    <?php endif; ?>
});
<?php endif; ?>
</script>

