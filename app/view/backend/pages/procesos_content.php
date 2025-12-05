<?php
/**
 * Contenido de Gestión de Procesos
 * DevSoft Build SAC
 */
?>
<div class="page-header">
    <div class="header-actions">
        <p style="color: var(--color-text-light);">Edita los pasos del proceso de trabajo que se muestran en el sitio web</p>
    </div>
</div>

<?php if ($message): ?>
    <div class="alert alert-<?php echo $message_type; ?>">
        <i class="fas fa-<?php echo $message_type === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<div class="card">
    <h2>Procesos de Trabajo (<?php echo count($procesos); ?>)</h2>
    <form method="POST" action="">
        <input type="hidden" name="action" value="update">
        
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Icono</th>
                        <th>Orden</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($procesos as $proc): ?>
                        <tr>
                            <td>#<?php echo $proc['numero_proceso'] ?? $proc['id_proceso'] ?? ''; ?></td>
                            <td>
                                <input type="text" name="procesos[<?php echo $proc['id_proceso']; ?>][titulo]" 
                                       value="<?php echo htmlspecialchars($proc['titulo_proceso'] ?? ''); ?>" 
                                       style="width: 200px; padding: 5px; border: 2px solid var(--color-border); border-radius: 5px;">
                            </td>
                            <td>
                                <textarea name="procesos[<?php echo $proc['id_proceso']; ?>][descripcion]" 
                                          rows="2" 
                                          style="width: 100%; padding: 5px; border: 2px solid var(--color-border); border-radius: 5px;"><?php echo htmlspecialchars($proc['descripcion_proceso'] ?? ''); ?></textarea>
                            </td>
                            <td>
                                <input type="text" name="procesos[<?php echo $proc['id_proceso']; ?>][icono]" 
                                       value="<?php echo htmlspecialchars($proc['icono_proceso'] ?? ''); ?>" 
                                       placeholder="fas fa-..." 
                                       style="width: 150px; padding: 5px; border: 2px solid var(--color-border); border-radius: 5px;">
                            </td>
                            <td>
                                <input type="number" name="procesos[<?php echo $proc['id_proceso']; ?>][orden]" 
                                       value="<?php echo $proc['orden_proceso'] ?? 0; ?>" 
                                       style="width: 80px; padding: 5px; border: 2px solid var(--color-border); border-radius: 5px;">
                            </td>
                            <td>
                                <label style="display: flex; align-items: center; gap: 5px;">
                                    <input type="checkbox" name="procesos[<?php echo $proc['id_proceso']; ?>][estado]" 
                                           <?php echo ($proc['estado_proceso'] ?? 0) == 1 ? 'checked' : ''; ?>>
                                    Activo
                                </label>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
        </div>
    </form>
</div>
