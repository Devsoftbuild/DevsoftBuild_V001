<?php
/**
 * Contenido de Gestión de Estadísticas
 * DevSoft Build SAC
 */
?>
<div class="page-header">
    <div class="header-actions">
        <p style="color: var(--color-text-light);">Edita las estadísticas que se muestran en el sitio web</p>
    </div>
</div>

<?php if ($message): ?>
    <div class="alert alert-<?php echo $message_type; ?>">
        <i class="fas fa-<?php echo $message_type === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<div class="card">
    <h2>Estadísticas (<?php echo count($estadisticas); ?>)</h2>
    <form method="POST" action="">
        <input type="hidden" name="action" value="update">
        
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Valor</th>
                        <th>Icono</th>
                        <th>Orden</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($estadisticas as $est): ?>
                        <tr>
                            <td>#<?php echo $est['id_estadistica'] ?? ''; ?></td>
                            <td>
                                <input type="text" name="estadisticas[<?php echo $est['id_estadistica']; ?>][titulo]" 
                                       value="<?php echo htmlspecialchars($est['titulo_estadistica'] ?? ''); ?>" 
                                       placeholder="Título de la estadística"
                                       style="width: 200px; padding: 5px; border: 2px solid var(--color-border); border-radius: 5px;">
                            </td>
                            <td>
                                <input type="text" name="estadisticas[<?php echo $est['id_estadistica']; ?>][valor]" 
                                       value="<?php echo htmlspecialchars($est['valor_estadistica'] ?? ''); ?>" 
                                       style="width: 100px; padding: 5px; border: 2px solid var(--color-border); border-radius: 5px;">
                            </td>
                            <td>
                                <input type="text" name="estadisticas[<?php echo $est['id_estadistica']; ?>][icono]" 
                                       value="<?php echo htmlspecialchars($est['icono_estadistica'] ?? ''); ?>" 
                                       placeholder="fas fa-..." 
                                       style="width: 150px; padding: 5px; border: 2px solid var(--color-border); border-radius: 5px;">
                            </td>
                            <td>
                                <input type="number" name="estadisticas[<?php echo $est['id_estadistica']; ?>][orden]" 
                                       value="<?php echo $est['orden_estadistica'] ?? 0; ?>" 
                                       style="width: 80px; padding: 5px; border: 2px solid var(--color-border); border-radius: 5px;">
                            </td>
                            <td>
                                <label style="display: flex; align-items: center; gap: 5px;">
                                    <input type="checkbox" name="estadisticas[<?php echo $est['id_estadistica']; ?>][estado]" 
                                           <?php echo ($est['estado_estadistica'] ?? 0) == 1 ? 'checked' : ''; ?>>
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
