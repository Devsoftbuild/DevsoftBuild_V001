<?php
/**
 * Detalle de Servicio
 * DevSoft Build SAC
 */
?>

<!-- Hero Section -->
<section class="page-hero">
    <div class="container">
        <nav class="breadcrumb">
            <a href="<?php echo URL_SERV; ?>">Inicio</a>
            <span>/</span>
            <a href="<?php echo URL_SERV; ?>/servicios">Servicios</a>
            <span>/</span>
            <span><?php echo htmlspecialchars($servicio['nombre_servicio']); ?></span>
        </nav>
        <h1 class="page-title"><?php echo htmlspecialchars($servicio['nombre_servicio']); ?></h1>
    </div>
</section>

<!-- Servicio Detalle -->
<section class="section">
    <div class="container">
        <div class="service-detail">
            <div class="service-detail-content">
                <?php if (!empty($servicio['imagen_servicio'])): ?>
                <div class="service-detail-image">
                    <img src="<?php echo htmlspecialchars($servicio['imagen_servicio']); ?>" alt="<?php echo htmlspecialchars($servicio['nombre_servicio']); ?>">
                </div>
                <?php endif; ?>
                
                <?php if (!empty($servicio['descripcion_larga_servicio'])): ?>
                <div class="service-detail-description">
                    <?php echo nl2br(htmlspecialchars($servicio['descripcion_larga_servicio'])); ?>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($servicio['precio_desde_servicio'])): ?>
                <div class="service-detail-price">
                    <h3>Precio desde:</h3>
                    <div class="price-amount"><?php echo formatPrice($servicio['precio_desde_servicio']); ?></div>
                </div>
                <?php endif; ?>
                
                <div class="service-detail-actions">
                    <a href="<?php echo URL_SERV; ?>/cotizar?servicio=<?php echo $servicio['id_servicio']; ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane"></i> Solicitar Cotización
                    </a>
                    <a href="<?php echo URL_SERV; ?>/contacto" class="btn btn-outline btn-lg">
                        <i class="fas fa-envelope"></i> Contactar
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Otros Servicios -->
<?php
$otros_servicios = ServicioModel::getAll([
    'estado_servicio' => 1,
    'exclude_id' => $servicio['id_servicio']
]);
if (!empty($otros_servicios)):
    $otros_servicios = array_slice($otros_servicios, 0, 3);
?>
<section class="section section-dark">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Otros Servicios</h2>
            <p class="section-description">Conoce más sobre nuestros servicios</p>
        </div>
        <div class="services-grid">
            <?php foreach ($otros_servicios as $otro): ?>
            <div class="service-card">
                <?php if (!empty($otro['icono_servicio'])): ?>
                <div class="service-icon">
                    <i class="<?php echo htmlspecialchars($otro['icono_servicio']); ?>"></i>
                </div>
                <?php endif; ?>
                <h3 class="service-title"><?php echo htmlspecialchars($otro['nombre_servicio']); ?></h3>
                <?php if (!empty($otro['descripcion_corta_servicio'])): ?>
                <p class="service-description"><?php echo htmlspecialchars($otro['descripcion_corta_servicio']); ?></p>
                <?php endif; ?>
                <a href="<?php echo URL_SERV; ?>/servicio/<?php echo htmlspecialchars($otro['slug_servicio']); ?>" class="service-link">
                    Ver más <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>


