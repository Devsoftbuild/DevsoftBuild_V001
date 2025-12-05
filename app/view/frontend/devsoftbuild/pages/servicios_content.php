<?php
/**
 * Contenido de la Página Servicios
 * DevSoft Build SAC
 */
?>

<!-- Hero Section -->
<section class="page-hero">
    <div class="container">
        <h1 class="page-title">Nuestros Servicios</h1>
        <p class="page-subtitle">Soluciones tecnológicas completas para impulsar tu negocio</p>
    </div>
</section>

<!-- Servicios Grid -->
<section class="section">
    <div class="container">
        <?php if (!empty($servicios)): ?>
        <div class="services-grid">
            <?php foreach ($servicios as $servicio): ?>
            <div class="service-card">
                <?php if (!empty($servicio['icono_servicio'])): ?>
                <div class="service-icon">
                    <i class="<?php echo htmlspecialchars($servicio['icono_servicio']); ?>"></i>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($servicio['imagen_servicio'])): ?>
                <div class="service-image">
                    <img src="<?php echo htmlspecialchars($servicio['imagen_servicio']); ?>" alt="<?php echo htmlspecialchars($servicio['nombre_servicio']); ?>">
                </div>
                <?php endif; ?>
                
                <h3 class="service-title"><?php echo htmlspecialchars($servicio['nombre_servicio']); ?></h3>
                
                <?php if (!empty($servicio['descripcion_corta_servicio'])): ?>
                <p class="service-description"><?php echo htmlspecialchars($servicio['descripcion_corta_servicio']); ?></p>
                <?php endif; ?>
                
                <?php if (!empty($servicio['precio_desde_servicio'])): ?>
                <div class="service-price">
                    Desde <?php echo formatPrice($servicio['precio_desde_servicio']); ?>
                </div>
                <?php endif; ?>
                
                <a href="<?php echo URL_SERV; ?>/servicio/<?php echo htmlspecialchars($servicio['slug_servicio']); ?>" class="btn btn-primary">
                    Ver Detalles <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <h3>No hay servicios disponibles</h3>
            <p>Próximamente agregaremos nuestros servicios.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title">¿Necesitas un servicio personalizado?</h2>
            <p class="cta-description">Contáctanos y te ayudaremos a encontrar la solución perfecta para tu proyecto</p>
            <div class="cta-actions">
                <a href="<?php echo URL_SERV; ?>/cotizar" class="btn btn-primary btn-lg">
                    <i class="fas fa-paper-plane"></i> Solicitar Cotización
                </a>
                <a href="<?php echo URL_SERV; ?>/contacto" class="btn btn-outline btn-lg">
                    <i class="fas fa-envelope"></i> Contactar
                </a>
            </div>
        </div>
    </div>
</section>

