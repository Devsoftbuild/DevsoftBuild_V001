<?php
/**
 * Contenido de Gestión de Contactos
 * DevSoft Build SAC
 */
?>
<div class="page-header">
    <div class="header-actions">
        <form method="GET" style="display: inline-block;">
            <select name="estado" onchange="this.form.submit()" style="padding: 8px 15px; border: 2px solid var(--color-border); border-radius: 8px;">
                <option value="">Todos los estados</option>
                <option value="nuevo" <?php echo ($_GET['estado'] ?? '') === 'nuevo' ? 'selected' : ''; ?>>Nuevos</option>
                <option value="leido" <?php echo ($_GET['estado'] ?? '') === 'leido' ? 'selected' : ''; ?>>Leídos</option>
                <option value="respondido" <?php echo ($_GET['estado'] ?? '') === 'respondido' ? 'selected' : ''; ?>>Respondidos</option>
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

<?php if ($action === 'view' && $contacto): ?>
    <div class="card">
        <div class="card-header">
            <h2>Detalle de Contacto</h2>
            <a href="<?php echo URL_SERV; ?>/dw-panel/contactos" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
        
        <div class="info-grid">
            <div class="info-item">
                <label>Nombre</label>
                <span><?php echo htmlspecialchars($contacto['nombre_contacto']); ?></span>
            </div>
            <div class="info-item">
                <label>Email</label>
                <span><a href="mailto:<?php echo htmlspecialchars($contacto['email_contacto']); ?>"><?php echo htmlspecialchars($contacto['email_contacto']); ?></a></span>
            </div>
            <div class="info-item">
                <label>Teléfono</label>
                <span><?php echo htmlspecialchars($contacto['telefono_contacto'] ?? '-'); ?></span>
            </div>
            <div class="info-item">
                <label>Empresa</label>
                <span><?php echo htmlspecialchars($contacto['empresa_contacto'] ?? '-'); ?></span>
            </div>
            <div class="info-item">
                <label>Tipo de Proyecto</label>
                <span><?php echo htmlspecialchars($contacto['tipo_proyecto_contacto'] ?? '-'); ?></span>
            </div>
            <div class="info-item">
                <label>Estado</label>
                <span>
                    <span class="badge badge-<?php 
                        echo $contacto['estado_contacto'] === 'nuevo' ? 'warning' : 
                            ($contacto['estado_contacto'] === 'leido' ? 'info' : 'success'); 
                    ?>">
                        <?php echo ucfirst($contacto['estado_contacto']); ?>
                    </span>
                </span>
            </div>
            <div class="info-item full-width">
                <label>Mensaje</label>
                <span style="white-space: pre-wrap;"><?php echo htmlspecialchars($contacto['mensaje_contacto']); ?></span>
            </div>
            <div class="info-item">
                <label>Fecha</label>
                <span><?php echo date('d/m/Y H:i', strtotime($contacto['created_at'])); ?></span>
            </div>
        </div>
        
        <div class="form-actions">
            <form method="POST" style="display: inline-block;">
                <input type="hidden" name="action" value="update_estado">
                <input type="hidden" name="id" value="<?php echo $contacto['id_contacto']; ?>">
                <select name="estado_contacto" onchange="this.form.submit()" style="padding: 8px 15px; border: 2px solid var(--color-border); border-radius: 8px;">
                    <option value="nuevo" <?php echo $contacto['estado_contacto'] === 'nuevo' ? 'selected' : ''; ?>>Nuevo</option>
                    <option value="leido" <?php echo $contacto['estado_contacto'] === 'leido' ? 'selected' : ''; ?>>Leído</option>
                    <option value="respondido" <?php echo $contacto['estado_contacto'] === 'respondido' ? 'selected' : ''; ?>>Respondido</option>
                </select>
            </form>
        </div>
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-header"><h2>Contactos (<?php echo count($contactos); ?>)</h2></div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Tipo Proyecto</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($contactos)): ?>
                        <tr>
                            <td colspan="8" class="text-center">No hay contactos registrados</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($contactos as $c): ?>
                            <tr>
                                <td>#<?php echo $c['id_contacto']; ?></td>
                                <td><strong><?php echo htmlspecialchars($c['nombre_contacto']); ?></strong></td>
                                <td><a href="mailto:<?php echo htmlspecialchars($c['email_contacto']); ?>"><?php echo htmlspecialchars($c['email_contacto']); ?></a></td>
                                <td><?php echo htmlspecialchars($c['telefono_contacto'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($c['tipo_proyecto_contacto'] ?? '-'); ?></td>
                                <td>
                                    <span class="badge badge-<?php 
                                        echo $c['estado_contacto'] === 'nuevo' ? 'warning' : 
                                            ($c['estado_contacto'] === 'leido' ? 'info' : 'success'); 
                                    ?>">
                                        <?php echo ucfirst($c['estado_contacto']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($c['created_at'])); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?php echo URL_SERV; ?>/dw-panel/contactos?action=view&id=<?php echo $c['id_contacto']; ?>" class="btn-icon" title="Ver"><i class="fas fa-eye"></i></a>
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

