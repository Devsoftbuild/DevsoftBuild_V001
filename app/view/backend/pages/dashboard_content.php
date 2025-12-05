<?php
/**
 * Contenido del Dashboard
 * DevSoft Build SAC
 */
?>

<div class="dashboard-container">
    <!-- Métricas -->
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-icon" style="background: linear-gradient(135deg, #00d4ff, #1dd1e8);">
                <i class="fas fa-laptop-code"></i>
            </div>
            <div class="metric-info">
                <h3><?php echo $stats['servicios']; ?></h3>
                <p>Servicios Activos</p>
            </div>
        </div>
        
        <div class="metric-card">
            <div class="metric-icon" style="background: linear-gradient(135deg, #00d4ff, #1dd1e8);">
                <i class="fas fa-project-diagram"></i>
            </div>
            <div class="metric-info">
                <h3><?php echo $stats['proyectos']; ?></h3>
                <p>Proyectos Completados</p>
            </div>
        </div>
        
        <div class="metric-card">
            <div class="metric-icon" style="background: linear-gradient(135deg, #00d4ff, #1dd1e8);">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="metric-info">
                <h3><?php echo $stats['contactos_nuevos']; ?></h3>
                <p>Contactos (30 días)</p>
            </div>
        </div>
        
        <div class="metric-card">
            <div class="metric-icon" style="background: linear-gradient(135deg, #00d4ff, #1dd1e8);">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <div class="metric-info">
                <h3><?php echo $stats['cotizaciones_pendientes']; ?></h3>
                <p>Cotizaciones Pendientes</p>
            </div>
        </div>
    </div>
    
    <!-- Contactos Recientes -->
    <div class="dashboard-section">
        <div class="section-header">
            <h2>Contactos Recientes</h2>
            <a href="<?php echo URL_SERV; ?>/dw-panel/contactos" class="btn-link">Ver todos</a>
        </div>
        
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Tipo Proyecto</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($contactos_recientes)): ?>
                        <tr>
                            <td colspan="6" class="text-center">No hay contactos nuevos</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($contactos_recientes as $contacto): ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i', strtotime($contacto['created_at'])); ?></td>
                                <td><?php echo htmlspecialchars($contacto['nombre_contacto']); ?></td>
                                <td><?php echo htmlspecialchars($contacto['email_contacto']); ?></td>
                                <td><?php echo htmlspecialchars($contacto['tipo_proyecto_contacto'] ?? 'N/A'); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $contacto['estado_contacto'] === 'nuevo' ? 'warning' : 'info'; ?>">
                                        <?php echo ucfirst($contacto['estado_contacto']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo URL_SERV; ?>/dw-panel/contactos?id=<?php echo $contacto['id_contacto']; ?>" class="btn-icon" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Cotizaciones Recientes -->
    <div class="dashboard-section">
        <div class="section-header">
            <h2>Cotizaciones Pendientes</h2>
            <a href="<?php echo URL_SERV; ?>/dw-panel/cotizaciones" class="btn-link">Ver todas</a>
        </div>
        
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Tipo Proyecto</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($cotizaciones_recientes)): ?>
                        <tr>
                            <td colspan="5" class="text-center">No hay cotizaciones pendientes</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($cotizaciones_recientes as $cotizacion): ?>
                            <tr>
                                <td><?php echo date('d/m/Y', strtotime($cotizacion['created_at'])); ?></td>
                                <td><?php echo htmlspecialchars($cotizacion['nombre_cotizacion']); ?></td>
                                <td><?php echo htmlspecialchars($cotizacion['tipo_proyecto_cotizacion']); ?></td>
                                <td>
                                    <span class="badge badge-warning">
                                        <?php echo ucfirst(str_replace('_', ' ', $cotizacion['estado_cotizacion'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo URL_SERV; ?>/dw-panel/cotizaciones?id=<?php echo $cotizacion['id_cotizacion']; ?>" class="btn-icon" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
