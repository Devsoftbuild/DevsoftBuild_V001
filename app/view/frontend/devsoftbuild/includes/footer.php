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

// Redes desde configuración (por clave: funciona aunque grupo_configuracion esté vacío en BD antigua)
$redes_sociales_links = [
    'facebook'  => ['icon' => 'fab fa-facebook-f', 'title' => 'Facebook'],
    'instagram' => ['icon' => 'fab fa-instagram', 'title' => 'Instagram'],
    'twitter'   => ['icon' => 'fab fa-twitter', 'title' => 'X (Twitter)'],
    'linkedin'  => ['icon' => 'fab fa-linkedin-in', 'title' => 'LinkedIn'],
    'youtube'   => ['icon' => 'fab fa-youtube', 'title' => 'YouTube'],
    'github'    => ['icon' => 'fab fa-github', 'title' => 'GitHub'],
];
$hay_redes_footer = false;
foreach ($redes_sociales_links as $clave => $meta) {
    $u = trim((string) ConfigModel::get($clave, ''));
    if ($u !== '') {
        $hay_redes_footer = true;
        break;
    }
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
                <?php if ($hay_redes_footer): ?>
                <div class="footer-social">
                    <?php foreach ($redes_sociales_links as $clave => $meta): ?>
                        <?php
                        $url = trim((string) ConfigModel::get($clave, ''));
                        if ($url === '') {
                            continue;
                        }
                        ?>
                    <a href="<?php echo htmlspecialchars($url); ?>" target="_blank" rel="noopener noreferrer" class="social-link" title="<?php echo htmlspecialchars($meta['title']); ?>">
                        <i class="<?php echo htmlspecialchars($meta['icon']); ?>" aria-hidden="true"></i>
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

