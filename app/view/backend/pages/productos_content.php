<?php
?>
<div class="page-header">
    <div class="header-actions">
        <a href="<?php echo URL_SERV; ?>/dw-panel/productos?action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Producto
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
        <h2><?php echo $action === 'create' ? 'Nuevo Producto' : 'Editar Producto'; ?></h2>
        <form method="POST" action="">
            <input type="hidden" name="action" value="<?php echo $action; ?>">
            <?php if ($action === 'edit'): ?><input type="hidden" name="id" value="<?php echo $producto['id']; ?>"><?php endif; ?>
            
            <div class="form-grid">
                <div class="form-group"><label>Categoría *</label>
                    <select name="categoria_id" required>
                        <option value="">Seleccione</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo ($producto && $producto['categoria_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Nombre *</label><input type="text" name="nombre" value="<?php echo $producto['nombre'] ?? ''; ?>" required></div>
                <div class="form-group"><label>Código de Barras</label><input type="text" name="codigo_barras" value="<?php echo $producto['codigo_barras'] ?? ''; ?>"></div>
                <div class="form-group"><label>Stock Actual *</label><input type="number" name="stock_actual" value="<?php echo $producto['stock_actual'] ?? '0'; ?>" required></div>
                <div class="form-group"><label>Stock Mínimo *</label><input type="number" name="stock_minimo" value="<?php echo $producto['stock_minimo'] ?? '0'; ?>" required></div>
                <div class="form-group"><label>Precio Compra</label><input type="number" name="precio_compra" step="0.01" value="<?php echo $producto['precio_compra'] ?? '0'; ?>"></div>
                <div class="form-group"><label>Precio Venta</label><input type="number" name="precio_venta" step="0.01" value="<?php echo $producto['precio_venta'] ?? '0'; ?>"></div>
                <div class="form-group"><label>Proveedor</label><input type="text" name="proveedor" value="<?php echo $producto['proveedor'] ?? ''; ?>"></div>
                <div class="form-group"><label>Fecha Vencimiento</label><input type="date" name="fecha_vencimiento" value="<?php echo $producto['fecha_vencimiento'] ?? ''; ?>"></div>
                <div class="form-group"><label>Unidad Medida</label><input type="text" name="unidad_medida" value="<?php echo $producto['unidad_medida'] ?? 'unidad'; ?>"></div>
            </div>
            <div class="form-group"><label>Descripción</label><textarea name="descripcion" rows="4"><?php echo $producto['descripcion'] ?? ''; ?></textarea></div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                <a href="<?php echo URL_SERV; ?>/dw-panel/productos" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>
<?php else: ?>
    <div class="filters-card">
        <form method="GET" class="filters-form">
            <div class="filters-grid">
                <div class="filter-group">
                    <label>Buscar:</label>
                    <input type="text" name="buscar" value="<?php echo htmlspecialchars($filters['buscar']); ?>" placeholder="Nombre del producto...">
                </div>
                <div class="filter-group">
                    <label>
                        <input type="checkbox" name="stock_bajo" <?php echo isset($filters['stock_bajo']) ? 'checked' : ''; ?>>
                        Solo stock bajo
                    </label>
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
                    <a href="<?php echo URL_SERV; ?>/dw-panel/productos" class="btn btn-secondary"><i class="fas fa-redo"></i> Limpiar</a>
                </div>
            </div>
        </form>
    </div>
    <div class="card">
        <div class="card-header"><h2>Productos (<?php echo count($productos); ?>)</h2></div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr><th>ID</th><th>Nombre</th><th>Categoría</th><th>Stock</th><th>Stock Mín</th><th>Precio Venta</th><th>Estado</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                    <?php if (empty($productos)): ?>
                        <tr><td colspan="8" class="text-center">No hay productos registrados</td></tr>
                    <?php else: ?>
                        <?php foreach ($productos as $p): ?>
                            <tr>
                                <td>#<?php echo $p['id']; ?></td>
                                <td><?php echo htmlspecialchars($p['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($p['categoria_nombre'] ?? '-'); ?></td>
                                <td>
                                    <span style="color: <?php echo $p['stock_actual'] <= $p['stock_minimo'] ? '#E74C3C' : '#27AE60'; ?>; font-weight: 600;">
                                        <?php echo $p['stock_actual']; ?>
                                    </span>
                                </td>
                                <td><?php echo $p['stock_minimo']; ?></td>
                                <td><?php echo formatPrice($p['precio_venta']); ?></td>
                                <td>
                                    <?php if ($p['stock_actual'] <= $p['stock_minimo']): ?>
                                        <span class="badge badge-cancelada">Stock Bajo</span>
                                    <?php elseif ($p['stock_actual'] == 0): ?>
                                        <span class="badge badge-cancelada">Agotado</span>
                                    <?php else: ?>
                                        <span class="badge badge-confirmada">Disponible</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?php echo URL_SERV; ?>/dw-panel/productos?action=edit&id=<?php echo $p['id']; ?>" class="btn-icon" title="Editar"><i class="fas fa-edit"></i></a>
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar producto?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
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

