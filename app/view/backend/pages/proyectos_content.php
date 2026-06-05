<?php
/**
 * Contenido de Gestión de Proyectos
 * DevSoft Build SAC
 */
?>
<div class="page-header">
    <div class="header-actions">
        <a href="<?php echo URL_SERV; ?>/dw-panel/proyectos?action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Proyecto
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
        <h2><?php echo $action === 'create' ? 'Nuevo Proyecto' : 'Editar Proyecto'; ?></h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?php echo $action === 'create' ? 'create' : 'update'; ?>">
            <?php if ($action === 'edit'): ?><input type="hidden" name="id" value="<?php echo $proyecto['id_proyecto']; ?>"><?php endif; ?>
            
            <div class="form-grid">
                <div class="form-group full-width">
                    <label>Nombre del Proyecto *</label>
                    <input type="text" name="nombre_proyecto" value="<?php echo htmlspecialchars($proyecto['nombre_proyecto'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group full-width">
                    <label>Slug (URL amigable)</label>
                    <input type="text" name="slug_proyecto" value="<?php echo htmlspecialchars($proyecto['slug_proyecto'] ?? ''); ?>" placeholder="Se genera automáticamente si se deja vacío">
                </div>
                
                <div class="form-group full-width">
                    <label>Descripción Corta</label>
                    <textarea name="descripcion_corta_proyecto" rows="2"><?php echo htmlspecialchars($proyecto['descripcion_corta_proyecto'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group full-width">
                    <label>Descripción Larga</label>
                    <textarea name="descripcion_larga_proyecto" rows="6"><?php echo htmlspecialchars($proyecto['descripcion_larga_proyecto'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Tipo de Proyecto</label>
                    <select name="tipo_proyecto">
                        <option value="web" <?php echo ($proyecto['tipo_proyecto'] ?? '') === 'web' ? 'selected' : ''; ?>>Web</option>
                        <option value="mobile" <?php echo ($proyecto['tipo_proyecto'] ?? '') === 'mobile' ? 'selected' : ''; ?>>App Móvil</option>
                        <option value="ecommerce" <?php echo ($proyecto['tipo_proyecto'] ?? '') === 'ecommerce' ? 'selected' : ''; ?>>E-commerce</option>
                        <option value="otro" <?php echo ($proyecto['tipo_proyecto'] ?? '') === 'otro' ? 'selected' : ''; ?>>Otro</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Cliente</label>
                    <input type="text" name="cliente_proyecto" value="<?php echo htmlspecialchars($proyecto['cliente_proyecto'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>Fecha Inicio</label>
                    <input type="date" name="fecha_inicio_proyecto" value="<?php echo $proyecto['fecha_inicio_proyecto'] ?? ''; ?>">
                </div>
                
                <div class="form-group">
                    <label>Fecha Fin</label>
                    <input type="date" name="fecha_fin_proyecto" value="<?php echo $proyecto['fecha_fin_proyecto'] ?? ''; ?>">
                </div>
                
                <div class="form-group">
                    <label>URL del Proyecto</label>
                    <input type="url" name="url_proyecto" value="<?php echo htmlspecialchars($proyecto['url_proyecto'] ?? ''); ?>" placeholder="https://...">
                </div>
                
                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado_proyecto">
                        <option value="en_proceso" <?php echo ($proyecto['estado_proyecto'] ?? 'en_proceso') === 'en_proceso' ? 'selected' : ''; ?>>En Proceso</option>
                        <option value="completado" <?php echo ($proyecto['estado_proyecto'] ?? '') === 'completado' ? 'selected' : ''; ?>>Completado</option>
                        <option value="cancelado" <?php echo ($proyecto['estado_proyecto'] ?? '') === 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Orden</label>
                    <input type="number" name="orden_proyecto" value="<?php echo $proyecto['orden_proyecto'] ?? 0; ?>">
                </div>
                
                <div class="form-group">
                    <label>Destacado</label>
                    <select name="destacado_proyecto">
                        <option value="0" <?php echo ($proyecto['destacado_proyecto'] ?? 0) == 0 ? 'selected' : ''; ?>>No</option>
                        <option value="1" <?php echo ($proyecto['destacado_proyecto'] ?? 0) == 1 ? 'selected' : ''; ?>>Sí</option>
                    </select>
                </div>
                
                <div class="form-group full-width">
                    <label>Subir Imagen <?php echo ($action === 'create') ? '' : '(opcional)'; ?></label>
                    <input type="file" name="imagen_proyecto_file" id="imagen_proyecto_file" accept="image/jpeg,image/png,image/gif,image/webp" onchange="previewImage(this)">
                    <small style="display: block; margin-top: 5px; color: #7F8C8D;">Formatos: JPG, PNG, GIF, WEBP (máx 5MB)</small>
                </div>
                
                <div class="form-group full-width">
                    <label>O usar URL de Imagen</label>
                    <input type="text" name="imagen_proyecto" id="imagen_proyecto_url" value="<?php echo htmlspecialchars($proyecto['imagen_principal_proyecto'] ?? $proyecto['imagen_proyecto'] ?? ''); ?>" placeholder="https://...">
                </div>
            </div>
            
            <div class="form-section">
                <h3>SEO</h3>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Meta Título</label>
                        <input type="text" name="meta_titulo_proyecto" value="<?php echo htmlspecialchars($proyecto['meta_titulo_proyecto'] ?? ''); ?>" maxlength="255">
                    </div>
                    <div class="form-group full-width">
                        <label>Meta Descripción</label>
                        <textarea name="meta_descripcion_proyecto" rows="2" maxlength="500"><?php echo htmlspecialchars($proyecto['meta_descripcion_proyecto'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group full-width">
                        <label>Meta Keywords</label>
                        <input type="text" name="meta_keywords_proyecto" value="<?php echo htmlspecialchars($proyecto['meta_keywords_proyecto'] ?? ''); ?>" placeholder="palabra1, palabra2, palabra3">
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
                <a href="<?php echo URL_SERV; ?>/dw-panel/proyectos" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>
<?php else: ?>
    <div class="filters-card">
        <form method="GET" class="filters-form">
            <div class="filters-grid">
                <div class="filter-group">
                    <label>Buscar:</label>
                    <input type="text" name="buscar" value="<?php echo htmlspecialchars($_GET['buscar'] ?? ''); ?>" placeholder="Nombre del proyecto...">
                </div>
                <div class="filter-group">
                    <label>Estado:</label>
                    <select name="estado">
                        <option value="">Todos</option>
                        <option value="en_proceso" <?php echo ($_GET['estado'] ?? '') === 'en_proceso' ? 'selected' : ''; ?>>En Proceso</option>
                        <option value="completado" <?php echo ($_GET['estado'] ?? '') === 'completado' ? 'selected' : ''; ?>>Completado</option>
                        <option value="cancelado" <?php echo ($_GET['estado'] ?? '') === 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                    </select>
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
                    <a href="<?php echo URL_SERV; ?>/dw-panel/proyectos" class="btn btn-secondary"><i class="fas fa-redo"></i> Limpiar</a>
                </div>
            </div>
        </form>
    </div>
    <div class="card">
        <div class="card-header"><h2>Proyectos (<?php echo count($proyectos); ?>)</h2></div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Cliente</th>
                        <th>Estado</th>
                        <th>Destacado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($proyectos)): ?>
                        <tr>
                            <td colspan="8" class="text-center">No hay proyectos registrados</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($proyectos as $p): ?>
                            <tr>
                                <td>#<?php echo $p['id_proyecto']; ?></td>
                                <td>
                                    <?php 
                                    $imagen_proyecto = $p['imagen_principal_proyecto'] ?? $p['imagen_proyecto'] ?? '';
                                    if (!empty($imagen_proyecto)): ?>
                                        <img src="<?php echo htmlspecialchars($imagen_proyecto); ?>" alt="<?php echo htmlspecialchars($p['nombre_proyecto']); ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                    <?php else: ?>
                                        <span style="color: #BDC3C7;">Sin imagen</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($p['nombre_proyecto']); ?></strong>
                                    <?php if (!empty($p['descripcion_corta_proyecto'])): ?>
                                        <br><small style="color: #7F8C8D;"><?php echo truncateText($p['descripcion_corta_proyecto'], 50); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo ucfirst($p['tipo_proyecto'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($p['cliente_proyecto'] ?? '-'); ?></td>
                                <td>
                                    <span class="badge badge-<?php 
                                        echo $p['estado_proyecto'] === 'completado' ? 'success' : 
                                            ($p['estado_proyecto'] === 'en_proceso' ? 'warning' : 'danger'); 
                                    ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $p['estado_proyecto'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($p['destacado_proyecto'] == 1): ?>
                                        <span class="badge badge-warning"><i class="fas fa-star"></i> Destacado</span>
                                    <?php else: ?>
                                        <span style="color: #BDC3C7;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?php echo URL_SERV; ?>/dw-panel/proyectos?action=edit&id=<?php echo $p['id_proyecto']; ?>" class="btn-icon" title="Editar"><i class="fas fa-edit"></i></a>
                                        <a href="<?php echo htmlspecialchars(proyecto_url_ficha($p)); ?>" target="_blank" rel="noopener noreferrer" class="btn-icon" title="Ver en sitio"><i class="fas fa-eye"></i></a>
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar proyecto?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $p['id_proyecto']; ?>">
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

<?php 
$imagen_actual = $proyecto['imagen_principal_proyecto'] ?? $proyecto['imagen_proyecto'] ?? '';
if (isset($proyecto) && !empty($imagen_actual)): ?>
document.addEventListener('DOMContentLoaded', function() {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    if (preview && previewImg) {
        previewImg.src = '<?php echo htmlspecialchars($imagen_actual); ?>';
        preview.style.display = 'block';
    }
});
<?php endif; ?>
</script>

