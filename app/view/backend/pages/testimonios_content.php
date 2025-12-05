<?php
/**
 * Contenido de Gestión de Testimonios
 * DevSoft Build SAC
 */
?>
<div class="page-header">
    <div class="header-actions">
        <a href="<?php echo URL_SERV; ?>/dw-panel/testimonios?action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Testimonio
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
        <h2><?php echo $action === 'create' ? 'Nuevo Testimonio' : 'Editar Testimonio'; ?></h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?php echo $action === 'create' ? 'create' : 'update'; ?>">
            <?php if ($action === 'edit'): ?><input type="hidden" name="id" value="<?php echo $testimonio['id_testimonio']; ?>"><?php endif; ?>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="nombre_testimonio" value="<?php echo htmlspecialchars($testimonio['nombre_testimonio'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Cargo</label>
                    <input type="text" name="cargo_testimonio" value="<?php echo htmlspecialchars($testimonio['cargo_testimonio'] ?? ''); ?>" placeholder="CEO, Director, etc.">
                </div>
                
                <div class="form-group">
                    <label>Empresa</label>
                    <input type="text" name="empresa_testimonio" value="<?php echo htmlspecialchars($testimonio['empresa_testimonio'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>Calificación (1-5)</label>
                    <input type="number" name="calificacion_testimonio" min="1" max="5" value="<?php echo $testimonio['calificacion_testimonio'] ?? 5; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Orden</label>
                    <input type="number" name="orden_testimonio" value="<?php echo $testimonio['orden_testimonio'] ?? 0; ?>">
                </div>
                
                <div class="form-group full-width">
                    <label>Testimonio *</label>
                    <textarea name="testimonio_texto" rows="5" required><?php echo htmlspecialchars($testimonio['testimonio_texto'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group full-width">
                    <label>Subir Foto <?php echo ($action === 'create') ? '' : '(opcional)'; ?></label>
                    <input type="file" name="foto_testimonio_file" id="foto_testimonio_file" accept="image/jpeg,image/png,image/gif,image/webp" onchange="previewImage(this)">
                    <small style="display: block; margin-top: 5px; color: #7F8C8D;">Formatos: JPG, PNG, GIF, WEBP (máx 2MB)</small>
                </div>
                
                <div class="form-group full-width">
                    <label>O usar URL de Foto</label>
                    <input type="text" name="foto_testimonio" id="foto_testimonio_url" value="<?php echo htmlspecialchars($testimonio['foto_testimonio'] ?? ''); ?>" placeholder="https://...">
                </div>
                
                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado_testimonio">
                        <option value="1" <?php echo ($testimonio['estado_testimonio'] ?? 1) == 1 ? 'selected' : ''; ?>>Activo</option>
                        <option value="0" <?php echo ($testimonio['estado_testimonio'] ?? 1) == 0 ? 'selected' : ''; ?>>Inactivo</option>
                    </select>
                </div>
            </div>
            
            <div id="imagePreview" style="margin: 20px 0; display: none;">
                <label>Vista Previa:</label>
                <div style="margin-top: 10px; border: 2px solid #E0E0E0; border-radius: 8px; overflow: hidden; max-width: 200px;">
                    <img id="previewImg" src="" alt="Preview" style="width: 100%; display: block; max-height: 200px; object-fit: cover; border-radius: 50%;">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                <a href="<?php echo URL_SERV; ?>/dw-panel/testimonios" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>
<?php else: ?>
    <div class="filters-card">
        <form method="GET" class="filters-form">
            <div class="filters-grid">
                <div class="filter-group">
                    <label>Buscar:</label>
                    <input type="text" name="buscar" value="<?php echo htmlspecialchars($_GET['buscar'] ?? ''); ?>" placeholder="Nombre o empresa...">
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
                    <a href="<?php echo URL_SERV; ?>/dw-panel/testimonios" class="btn btn-secondary"><i class="fas fa-redo"></i> Limpiar</a>
                    <label style="margin-left: 15px;">
                        <input type="checkbox" name="mostrar_inactivos" <?php echo isset($_GET['mostrar_inactivos']) ? 'checked' : ''; ?> onchange="this.form.submit()">
                        Mostrar inactivos
                    </label>
                </div>
            </div>
        </form>
    </div>
    <div class="card">
        <div class="card-header"><h2>Testimonios (<?php echo count($testimonios); ?>)</h2></div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Nombre</th>
                        <th>Cargo/Empresa</th>
                        <th>Calificación</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($testimonios)): ?>
                        <tr>
                            <td colspan="7" class="text-center">No hay testimonios registrados</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($testimonios as $test): ?>
                            <tr>
                                <td>#<?php echo $test['id_testimonio']; ?></td>
                                <td>
                                    <?php if (!empty($test['foto_testimonio'])): ?>
                                        <img src="<?php echo htmlspecialchars($test['foto_testimonio']); ?>" alt="<?php echo htmlspecialchars($test['nombre_testimonio']); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                    <?php else: ?>
                                        <span style="color: #BDC3C7;">Sin foto</span>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo htmlspecialchars($test['nombre_testimonio']); ?></strong></td>
                                <td>
                                    <?php echo htmlspecialchars($test['cargo_testimonio'] ?? ''); ?>
                                    <?php if (!empty($test['empresa_testimonio'])): ?>
                                        <br><small style="color: #7F8C8D;"><?php echo htmlspecialchars($test['empresa_testimonio']); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star" style="color: <?php echo $i <= $test['calificacion_testimonio'] ? '#F39C12' : '#E0E0E0'; ?>;"></i>
                                    <?php endfor; ?>
                                </td>
                                <td>
                                    <span class="badge badge-<?php echo $test['estado_testimonio'] == 1 ? 'success' : 'danger'; ?>">
                                        <?php echo $test['estado_testimonio'] == 1 ? 'Activo' : 'Inactivo'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?php echo URL_SERV; ?>/dw-panel/testimonios?action=edit&id=<?php echo $test['id_testimonio']; ?>" class="btn-icon" title="Editar"><i class="fas fa-edit"></i></a>
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar testimonio?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $test['id_testimonio']; ?>">
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

<?php if (isset($testimonio) && !empty($testimonio['foto_testimonio'])): ?>
document.addEventListener('DOMContentLoaded', function() {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    if (preview && previewImg) {
        previewImg.src = '<?php echo htmlspecialchars($testimonio['foto_testimonio']); ?>';
        preview.style.display = 'block';
    }
});
<?php endif; ?>
</script>

