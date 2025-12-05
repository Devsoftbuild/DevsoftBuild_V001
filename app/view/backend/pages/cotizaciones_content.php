<?php
/**
 * Contenido de Gestión de Cotizaciones
 * DevSoft Build SAC
 */
?>
<div class="page-header">
    <div class="header-actions">
        <form method="GET" style="display: inline-block;">
            <select name="estado" onchange="this.form.submit()" style="padding: 8px 15px; border: 2px solid var(--color-border); border-radius: 8px;">
                <option value="">Todos los estados</option>
                <option value="pendiente" <?php echo ($_GET['estado'] ?? '') === 'pendiente' ? 'selected' : ''; ?>>Pendientes</option>
                <option value="en_revision" <?php echo ($_GET['estado'] ?? '') === 'en_revision' ? 'selected' : ''; ?>>En Revisión</option>
                <option value="enviada" <?php echo ($_GET['estado'] ?? '') === 'enviada' ? 'selected' : ''; ?>>Enviadas</option>
                <option value="aceptada" <?php echo ($_GET['estado'] ?? '') === 'aceptada' ? 'selected' : ''; ?>>Aceptadas</option>
                <option value="rechazada" <?php echo ($_GET['estado'] ?? '') === 'rechazada' ? 'selected' : ''; ?>>Rechazadas</option>
            </select>
        </form>
    </div>
</div>

<?php if ($message): ?>
    <div class="alert alert-<?php echo $message_type; ?>">
        <i class="fas fa-<?php echo $message_type === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<?php if ($action === 'view' && $cotizacion): ?>
    <div class="card">
        <div class="card-header">
            <h2>Detalle de Cotización</h2>
            <a href="<?php echo URL_SERV; ?>/dw-panel/cotizaciones" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
        
        <div class="info-grid">
            <div class="info-item">
                <label>Nombre</label>
                <span><?php echo htmlspecialchars($cotizacion['nombre_cotizacion']); ?></span>
            </div>
            <div class="info-item">
                <label>Email</label>
                <span><a href="mailto:<?php echo htmlspecialchars($cotizacion['email_cotizacion']); ?>"><?php echo htmlspecialchars($cotizacion['email_cotizacion']); ?></a></span>
            </div>
            <div class="info-item">
                <label>Teléfono</label>
                <span><?php echo htmlspecialchars($cotizacion['telefono_cotizacion'] ?? '-'); ?></span>
            </div>
            <div class="info-item">
                <label>Empresa</label>
                <span><?php echo htmlspecialchars($cotizacion['empresa_cotizacion'] ?? '-'); ?></span>
            </div>
            <div class="info-item">
                <label>Tipo de Proyecto</label>
                <span><?php echo htmlspecialchars($cotizacion['tipo_proyecto_cotizacion']); ?></span>
            </div>
            <div class="info-item">
                <label>Servicio</label>
                <span><?php echo htmlspecialchars($cotizacion['nombre_servicio'] ?? '-'); ?></span>
            </div>
            <div class="info-item">
                <label>Presupuesto Estimado</label>
                <span><?php echo !empty($cotizacion['presupuesto_estimado_cotizacion']) ? formatPrice($cotizacion['presupuesto_estimado_cotizacion']) : '-'; ?></span>
            </div>
            <div class="info-item">
                <label>Plazo Estimado</label>
                <span><?php echo htmlspecialchars($cotizacion['plazo_estimado_cotizacion'] ?? '-'); ?></span>
            </div>
            <div class="info-item">
                <label>Estado</label>
                <span>
                    <span class="badge badge-<?php 
                        echo $cotizacion['estado_cotizacion'] === 'pendiente' ? 'warning' : 
                            ($cotizacion['estado_cotizacion'] === 'en_revision' ? 'info' : 
                            ($cotizacion['estado_cotizacion'] === 'aceptada' ? 'success' : 'danger')); 
                    ?>">
                        <?php echo ucfirst(str_replace('_', ' ', $cotizacion['estado_cotizacion'])); ?>
                    </span>
                </span>
            </div>
            <div class="info-item full-width">
                <label>Descripción del Proyecto</label>
                <span style="white-space: pre-wrap;"><?php echo htmlspecialchars($cotizacion['descripcion_proyecto_cotizacion']); ?></span>
            </div>
            <div class="info-item">
                <label>Fecha</label>
                <span><?php echo date('d/m/Y H:i', strtotime($cotizacion['created_at'])); ?></span>
            </div>
        </div>
        
        <div class="form-actions">
            <form method="POST" style="display: inline-block;">
                <input type="hidden" name="action" value="update_estado">
                <input type="hidden" name="id" value="<?php echo $cotizacion['id_cotizacion']; ?>">
                <select name="estado_cotizacion" onchange="this.form.submit()" style="padding: 8px 15px; border: 2px solid var(--color-border); border-radius: 8px;">
                    <option value="pendiente" <?php echo $cotizacion['estado_cotizacion'] === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                    <option value="en_revision" <?php echo $cotizacion['estado_cotizacion'] === 'en_revision' ? 'selected' : ''; ?>>En Revisión</option>
                    <option value="enviada" <?php echo $cotizacion['estado_cotizacion'] === 'enviada' ? 'selected' : ''; ?>>Enviada</option>
                    <option value="aceptada" <?php echo $cotizacion['estado_cotizacion'] === 'aceptada' ? 'selected' : ''; ?>>Aceptada</option>
                    <option value="rechazada" <?php echo $cotizacion['estado_cotizacion'] === 'rechazada' ? 'selected' : ''; ?>>Rechazada</option>
                </select>
            </form>
        </div>
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-header"><h2>Cotizaciones (<?php echo count($cotizaciones); ?>)</h2></div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Tipo Proyecto</th>
                        <th>Servicio</th>
                        <th>Presupuesto</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($cotizaciones)): ?>
                        <tr>
                            <td colspan="9" class="text-center">No hay cotizaciones registradas</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($cotizaciones as $cot): ?>
                            <tr>
                                <td>#<?php echo $cot['id_cotizacion']; ?></td>
                                <td><strong><?php echo htmlspecialchars($cot['nombre_cotizacion']); ?></strong></td>
                                <td><a href="mailto:<?php echo htmlspecialchars($cot['email_cotizacion']); ?>"><?php echo htmlspecialchars($cot['email_cotizacion']); ?></a></td>
                                <td><?php echo htmlspecialchars($cot['tipo_proyecto_cotizacion']); ?></td>
                                <td><?php echo htmlspecialchars($cot['nombre_servicio'] ?? '-'); ?></td>
                                <td><?php echo !empty($cot['presupuesto_estimado_cotizacion']) ? formatPrice($cot['presupuesto_estimado_cotizacion']) : '-'; ?></td>
                                <td>
                                    <span class="badge badge-<?php 
                                        echo $cot['estado_cotizacion'] === 'pendiente' ? 'warning' : 
                                            ($cot['estado_cotizacion'] === 'en_revision' ? 'info' : 
                                            ($cot['estado_cotizacion'] === 'aceptada' ? 'success' : 'danger')); 
                                    ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $cot['estado_cotizacion'])); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($cot['created_at'])); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?php echo URL_SERV; ?>/dw-panel/cotizaciones?action=view&id=<?php echo $cot['id_cotizacion']; ?>" class="btn-icon" title="Ver"><i class="fas fa-eye"></i></a>
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

