<?php
?>
<div class="page-header">
    <div class="header-actions">
        <a href="<?php echo URL_SERV; ?>/dw-panel/empleados?action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Empleado
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
        <h2><?php echo $action === 'create' ? 'Nuevo Empleado' : 'Editar Empleado'; ?></h2>
        <form method="POST" action="">
            <input type="hidden" name="action" value="<?php echo $action; ?>">
            <?php if ($action === 'edit'): ?><input type="hidden" name="id" value="<?php echo $empleado['id']; ?>"><?php endif; ?>
            
            <div class="form-grid">
                <div class="form-group"><label>Nombre *</label><input type="text" name="nombre" value="<?php echo $empleado['nombre'] ?? ''; ?>" required></div>
                <div class="form-group"><label>Apellido *</label><input type="text" name="apellido" value="<?php echo $empleado['apellido'] ?? ''; ?>" required></div>
                <div class="form-group"><label>Email</label><input type="email" name="email" value="<?php echo $empleado['email'] ?? ''; ?>"></div>
                <div class="form-group"><label>Teléfono *</label><input type="tel" name="telefono" value="<?php echo $empleado['telefono'] ?? ''; ?>" required></div>
                <div class="form-group"><label>Especialidad</label><input type="text" name="especialidad" value="<?php echo $empleado['especialidad'] ?? ''; ?>"></div>
                <div class="form-group"><label>% Comisión</label><input type="number" name="porcentaje_comision" step="0.01" value="<?php echo $empleado['porcentaje_comision'] ?? '0'; ?>"></div>
                <div class="form-group"><label>Salario Base</label><input type="number" name="salario_base" step="0.01" value="<?php echo $empleado['salario_base'] ?? '0'; ?>"></div>
                <div class="form-group"><label>Fecha Ingreso</label><input type="date" name="fecha_ingreso" value="<?php echo $empleado['fecha_ingreso'] ?? ''; ?>"></div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                <a href="<?php echo URL_SERV; ?>/dw-panel/empleados" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>
<?php else: ?>
    <div class="filters-card">
        <form method="GET" class="filters-form">
            <div class="filters-grid">
                <div class="filter-group">
                    <label>Buscar:</label>
                    <input type="text" name="buscar" value="<?php echo htmlspecialchars($filters['buscar']); ?>" placeholder="Nombre, email...">
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
                    <a href="<?php echo URL_SERV; ?>/dw-panel/empleados" class="btn btn-secondary"><i class="fas fa-redo"></i> Limpiar</a>
                </div>
            </div>
        </form>
    </div>
    <div class="card">
        <div class="card-header"><h2>Empleados (<?php echo count($empleados); ?>)</h2></div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr><th>ID</th><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Especialidad</th><th>% Comisión</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                    <?php if (empty($empleados)): ?>
                        <tr><td colspan="7" class="text-center">No hay empleados registrados</td></tr>
                    <?php else: ?>
                        <?php foreach ($empleados as $e): ?>
                            <tr>
                                <td>#<?php echo $e['id']; ?></td>
                                <td><?php echo htmlspecialchars($e['nombre'] . ' ' . $e['apellido']); ?></td>
                                <td><?php echo htmlspecialchars($e['email'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($e['telefono']); ?></td>
                                <td><?php echo htmlspecialchars($e['especialidad'] ?? '-'); ?></td>
                                <td><?php echo number_format($e['porcentaje_comision'], 2); ?>%</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?php echo URL_SERV; ?>/dw-panel/empleados?action=edit&id=<?php echo $e['id']; ?>" class="btn-icon" title="Editar"><i class="fas fa-edit"></i></a>
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar empleado?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $e['id']; ?>">
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

