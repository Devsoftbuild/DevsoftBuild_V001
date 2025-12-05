<?php
/**
 * Contenido de Gestión de Citas
 */
?>

<div class="page-header">
    <div class="header-actions">
        <a href="<?php echo URL_SERV; ?>/dw-panel/citas?action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Cita
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
    <!-- Formulario de Cita -->
    <div class="card">
        <h2><?php echo $action === 'create' ? 'Nueva Cita' : 'Editar Cita'; ?></h2>
        
        <form method="POST" action="" class="form-cita">
            <input type="hidden" name="action" value="<?php echo $action === 'create' ? 'create' : 'update'; ?>">
            <?php if ($action === 'edit'): ?>
                <input type="hidden" name="id" value="<?php echo $cita['id']; ?>">
            <?php endif; ?>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="cliente_id">Cliente *</label>
                    <select name="cliente_id" id="cliente_id" required>
                        <option value="">Seleccione un cliente</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?php echo $cliente['id']; ?>" 
                                    <?php echo ($cita && $cita['cliente_id'] == $cliente['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido'] . ' - ' . $cliente['telefono']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="servicio_id">Servicio *</label>
                    <select name="servicio_id" id="servicio_id" required>
                        <option value="">Seleccione un servicio</option>
                        <?php foreach ($servicios as $servicio): ?>
                            <option value="<?php echo $servicio['id']; ?>" 
                                    data-precio="<?php echo $servicio['precio']; ?>"
                                    data-duracion="<?php echo $servicio['duracion_minutos']; ?>"
                                    <?php echo ($cita && $cita['servicio_id'] == $servicio['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($servicio['nombre'] . ' - ' . formatPrice($servicio['precio'])); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="empleado_id">Empleado</label>
                    <select name="empleado_id" id="empleado_id">
                        <option value="">Sin asignar</option>
                        <?php foreach ($empleados as $empleado): ?>
                            <option value="<?php echo $empleado['id']; ?>" 
                                    <?php echo ($cita && $cita['empleado_id'] == $empleado['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($empleado['nombre'] . ' ' . $empleado['apellido']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="fecha">Fecha *</label>
                    <input type="date" name="fecha" id="fecha" 
                           value="<?php echo $cita ? $cita['fecha'] : date('Y-m-d'); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="hora">Hora *</label>
                    <input type="time" name="hora" id="hora" 
                           value="<?php echo $cita ? $cita['hora'] : '09:00'; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="duracion_minutos">Duración (minutos) *</label>
                    <input type="number" name="duracion_minutos" id="duracion_minutos" 
                           value="<?php echo $cita ? $cita['duracion_minutos'] : '60'; ?>" 
                           min="15" step="15" required>
                </div>
                
                <div class="form-group">
                    <label for="precio">Precio *</label>
                    <input type="number" name="precio" id="precio" step="0.01" 
                           value="<?php echo $cita ? $cita['precio'] : '0'; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="descuento">Descuento</label>
                    <input type="number" name="descuento" id="descuento" step="0.01" 
                           value="<?php echo $cita ? $cita['descuento'] : '0'; ?>">
                </div>
                
                <div class="form-group">
                    <label for="estado">Estado *</label>
                    <select name="estado" id="estado" required>
                        <option value="pendiente" <?php echo ($cita && $cita['estado'] === 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                        <option value="confirmada" <?php echo ($cita && $cita['estado'] === 'confirmada') ? 'selected' : ''; ?>>Confirmada</option>
                        <option value="en_proceso" <?php echo ($cita && $cita['estado'] === 'en_proceso') ? 'selected' : ''; ?>>En Proceso</option>
                        <option value="completada" <?php echo ($cita && $cita['estado'] === 'completada') ? 'selected' : ''; ?>>Completada</option>
                        <option value="cancelada" <?php echo ($cita && $cita['estado'] === 'cancelada') ? 'selected' : ''; ?>>Cancelada</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="notas">Notas</label>
                <textarea name="notas" id="notas" rows="4"><?php echo $cita ? htmlspecialchars($cita['notas']) : ''; ?></textarea>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="<?php echo URL_SERV; ?>/dw-panel/citas" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

<?php elseif ($action === 'view' && $cita): ?>
    <!-- Ver Detalles de Cita -->
    <div class="card">
        <div class="card-header">
            <h2>Detalles de la Cita #<?php echo $cita['id']; ?></h2>
            <div class="card-actions">
                <a href="<?php echo URL_SERV; ?>/dw-panel/citas?action=edit&id=<?php echo $cita['id']; ?>" class="btn btn-secondary">
                    <i class="fas fa-edit"></i> Editar
                </a>
            </div>
        </div>
        
        <div class="info-grid">
            <div class="info-item">
                <label>Cliente:</label>
                <span><?php echo htmlspecialchars($cita['cliente_nombre'] . ' ' . $cita['cliente_apellido']); ?></span>
            </div>
            <div class="info-item">
                <label>Teléfono:</label>
                <span><?php echo htmlspecialchars($cita['cliente_telefono']); ?></span>
            </div>
            <div class="info-item">
                <label>Email:</label>
                <span><?php echo htmlspecialchars($cita['cliente_email']); ?></span>
            </div>
            <div class="info-item">
                <label>Servicio:</label>
                <span><?php echo htmlspecialchars($cita['servicio_nombre']); ?></span>
            </div>
            <div class="info-item">
                <label>Empleado:</label>
                <span><?php echo $cita['empleado_nombre'] ? htmlspecialchars($cita['empleado_nombre'] . ' ' . $cita['empleado_apellido']) : 'Sin asignar'; ?></span>
            </div>
            <div class="info-item">
                <label>Fecha:</label>
                <span><?php echo date('d/m/Y', strtotime($cita['fecha'])); ?></span>
            </div>
            <div class="info-item">
                <label>Hora:</label>
                <span><?php echo date('H:i', strtotime($cita['hora'])); ?></span>
            </div>
            <div class="info-item">
                <label>Duración:</label>
                <span><?php echo $cita['duracion_minutos']; ?> minutos</span>
            </div>
            <div class="info-item">
                <label>Estado:</label>
                <span class="badge badge-<?php echo $cita['estado']; ?>">
                    <?php echo ucfirst(str_replace('_', ' ', $cita['estado'])); ?>
                </span>
            </div>
            <div class="info-item">
                <label>Precio:</label>
                <span><?php echo formatPrice($cita['precio']); ?></span>
            </div>
            <div class="info-item">
                <label>Descuento:</label>
                <span><?php echo formatPrice($cita['descuento']); ?></span>
            </div>
            <div class="info-item">
                <label>Total:</label>
                <span class="total-amount"><?php echo formatPrice($cita['total']); ?></span>
            </div>
            <?php if ($cita['notas']): ?>
                <div class="info-item full-width">
                    <label>Notas:</label>
                    <span><?php echo nl2br(htmlspecialchars($cita['notas'])); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php else: ?>
    <!-- Lista de Citas -->
    <div class="filters-card">
        <form method="GET" action="" class="filters-form">
            <div class="filters-grid">
                <div class="filter-group">
                    <label>Buscar:</label>
                    <input type="text" name="buscar" value="<?php echo htmlspecialchars($filters['buscar']); ?>" 
                           placeholder="Cliente, teléfono, servicio...">
                </div>
                <div class="filter-group">
                    <label>Estado:</label>
                    <select name="estado">
                        <option value="">Todos</option>
                        <option value="pendiente" <?php echo $filters['estado'] === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                        <option value="confirmada" <?php echo $filters['estado'] === 'confirmada' ? 'selected' : ''; ?>>Confirmada</option>
                        <option value="en_proceso" <?php echo $filters['estado'] === 'en_proceso' ? 'selected' : ''; ?>>En Proceso</option>
                        <option value="completada" <?php echo $filters['estado'] === 'completada' ? 'selected' : ''; ?>>Completada</option>
                        <option value="cancelada" <?php echo $filters['estado'] === 'cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Desde:</label>
                    <input type="date" name="fecha_desde" value="<?php echo htmlspecialchars($filters['fecha_desde']); ?>">
                </div>
                <div class="filter-group">
                    <label>Hasta:</label>
                    <input type="date" name="fecha_hasta" value="<?php echo htmlspecialchars($filters['fecha_hasta']); ?>">
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                    <a href="<?php echo URL_SERV; ?>/dw-panel/citas" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h2>Citas (<?php echo $total_citas; ?>)</h2>
        </div>
        
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Cliente</th>
                        <th>Servicio</th>
                        <th>Empleado</th>
                        <th>Estado</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($citas)): ?>
                        <tr>
                            <td colspan="9" class="text-center">No hay citas registradas</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($citas as $cita): ?>
                            <tr>
                                <td>#<?php echo $cita['id']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($cita['fecha'])); ?></td>
                                <td><?php echo date('H:i', strtotime($cita['hora'])); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($cita['cliente_nombre'] . ' ' . $cita['cliente_apellido']); ?><br>
                                    <small style="color: #7F8C8D;"><?php echo htmlspecialchars($cita['cliente_telefono']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($cita['servicio_nombre']); ?></td>
                                <td><?php echo $cita['empleado_nombre'] ? htmlspecialchars($cita['empleado_nombre'] . ' ' . $cita['empleado_apellido']) : '-'; ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $cita['estado']; ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $cita['estado'])); ?>
                                    </span>
                                </td>
                                <td><?php echo formatPrice($cita['total']); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?php echo URL_SERV; ?>/dw-panel/citas?action=view&id=<?php echo $cita['id']; ?>" 
                                           class="btn-icon" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo URL_SERV; ?>/dw-panel/citas?action=edit&id=<?php echo $cita['id']; ?>" 
                                           class="btn-icon" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="" style="display: inline;" 
                                              onsubmit="return confirm('¿Estás seguro de eliminar esta cita?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $cita['id']; ?>">
                                            <button type="submit" class="btn-icon btn-danger" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
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
// Auto-completar precio y duración al seleccionar servicio
document.addEventListener('DOMContentLoaded', function() {
    const servicioSelect = document.getElementById('servicio_id');
    const precioInput = document.getElementById('precio');
    const duracionInput = document.getElementById('duracion_minutos');
    
    if (servicioSelect && precioInput && duracionInput) {
        servicioSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                precioInput.value = selectedOption.getAttribute('data-precio');
                duracionInput.value = selectedOption.getAttribute('data-duracion');
            }
        });
    }
});
</script>

