<?php
?>
<div class="page-header">
    <div class="header-actions">
        <a href="<?php echo URL_SERV; ?>/dw-panel/citas?action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Cita
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Calendario de Citas</h2>
        <form method="GET" style="display: inline-flex; gap: 10px; align-items: center;">
            <input type="date" name="fecha" value="<?php echo $fecha_seleccionada; ?>" onchange="this.form.submit()">
            <a href="<?php echo URL_SERV; ?>/dw-panel/calendario?fecha=<?php echo date('Y-m-d'); ?>" class="btn btn-secondary">
                <i class="fas fa-calendar-day"></i> Hoy
            </a>
        </form>
    </div>
    
    <div class="calendar-view">
        <h3 style="margin-bottom: 20px;">Citas del <?php echo date('d/m/Y', strtotime($fecha_seleccionada)); ?></h3>
        
        <?php if (empty($citas_dia)): ?>
            <div class="text-center" style="padding: 40px;">
                <i class="fas fa-calendar-times" style="font-size: 3rem; color: #BDC3C7; margin-bottom: 15px;"></i>
                <p style="color: #7F8C8D;">No hay citas programadas para este día</p>
            </div>
        <?php else: ?>
            <div class="citas-timeline">
                <?php foreach ($citas_dia as $cita): ?>
                    <div class="cita-item" style="border-left: 4px solid #9B59B6; padding: 15px; margin-bottom: 15px; background: #F5F7FA; border-radius: 8px;">
                        <div style="display: flex; justify-content: space-between; align-items: start;">
                            <div>
                                <h4 style="margin: 0 0 10px 0; color: #2C3E50;">
                                    <?php echo date('H:i', strtotime($cita['hora'])); ?> - 
                                    <?php echo htmlspecialchars($cita['cliente_nombre'] . ' ' . $cita['cliente_apellido']); ?>
                                </h4>
                                <p style="margin: 5px 0; color: #7F8C8D;">
                                    <i class="fas fa-spa"></i> <?php echo htmlspecialchars($cita['servicio_nombre']); ?>
                                </p>
                                <?php if ($cita['empleado_nombre']): ?>
                                    <p style="margin: 5px 0; color: #7F8C8D;">
                                        <i class="fas fa-user-tie"></i> <?php echo htmlspecialchars($cita['empleado_nombre'] . ' ' . $cita['empleado_apellido']); ?>
                                    </p>
                                <?php endif; ?>
                                <p style="margin: 5px 0;">
                                    <span class="badge badge-<?php echo $cita['estado']; ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $cita['estado'])); ?>
                                    </span>
                                </p>
                            </div>
                            <div class="action-buttons">
                                <a href="<?php echo URL_SERV; ?>/dw-panel/citas?action=view&id=<?php echo $cita['id']; ?>" class="btn-icon" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo URL_SERV; ?>/dw-panel/citas?action=edit&id=<?php echo $cita['id']; ?>" class="btn-icon" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

