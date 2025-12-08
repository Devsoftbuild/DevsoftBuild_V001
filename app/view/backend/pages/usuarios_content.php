<?php
/**
 * Contenido de Gestión de Usuarios
 * DevSoft Build SAC
 */
?>
<div class="page-header">
    <div class="header-actions">
        <a href="<?php echo URL_SERV; ?>/dw-panel/usuarios?action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Usuario
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
        <h2><?php echo $action === 'create' ? 'Nuevo Usuario' : 'Editar Usuario'; ?></h2>
        <form method="POST" action="">
            <input type="hidden" name="action" value="<?php echo $action === 'create' ? 'create' : 'update'; ?>">
            <?php if ($action === 'edit'): ?><input type="hidden" name="id" value="<?php echo $usuario['id_usuario']; ?>"><?php endif; ?>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="nombre_usuario" value="<?php echo htmlspecialchars($usuario['nombre_usuario'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email_usuario" value="<?php echo htmlspecialchars($usuario['email_usuario'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Contraseña <?php echo $action === 'edit' ? '(dejar vacío para mantener)' : '*'; ?></label>
                    <input type="password" name="password_usuario" <?php echo $action === 'create' ? 'required' : ''; ?>>
                </div>
                
                <div class="form-group">
                    <label>Rol</label>
                    <select name="rol_usuario">
                        <option value="admin" <?php echo ($usuario['rol_usuario'] ?? '') === 'admin' ? 'selected' : ''; ?>>Administrador</option>
                        <option value="editor" <?php echo ($usuario['rol_usuario'] ?? '') === 'editor' ? 'selected' : ''; ?>>Editor</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado_usuario">
                        <option value="1" <?php echo ($usuario['estado_usuario'] ?? 1) == 1 ? 'selected' : ''; ?>>Activo</option>
                        <option value="0" <?php echo ($usuario['estado_usuario'] ?? 1) == 0 ? 'selected' : ''; ?>>Inactivo</option>
                    </select>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                <a href="<?php echo URL_SERV; ?>/dw-panel/usuarios" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-header"><h2>Usuarios (<?php echo count($usuarios); ?>)</h2></div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Último Acceso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($usuarios)): ?>
                        <tr>
                            <td colspan="7" class="text-center">No hay usuarios registrados</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td>#<?php echo $u['id_usuario']; ?></td>
                                <td><strong><?php echo htmlspecialchars($u['nombre_usuario']); ?></strong></td>
                                <td><?php echo htmlspecialchars($u['email_usuario']); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $u['rol_usuario'] === 'admin' ? 'warning' : 'info'; ?>">
                                        <?php echo ucfirst($u['rol_usuario']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-<?php echo $u['estado_usuario'] == 1 ? 'success' : 'danger'; ?>">
                                        <?php echo $u['estado_usuario'] == 1 ? 'Activo' : 'Inactivo'; ?>
                                    </span>
                                </td>
                                <td><?php echo !empty($u['ultimo_acceso_usuario']) ? date('d/m/Y H:i', strtotime($u['ultimo_acceso_usuario'])) : '-'; ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?php echo URL_SERV; ?>/dw-panel/usuarios?action=edit&id=<?php echo $u['id_usuario']; ?>" class="btn-icon" title="Editar"><i class="fas fa-edit"></i></a>
                                        <?php if ($u['id_usuario'] != $_SESSION['admin_user_id']): ?>
                                            <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar usuario?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $u['id_usuario']; ?>">
                                                <button type="submit" class="btn-icon btn-danger" title="Eliminar"><i class="fas fa-trash"></i></button>
                                            </form>
                                        <?php endif; ?>
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


