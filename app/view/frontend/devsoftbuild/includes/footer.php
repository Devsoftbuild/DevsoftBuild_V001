<?php
/**
 * Footer Template
 * DevSoft Build SAC
 */
if (!defined('APP_PATH')) {
    die('Acceso directo no permitido');
}

// Asegurar que ConfigModel esté cargado
if (!class_exists('ConfigModel')) {
    require_once BASE_PATH . '/app/model/ConfigModel.php';
}

// Obtener configuraciones
$email_contacto = ConfigModel::get('email_contacto', 'contacto@devsoftbuild.com');
$telefono_contacto = ConfigModel::get('telefono_contacto', '');
$direccion_contacto = ConfigModel::get('direccion_contacto', '');
$horario_atencion = ConfigModel::get('horario_atencion', 'Lunes a Viernes: 9:00 AM - 6:00 PM');

// Obtener redes sociales (si existe el modelo)
$redes_sociales = [];
if (class_exists('RedSocialModel')) {
    $redes_sociales = RedSocialModel::getAll(['estado_red_social' => 1]);
}
?>
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <!-- Columna 1: Sobre Nosotros -->
            <div class="footer-col">
                <h3 class="footer-title"><?php echo APP_NAME; ?></h3>
                <p class="footer-description">Transformamos ideas en soluciones digitales innovadoras. Desarrollo web y aplicaciones móviles de alto rendimiento.</p>
            </div>
            
            <!-- Columna 2: Servicios -->
            <div class="footer-col">
                <h4 class="footer-subtitle">Servicios</h4>
                <ul class="footer-links">
                    <li><a href="<?php echo URL_SERV; ?>/servicios">Desarrollo Web</a></li>
                    <li><a href="<?php echo URL_SERV; ?>/servicios">Apps Móviles</a></li>
                    <li><a href="<?php echo URL_SERV; ?>/servicios">Software a Medida</a></li>
                    <li><a href="<?php echo URL_SERV; ?>/servicios">Consultoría</a></li>
                </ul>
            </div>
            
            <!-- Columna 3: Enlaces -->
            <div class="footer-col">
                <h4 class="footer-subtitle">Enlaces</h4>
                <ul class="footer-links">
                    <li><a href="<?php echo URL_SERV; ?>">Inicio</a></li>
                    <li><a href="<?php echo URL_SERV; ?>/proyectos">Proyectos</a></li>
                    <li><a href="<?php echo URL_SERV; ?>/nosotros">Nosotros</a></li>
                    <li><a href="<?php echo URL_SERV; ?>/blog">Blog</a></li>
                    <li><a href="<?php echo URL_SERV; ?>/contacto">Contacto</a></li>
                </ul>
            </div>
            
            <!-- Columna 4: Contacto -->
            <div class="footer-col">
                <h4 class="footer-subtitle">Contacto</h4>
                <ul class="footer-contact">
                    <?php if ($email_contacto): ?>
                    <li><i class="fas fa-envelope"></i> <a href="mailto:<?php echo htmlspecialchars($email_contacto); ?>"><?php echo htmlspecialchars($email_contacto); ?></a></li>
                    <?php endif; ?>
                    <?php if ($telefono_contacto): ?>
                    <li><i class="fas fa-phone"></i> <a href="tel:<?php echo htmlspecialchars($telefono_contacto); ?>"><?php echo htmlspecialchars($telefono_contacto); ?></a></li>
                    <?php endif; ?>
                    <?php if ($direccion_contacto): ?>
                    <li><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($direccion_contacto); ?></li>
                    <?php endif; ?>
                    <?php if ($horario_atencion): ?>
                    <li><i class="fas fa-clock"></i> <?php echo htmlspecialchars($horario_atencion); ?></li>
                    <?php endif; ?>
                </ul>
                
                <!-- Redes Sociales -->
                <?php if (!empty($redes_sociales)): ?>
                <div class="footer-social">
                    <?php foreach ($redes_sociales as $red): ?>
                    <a href="<?php echo htmlspecialchars($red['url_red_social']); ?>" target="_blank" rel="noopener" class="social-link" title="<?php echo htmlspecialchars($red['nombre_red_social']); ?>">
                        <i class="<?php echo htmlspecialchars($red['icono_red_social'] ?? 'fab fa-facebook'); ?>"></i>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>

