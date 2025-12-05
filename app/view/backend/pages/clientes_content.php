<?php
?>
<div class="page-header">
    <div class="header-actions">
        <a href="<?php echo URL_SERV; ?>/dw-panel/clientes?action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Cliente
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
        <h2><?php echo $action === 'create' ? 'Nuevo Cliente' : 'Editar Cliente'; ?></h2>
        <form method="POST" action="">
            <input type="hidden" name="action" value="<?php echo $action; ?>">
            <?php if ($action === 'edit'): ?><input type="hidden" name="id" value="<?php echo $cliente['id']; ?>"><?php endif; ?>
            
            <div class="form-grid">
                <div class="form-group"><label>Nombre *</label><input type="text" name="nombre" value="<?php echo $cliente['nombre'] ?? ''; ?>" required></div>
                <div class="form-group"><label>Apellido *</label><input type="text" name="apellido" value="<?php echo $cliente['apellido'] ?? ''; ?>" required></div>
                <div class="form-group"><label>Email</label><input type="email" name="email" value="<?php echo $cliente['email'] ?? ''; ?>"></div>
                <div class="form-group"><label>Teléfono *</label><input type="tel" name="telefono" value="<?php echo $cliente['telefono'] ?? ''; ?>" required></div>
                <div class="form-group"><label>Fecha Nacimiento</label><input type="date" name="fecha_nacimiento" value="<?php echo $cliente['fecha_nacimiento'] ?? ''; ?>"></div>
                <div class="form-group"><label>Género</label>
                    <select name="genero">
                        <option value="">Seleccione</option>
                        <option value="M" <?php echo ($cliente['genero'] ?? '') === 'M' ? 'selected' : ''; ?>>Masculino</option>
                        <option value="F" <?php echo ($cliente['genero'] ?? '') === 'F' ? 'selected' : ''; ?>>Femenino</option>
                        <option value="O" <?php echo ($cliente['genero'] ?? '') === 'O' ? 'selected' : ''; ?>>Otro</option>
                    </select>
                </div>
                <div class="form-group"><label>Dirección</label><input type="text" name="direccion" value="<?php echo $cliente['direccion'] ?? ''; ?>"></div>
                <div class="form-group"><label>Ciudad</label><input type="text" name="ciudad" value="<?php echo $cliente['ciudad'] ?? ''; ?>"></div>
                <div class="form-group"><label>Código Postal</label><input type="text" name="codigo_postal" value="<?php echo $cliente['codigo_postal'] ?? ''; ?>"></div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                <a href="<?php echo URL_SERV; ?>/dw-panel/clientes" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>
<?php elseif ($action === 'view' && $cliente): ?>
    <div class="card">
        <div class="card-header">
            <h2>Cliente: <?php echo htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']); ?></h2>
            <a href="<?php echo URL_SERV; ?>/dw-panel/clientes?action=edit&id=<?php echo $cliente['id']; ?>" class="btn btn-secondary"><i class="fas fa-edit"></i> Editar</a>
        </div>
        <div class="info-grid">
            <div class="info-item"><label>Nombre:</label><span><?php echo htmlspecialchars($cliente['nombre']); ?></span></div>
            <div class="info-item"><label>Apellido:</label><span><?php echo htmlspecialchars($cliente['apellido']); ?></span></div>
            <div class="info-item"><label>Email:</label><span><?php echo htmlspecialchars($cliente['email'] ?? '-'); ?></span></div>
            <div class="info-item"><label>Teléfono:</label><span><?php echo htmlspecialchars($cliente['telefono']); ?></span></div>
            <div class="info-item"><label>Fecha Registro:</label><span><?php echo date('d/m/Y', strtotime($cliente['fecha_registro'])); ?></span></div>
        </div>
    </div>
<?php else: ?>
    <div class="filters-card">
        <form method="GET" class="filters-form">
            <div class="filters-grid">
                <div class="filter-group">
                    <label>Buscar:</label>
                    <input type="text" name="buscar" value="<?php echo htmlspecialchars($filters['buscar']); ?>" placeholder="Nombre, teléfono, email...">
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
                    <a href="<?php echo URL_SERV; ?>/dw-panel/clientes" class="btn btn-secondary"><i class="fas fa-redo"></i> Limpiar</a>
                </div>
            </div>
        </form>
    </div>
    <div class="card">
        <div class="card-header"><h2>Clientes (<?php echo count($clientes); ?>)</h2></div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr><th>ID</th><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Fecha Registro</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                    <?php if (empty($clientes)): ?>
                        <tr><td colspan="6" class="text-center">No hay clientes registrados</td></tr>
                    <?php else: ?>
                        <?php foreach ($clientes as $c): ?>
                            <tr>
                                <td>#<?php echo $c['id']; ?></td>
                                <td><?php echo htmlspecialchars($c['nombre'] . ' ' . $c['apellido']); ?></td>
                                <td><?php echo htmlspecialchars($c['email'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($c['telefono']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($c['fecha_registro'])); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?php echo URL_SERV; ?>/dw-panel/clientes?action=view&id=<?php echo $c['id']; ?>" class="btn-icon" title="Ver"><i class="fas fa-eye"></i></a>
                                        <a href="<?php echo URL_SERV; ?>/dw-panel/clientes?action=edit&id=<?php echo $c['id']; ?>" class="btn-icon" title="Editar"><i class="fas fa-edit"></i></a>
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar cliente?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
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

