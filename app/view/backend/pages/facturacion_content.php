<?php
?>
<div class="page-header">
    <div class="header-actions">
        <a href="<?php echo URL_SERV; ?>/dw-panel/facturacion?action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Factura
        </a>
    </div>
</div>

<?php if ($message): ?>
    <div class="alert alert-<?php echo $message_type; ?>">
        <i class="fas fa-<?php echo $message_type === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<?php if ($action === 'create'): ?>
    <div class="card">
        <h2>Nueva Factura</h2>
        <form method="POST" action="">
            <input type="hidden" name="action" value="create">
            <div class="form-grid">
                <div class="form-group"><label>Cliente *</label>
                    <select name="cliente_id" required>
                        <option value="">Seleccione</option>
                        <?php foreach ($clientes as $c): ?>
                            <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['nombre'] . ' ' . $c['apellido']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Cita (opcional)</label>
                    <select name="cita_id">
                        <option value="">Sin cita</option>
                        <?php foreach ($citas as $cit): ?>
                            <option value="<?php echo $cit['id']; ?>">Cita #<?php echo $cit['id']; ?> - <?php echo date('d/m/Y H:i', strtotime($cit['fecha'] . ' ' . $cit['hora'])); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Fecha *</label><input type="date" name="fecha" value="<?php echo date('Y-m-d'); ?>" required></div>
                <div class="form-group"><label>Subtotal *</label><input type="number" name="subtotal" step="0.01" required></div>
                <div class="form-group"><label>Descuento</label><input type="number" name="descuento" step="0.01" value="0"></div>
                <div class="form-group"><label>Impuesto</label><input type="number" name="impuesto" step="0.01" value="0"></div>
                <div class="form-group"><label>Total *</label><input type="number" name="total" step="0.01" required></div>
                <div class="form-group"><label>Estado *</label>
                    <select name="estado" required>
                        <option value="pendiente">Pendiente</option>
                        <option value="pagada">Pagada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                <a href="<?php echo URL_SERV; ?>/dw-panel/facturacion" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>
<?php else: ?>
    <div class="filters-card">
        <form method="GET" class="filters-form">
            <div class="filters-grid">
                <div class="filter-group">
                    <label>Buscar:</label>
                    <input type="text" name="buscar" value="<?php echo htmlspecialchars($filters['buscar']); ?>" placeholder="Número de factura...">
                </div>
                <div class="filter-group">
                    <label>Estado:</label>
                    <select name="estado">
                        <option value="">Todos</option>
                        <option value="pendiente" <?php echo $filters['estado'] === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                        <option value="pagada" <?php echo $filters['estado'] === 'pagada' ? 'selected' : ''; ?>>Pagada</option>
                        <option value="cancelada" <?php echo $filters['estado'] === 'cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                    </select>
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
                    <a href="<?php echo URL_SERV; ?>/dw-panel/facturacion" class="btn btn-secondary"><i class="fas fa-redo"></i> Limpiar</a>
                </div>
            </div>
        </form>
    </div>
    <div class="card">
        <div class="card-header"><h2>Facturas (<?php echo count($facturas); ?>)</h2></div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr><th>Número</th><th>Fecha</th><th>Cliente</th><th>Subtotal</th><th>Descuento</th><th>Total</th><th>Estado</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                    <?php if (empty($facturas)): ?>
                        <tr><td colspan="8" class="text-center">No hay facturas registradas</td></tr>
                    <?php else: ?>
                        <?php foreach ($facturas as $f): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($f['numero_factura']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($f['fecha'])); ?></td>
                                <td><?php echo htmlspecialchars($f['cliente_nombre'] . ' ' . $f['cliente_apellido']); ?></td>
                                <td><?php echo formatPrice($f['subtotal']); ?></td>
                                <td><?php echo formatPrice($f['descuento']); ?></td>
                                <td><strong><?php echo formatPrice($f['total']); ?></strong></td>
                                <td>
                                    <span class="badge badge-<?php echo $f['estado'] === 'pagada' ? 'confirmada' : ($f['estado'] === 'cancelada' ? 'cancelada' : 'pendiente'); ?>">
                                        <?php echo ucfirst($f['estado']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="action" value="change_status">
                                            <input type="hidden" name="id" value="<?php echo $f['id']; ?>">
                                            <input type="hidden" name="estado" value="pagada">
                                            <button type="submit" class="btn-icon" title="Marcar como pagada"><i class="fas fa-check"></i></button>
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

