<?php
/**
 * Contenido de Gestión de Servicios
 * DevSoft Build SAC
 */
?>
<div class="page-header">
    <div class="header-actions">
        <a href="<?php echo URL_SERV; ?>/dw-panel/servicios?action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Servicio
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
        <h2><?php echo $action === 'create' ? 'Nuevo Servicio' : 'Editar Servicio'; ?></h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?php echo $action === 'create' ? 'create' : 'update'; ?>">
            <?php if ($action === 'edit'): ?><input type="hidden" name="id" value="<?php echo $servicio['id_servicio']; ?>"><?php endif; ?>
            
            <div class="form-grid">
                <div class="form-group full-width">
                    <label>Nombre del Servicio *</label>
                    <input type="text" name="nombre_servicio" value="<?php echo htmlspecialchars($servicio['nombre_servicio'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group full-width">
                    <label>Slug (URL amigable)</label>
                    <input type="text" name="slug_servicio" value="<?php echo htmlspecialchars($servicio['slug_servicio'] ?? ''); ?>" placeholder="Se genera automáticamente si se deja vacío">
                    <small>Ejemplo: desarrollo-web, apps-moviles</small>
                </div>
                
                <div class="form-group full-width">
                    <label>Descripción Corta</label>
                    <textarea name="descripcion_corta_servicio" rows="2"><?php echo htmlspecialchars($servicio['descripcion_corta_servicio'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group full-width">
                    <label>Descripción Larga</label>
                    <textarea name="descripcion_larga_servicio" rows="6"><?php echo htmlspecialchars($servicio['descripcion_larga_servicio'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Icono (FontAwesome)</label>
                    <input type="text" name="icono_servicio" value="<?php echo htmlspecialchars($servicio['icono_servicio'] ?? ''); ?>" placeholder="fas fa-laptop-code">
                    <small>Ejemplo: fas fa-laptop-code, fas fa-mobile-alt</small>
                </div>
                
                <div class="form-group">
                    <label>Precio Desde</label>
                    <input type="number" name="precio_desde_servicio" step="0.01" value="<?php echo $servicio['precio_desde_servicio'] ?? ''; ?>" placeholder="0.00">
                </div>
                
                <div class="form-group">
                    <label>Orden</label>
                    <input type="number" name="orden_servicio" value="<?php echo $servicio['orden_servicio'] ?? 0; ?>">
                </div>
                
                <div class="form-group full-width">
                    <label>Subir Imagen <?php echo ($action === 'create') ? '' : '(opcional)'; ?></label>
                    <input type="file" name="imagen_servicio_file" id="imagen_servicio_file" accept="image/jpeg,image/png,image/gif,image/webp" onchange="previewImage(this)">
                    <small style="display: block; margin-top: 5px; color: #7F8C8D;">Formatos: JPG, PNG, GIF, WEBP (máx 5MB)<?php echo ($action === 'edit') ? '. Dejar vacío para mantener la imagen actual.' : ''; ?></small>
                </div>
                
                <div class="form-group full-width">
                    <label>O usar URL de Imagen</label>
                    <input type="text" name="imagen_servicio" id="imagen_servicio_url" value="<?php echo htmlspecialchars($servicio['imagen_servicio'] ?? ''); ?>" placeholder="https://... o dejar vacío si subes archivo">
                    <small style="display: block; margin-top: 5px; color: #7F8C8D;">Si subes archivo, esta URL se ignorará</small>
                </div>
                
                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado_servicio">
                        <option value="1" <?php echo ($servicio['estado_servicio'] ?? 1) == 1 ? 'selected' : ''; ?>>Activo</option>
                        <option value="0" <?php echo ($servicio['estado_servicio'] ?? 1) == 0 ? 'selected' : ''; ?>>Inactivo</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Destacado</label>
                    <select name="destacado_servicio">
                        <option value="0" <?php echo ($servicio['destacado_servicio'] ?? 0) == 0 ? 'selected' : ''; ?>>No</option>
                        <option value="1" <?php echo ($servicio['destacado_servicio'] ?? 0) == 1 ? 'selected' : ''; ?>>Sí</option>
                    </select>
                </div>
            </div>
            
            <div class="form-section">
                <h3>SEO</h3>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Meta Título</label>
                        <input type="text" name="meta_titulo_servicio" value="<?php echo htmlspecialchars($servicio['meta_titulo_servicio'] ?? ''); ?>" maxlength="255">
                    </div>
                    <div class="form-group full-width">
                        <label>Meta Descripción</label>
                        <textarea name="meta_descripcion_servicio" rows="2" maxlength="500"><?php echo htmlspecialchars($servicio['meta_descripcion_servicio'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group full-width">
                        <label>Meta Keywords</label>
                        <input type="text" name="meta_keywords_servicio" value="<?php echo htmlspecialchars($servicio['meta_keywords_servicio'] ?? ''); ?>" placeholder="palabra1, palabra2, palabra3">
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
                <a href="<?php echo URL_SERV; ?>/dw-panel/servicios" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>
<?php else: ?>
    <div class="filters-card">
        <form method="GET" class="filters-form">
            <div class="filters-grid">
                <div class="filter-group">
                    <label>Buscar:</label>
                    <input type="text" name="buscar" value="<?php echo htmlspecialchars($_GET['buscar'] ?? ''); ?>" placeholder="Nombre del servicio...">
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
                    <a href="<?php echo URL_SERV; ?>/dw-panel/servicios" class="btn btn-secondary"><i class="fas fa-redo"></i> Limpiar</a>
                    <label style="margin-left: 15px;">
                        <input type="checkbox" name="mostrar_inactivos" <?php echo isset($_GET['mostrar_inactivos']) ? 'checked' : ''; ?> onchange="this.form.submit()">
                        Mostrar inactivos
                    </label>
                </div>
            </div>
        </form>
    </div>
    <div class="card">
        <div class="card-header"><h2>Servicios (<?php echo count($servicios); ?>)</h2></div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Icono</th>
                        <th>Precio Desde</th>
                        <th>Orden</th>
                        <th>Estado</th>
                        <th>Destacado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($servicios)): ?>
                        <tr>
                            <td colspan="9" class="text-center">No hay servicios registrados</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($servicios as $s): ?>
                            <tr>
                                <td>#<?php echo $s['id_servicio']; ?></td>
                                <td>
                                    <?php if (!empty($s['imagen_servicio'])): ?>
                                        <img src="<?php echo htmlspecialchars($s['imagen_servicio']); ?>" alt="<?php echo htmlspecialchars($s['nombre_servicio']); ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                    <?php else: ?>
                                        <span style="color: #BDC3C7;">Sin imagen</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($s['nombre_servicio']); ?></strong>
                                    <?php if (!empty($s['descripcion_corta_servicio'])): ?>
                                        <br><small style="color: #7F8C8D;"><?php echo truncateText($s['descripcion_corta_servicio'], 50); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($s['icono_servicio'])): ?>
                                        <i class="<?php echo htmlspecialchars($s['icono_servicio']); ?>"></i>
                                    <?php else: ?>
                                        <span style="color: #BDC3C7;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($s['precio_desde_servicio'])): ?>
                                        <?php echo formatPrice($s['precio_desde_servicio']); ?>
                                    <?php else: ?>
                                        <span style="color: #BDC3C7;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $s['orden_servicio']; ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $s['estado_servicio'] == 1 ? 'success' : 'danger'; ?>">
                                        <?php echo $s['estado_servicio'] == 1 ? 'Activo' : 'Inactivo'; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($s['destacado_servicio'] == 1): ?>
                                        <span class="badge badge-warning"><i class="fas fa-star"></i> Destacado</span>
                                    <?php else: ?>
                                        <span style="color: #BDC3C7;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?php echo URL_SERV; ?>/dw-panel/servicios?action=edit&id=<?php echo $s['id_servicio']; ?>" class="btn-icon" title="Editar"><i class="fas fa-edit"></i></a>
                                        <a href="<?php echo URL_SERV; ?>/servicio/<?php echo htmlspecialchars($s['slug_servicio']); ?>" target="_blank" class="btn-icon" title="Ver en sitio"><i class="fas fa-eye"></i></a>
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar servicio?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $s['id_servicio']; ?>">
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

// Mostrar preview si hay imagen existente al editar
<?php if (isset($servicio) && !empty($servicio['imagen_servicio'])): ?>
document.addEventListener('DOMContentLoaded', function() {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    if (preview && previewImg) {
        previewImg.src = '<?php echo htmlspecialchars($servicio['imagen_servicio']); ?>';
        preview.style.display = 'block';
    }
});
<?php endif; ?>
</script>
