<?php
/**
 * Contenido de Gestión de Tecnologías
 * DevSoft Build SAC
 */
?>
<div class="page-header">
    <div class="header-actions">
        <a href="<?php echo URL_SERV; ?>/dw-panel/tecnologias?action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Tecnología
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
        <h2><?php echo $action === 'create' ? 'Nueva Tecnología' : 'Editar Tecnología'; ?></h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?php echo $action === 'create' ? 'create' : 'update'; ?>">
            <?php if ($action === 'edit'): ?><input type="hidden" name="id" value="<?php echo $tecnologia['id_tecnologia']; ?>"><?php endif; ?>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="nombre_tecnologia" value="<?php echo htmlspecialchars($tecnologia['nombre_tecnologia'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Categoría</label>
                    <select name="categoria_tecnologia">
                        <option value="frontend" <?php echo ($tecnologia['categoria_tecnologia'] ?? '') === 'frontend' ? 'selected' : ''; ?>>Frontend</option>
                        <option value="backend" <?php echo ($tecnologia['categoria_tecnologia'] ?? '') === 'backend' ? 'selected' : ''; ?>>Backend</option>
                        <option value="database" <?php echo ($tecnologia['categoria_tecnologia'] ?? '') === 'database' ? 'selected' : ''; ?>>Base de Datos</option>
                        <option value="mobile" <?php echo ($tecnologia['categoria_tecnologia'] ?? '') === 'mobile' ? 'selected' : ''; ?>>Móvil</option>
                        <option value="cloud" <?php echo ($tecnologia['categoria_tecnologia'] ?? '') === 'cloud' ? 'selected' : ''; ?>>Cloud</option>
                        <option value="otro" <?php echo ($tecnologia['categoria_tecnologia'] ?? '') === 'otro' ? 'selected' : ''; ?>>Otro</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>URL</label>
                    <input type="url" name="url_tecnologia" value="<?php echo htmlspecialchars($tecnologia['url_tecnologia'] ?? ''); ?>" placeholder="https://...">
                </div>
                
                <div class="form-group">
                    <label>Orden</label>
                    <input type="number" name="orden_tecnologia" value="<?php echo $tecnologia['orden_tecnologia'] ?? 0; ?>">
                </div>
                
                <div class="form-group full-width">
                    <label>Descripción</label>
                    <textarea name="descripcion_tecnologia" rows="3"><?php echo htmlspecialchars($tecnologia['descripcion_tecnologia'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group full-width">
                    <label>Subir Logo <?php echo ($action === 'create') ? '' : '(opcional)'; ?></label>
                    <input type="file" name="logo_tecnologia_file" id="logo_tecnologia_file" accept="image/jpeg,image/png,image/gif,image/webp,image/svg+xml" onchange="previewImage(this)">
                    <small style="display: block; margin-top: 5px; color: #7F8C8D;">Formatos: JPG, PNG, GIF, WEBP, SVG (máx 2MB)</small>
                </div>
                
                <div class="form-group full-width">
                    <label>O usar URL de Logo</label>
                    <input type="text" name="logo_tecnologia" id="logo_tecnologia_url" value="<?php echo htmlspecialchars($tecnologia['logo_tecnologia'] ?? ''); ?>" placeholder="https://...">
                </div>
                
                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado_tecnologia">
                        <option value="1" <?php echo ($tecnologia['estado_tecnologia'] ?? 1) == 1 ? 'selected' : ''; ?>>Activo</option>
                        <option value="0" <?php echo ($tecnologia['estado_tecnologia'] ?? 1) == 0 ? 'selected' : ''; ?>>Inactivo</option>
                    </select>
                </div>
            </div>
            
            <div id="imagePreview" style="margin: 20px 0; display: none;">
                <label>Vista Previa:</label>
                <div style="margin-top: 10px; border: 2px solid #E0E0E0; border-radius: 8px; overflow: hidden; max-width: 200px;">
                    <img id="previewImg" src="" alt="Preview" style="width: 100%; display: block; max-height: 200px; object-fit: contain;">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                <a href="<?php echo URL_SERV; ?>/dw-panel/tecnologias" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>
<?php else: ?>
    <div class="filters-card">
        <form method="GET" class="filters-form">
            <div class="filters-grid">
                <div class="filter-group">
                    <label>Buscar:</label>
                    <input type="text" name="buscar" value="<?php echo htmlspecialchars($_GET['buscar'] ?? ''); ?>" placeholder="Nombre de tecnología...">
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
                    <a href="<?php echo URL_SERV; ?>/dw-panel/tecnologias" class="btn btn-secondary"><i class="fas fa-redo"></i> Limpiar</a>
                    <label style="margin-left: 15px;">
                        <input type="checkbox" name="mostrar_inactivos" <?php echo isset($_GET['mostrar_inactivos']) ? 'checked' : ''; ?> onchange="this.form.submit()">
                        Mostrar inactivos
                    </label>
                </div>
            </div>
        </form>
    </div>
    <div class="card">
        <div class="card-header"><h2>Tecnologías (<?php echo count($tecnologias); ?>)</h2></div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Logo</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Orden</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tecnologias)): ?>
                        <tr>
                            <td colspan="7" class="text-center">No hay tecnologías registradas</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($tecnologias as $t): ?>
                            <tr>
                                <td>#<?php echo $t['id_tecnologia']; ?></td>
                                <td>
                                    <?php if (!empty($t['logo_tecnologia'])): ?>
                                        <img src="<?php echo htmlspecialchars($t['logo_tecnologia']); ?>" alt="<?php echo htmlspecialchars($t['nombre_tecnologia']); ?>" style="width: 50px; height: 50px; object-fit: contain;">
                                    <?php else: ?>
                                        <span style="color: #BDC3C7;">Sin logo</span>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo htmlspecialchars($t['nombre_tecnologia']); ?></strong></td>
                                <td><?php echo ucfirst($t['categoria_tecnologia'] ?? '-'); ?></td>
                                <td><?php echo $t['orden_tecnologia']; ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $t['estado_tecnologia'] == 1 ? 'success' : 'danger'; ?>">
                                        <?php echo $t['estado_tecnologia'] == 1 ? 'Activo' : 'Inactivo'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?php echo URL_SERV; ?>/dw-panel/tecnologias?action=edit&id=<?php echo $t['id_tecnologia']; ?>" class="btn-icon" title="Editar"><i class="fas fa-edit"></i></a>
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar tecnología?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $t['id_tecnologia']; ?>">
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

<?php if (isset($tecnologia) && !empty($tecnologia['logo_tecnologia'])): ?>
document.addEventListener('DOMContentLoaded', function() {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    if (preview && previewImg) {
        previewImg.src = '<?php echo htmlspecialchars($tecnologia['logo_tecnologia']); ?>';
        preview.style.display = 'block';
    }
});
<?php endif; ?>
</script>


