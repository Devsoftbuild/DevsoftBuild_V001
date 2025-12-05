<?php
?>
<div class="page-header">
    <div class="header-actions">
        <a href="<?php echo URL_SERV; ?>/dw-panel/promociones?action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Promoción
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
        <h2><?php echo $action === 'create' ? 'Nueva Promoción' : 'Editar Promoción'; ?></h2>
        <form method="POST" action="">
            <input type="hidden" name="action" value="<?php echo $action; ?>">
            <?php if ($action === 'edit'): ?><input type="hidden" name="id" value="<?php echo $promocion['id']; ?>"><?php endif; ?>
            
            <div class="form-grid">
                <div class="form-group"><label>Nombre *</label><input type="text" name="nombre" value="<?php echo $promocion['nombre'] ?? ''; ?>" required></div>
                <div class="form-group"><label>Tipo *</label>
                    <select name="tipo" required>
                        <option value="porcentaje" <?php echo ($promocion['tipo'] ?? '') === 'porcentaje' ? 'selected' : ''; ?>>Porcentaje</option>
                        <option value="monto_fijo" <?php echo ($promocion['tipo'] ?? '') === 'monto_fijo' ? 'selected' : ''; ?>>Monto Fijo</option>
                    </select>
                </div>
                <div class="form-group"><label>Valor *</label><input type="number" name="valor" step="0.01" value="<?php echo $promocion['valor'] ?? '0'; ?>" required></div>
                <div class="form-group"><label>Código</label><input type="text" name="codigo" value="<?php echo $promocion['codigo'] ?? ''; ?>" placeholder="Código promocional"></div>
                <div class="form-group"><label>Servicio</label>
                    <select name="servicio_id">
                        <option value="">Todos los servicios</option>
                        <?php foreach ($servicios as $s): ?>
                            <option value="<?php echo $s['id']; ?>" <?php echo ($promocion && $promocion['servicio_id'] == $s['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($s['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Categoría</label>
                    <select name="categoria_servicio_id">
                        <option value="">Todas las categorías</option>
                        <?php foreach ($categorias as $c): ?>
                            <option value="<?php echo $c['id']; ?>" <?php echo ($promocion && $promocion['categoria_servicio_id'] == $c['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($c['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Fecha Inicio *</label><input type="date" name="fecha_inicio" value="<?php echo $promocion['fecha_inicio'] ?? date('Y-m-d'); ?>" required></div>
                <div class="form-group"><label>Fecha Fin *</label><input type="date" name="fecha_fin" value="<?php echo $promocion['fecha_fin'] ?? date('Y-m-d', strtotime('+1 month')); ?>" required></div>
                <div class="form-group"><label>Uso Máximo</label><input type="number" name="uso_maximo" value="<?php echo $promocion['uso_maximo'] ?? ''; ?>" placeholder="Sin límite"></div>
                <div class="form-group"><label>Activa</label>
                    <select name="activo">
                        <option value="1" <?php echo ($promocion['activo'] ?? 1) ? 'selected' : ''; ?>>Sí</option>
                        <option value="0" <?php echo ($promocion['activo'] ?? 1) ? '' : 'selected'; ?>>No</option>
                    </select>
                </div>
            </div>
            <div class="form-group"><label>Descripción</label><textarea name="descripcion" rows="4"><?php echo $promocion['descripcion'] ?? ''; ?></textarea></div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                <a href="<?php echo URL_SERV; ?>/dw-panel/promociones" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-header"><h2>Promociones (<?php echo count($promociones); ?>)</h2></div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr><th>ID</th><th>Nombre</th><th>Tipo</th><th>Valor</th><th>Código</th><th>Válida Desde</th><th>Válida Hasta</th><th>Uso</th><th>Estado</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                    <?php if (empty($promociones)): ?>
                        <tr><td colspan="10" class="text-center">No hay promociones registradas</td></tr>
                    <?php else: ?>
                        <?php foreach ($promociones as $p): ?>
                            <tr>
                                <td>#<?php echo $p['id']; ?></td>
                                <td><?php echo htmlspecialchars($p['nombre']); ?></td>
                                <td><?php echo $p['tipo'] === 'porcentaje' ? '%' : '$'; ?></td>
                                <td><?php echo number_format($p['valor'], 2); ?></td>
                                <td><?php echo htmlspecialchars($p['codigo'] ?? '-'); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($p['fecha_inicio'])); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($p['fecha_fin'])); ?></td>
                                <td><?php echo $p['uso_actual']; ?>/<?php echo $p['uso_maximo'] ?? '∞'; ?></td>
                                <td>
                                    <?php 
                                    $hoy = date('Y-m-d');
                                    $activa = $p['activo'] && $p['fecha_inicio'] <= $hoy && $p['fecha_fin'] >= $hoy;
                                    ?>
                                    <span class="badge badge-<?php echo $activa ? 'confirmada' : 'cancelada'; ?>">
                                        <?php echo $activa ? 'Activa' : 'Inactiva'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?php echo URL_SERV; ?>/dw-panel/promociones?action=edit&id=<?php echo $p['id']; ?>" class="btn-icon" title="Editar"><i class="fas fa-edit"></i></a>
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar promoción?');">
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

