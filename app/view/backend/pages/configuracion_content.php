<?php
/**
 * Contenido de Configuración
 * DevSoft Build SAC
 */
?>
<?php if ($message): ?>
    <div class="alert alert-<?php echo $message_type; ?>">
        <i class="fas fa-<?php echo $message_type === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<form method="POST" action="">
    <div class="card">
        <h2>Información General</h2>
        <div class="form-grid">
            <div class="form-group full-width">
                <label>Nombre de la Empresa</label>
                <input type="text" name="nombre_empresa" value="<?php echo htmlspecialchars($config_general['nombre_empresa'] ?? APP_NAME); ?>">
            </div>
            <div class="form-group full-width">
                <label>Logo</label>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <?php if (!empty($config_general['logo'])): ?>
                        <img src="<?php echo htmlspecialchars($config_general['logo']); ?>" alt="Logo" style="max-height: 60px; border: 2px solid #E0E0E0; border-radius: 8px; padding: 5px;">
                    <?php endif; ?>
                    <div style="flex: 1;">
                        <input type="text" name="logo" value="<?php echo htmlspecialchars($config_general['logo'] ?? ''); ?>" placeholder="URL o ruta del logo">
                        <small style="display: block; margin-top: 5px; color: #7F8C8D;">O usa el módulo de subida de logo</small>
                    </div>
                    <a href="<?php echo URL_SERV; ?>/dw-panel/configuracion_logo" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Subir Logo
                    </a>
                </div>
            </div>
            <div class="form-group">
                <label>Teléfono</label>
                <input type="tel" name="telefono" value="<?php echo htmlspecialchars($config_general['telefono'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($config_general['email'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Dirección</label>
                <input type="text" name="direccion" value="<?php echo htmlspecialchars($config_general['direccion'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Ciudad</label>
                <input type="text" name="ciudad" value="<?php echo htmlspecialchars($config_general['ciudad'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>País</label>
                <input type="text" name="pais" value="<?php echo htmlspecialchars($config_general['pais'] ?? 'Perú'); ?>">
            </div>
        </div>
    </div>
    
    <div class="card">
        <h2>Información de Contacto</h2>
        <div class="form-grid">
            <div class="form-group">
                <label>Email de Contacto</label>
                <input type="email" name="email_contacto" value="<?php echo htmlspecialchars($config_contacto['email_contacto'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Teléfono de Contacto</label>
                <input type="tel" name="telefono_contacto" value="<?php echo htmlspecialchars($config_contacto['telefono_contacto'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>WhatsApp</label>
                <input type="text" name="whatsapp" value="<?php echo htmlspecialchars($config_contacto['whatsapp'] ?? ''); ?>" placeholder="1234567890">
            </div>
        </div>
    </div>
    
    <div class="card">
        <h2>Redes Sociales</h2>
        <div class="form-grid">
            <div class="form-group">
                <label>Facebook</label>
                <input type="url" name="facebook" value="<?php echo htmlspecialchars($config_redes['facebook'] ?? ''); ?>" placeholder="https://facebook.com/...">
            </div>
            <div class="form-group">
                <label>Instagram</label>
                <input type="url" name="instagram" value="<?php echo htmlspecialchars($config_redes['instagram'] ?? ''); ?>" placeholder="https://instagram.com/...">
            </div>
            <div class="form-group">
                <label>Twitter/X</label>
                <input type="url" name="twitter" value="<?php echo htmlspecialchars($config_redes['twitter'] ?? ''); ?>" placeholder="https://twitter.com/...">
            </div>
            <div class="form-group">
                <label>LinkedIn</label>
                <input type="url" name="linkedin" value="<?php echo htmlspecialchars($config_redes['linkedin'] ?? ''); ?>" placeholder="https://linkedin.com/...">
            </div>
            <div class="form-group">
                <label>YouTube</label>
                <input type="url" name="youtube" value="<?php echo htmlspecialchars($config_redes['youtube'] ?? ''); ?>" placeholder="https://youtube.com/...">
            </div>
            <div class="form-group">
                <label>GitHub</label>
                <input type="url" name="github" value="<?php echo htmlspecialchars($config_redes['github'] ?? ''); ?>" placeholder="https://github.com/...">
            </div>
        </div>
    </div>
    
    <div class="card">
        <h2>SEO General</h2>
        <div class="form-grid">
            <div class="form-group full-width">
                <label>Meta Título por Defecto</label>
                <input type="text" name="meta_titulo_default" value="<?php echo htmlspecialchars($config_seo['meta_titulo_default'] ?? ''); ?>" maxlength="255">
            </div>
            <div class="form-group full-width">
                <label>Meta Descripción por Defecto</label>
                <textarea name="meta_descripcion_default" rows="3" maxlength="500"><?php echo htmlspecialchars($config_seo['meta_descripcion_default'] ?? ''); ?></textarea>
            </div>
            <div class="form-group full-width">
                <label>Meta Keywords por Defecto</label>
                <input type="text" name="meta_keywords_default" value="<?php echo htmlspecialchars($config_seo['meta_keywords_default'] ?? ''); ?>" placeholder="palabra1, palabra2, palabra3">
            </div>
        </div>
    </div>
    
    <div class="form-actions" style="margin-top: 20px;">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Guardar Configuración
        </button>
    </div>
</form>
