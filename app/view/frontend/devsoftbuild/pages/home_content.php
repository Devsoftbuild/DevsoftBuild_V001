<?php
/**
 * Contenido de la Página Home
 * DevSoft Build SAC
 */
?>

<!-- Hero Section -->
<section class="hero-section">
    <?php if (!empty($banners)): ?>
        <div class="hero-slider">
            <?php foreach ($banners as $banner): ?>
            <div class="hero-slide" style="background-image: url('<?php echo htmlspecialchars($banner['imagen_banner']); ?>');">
                <div class="hero-overlay"></div>
                <div class="container">
                    <div class="hero-content">
                        <?php if (!empty($banner['titulo_banner'])): ?>
                        <h1 class="hero-title"><?php echo htmlspecialchars($banner['titulo_banner']); ?></h1>
                        <?php endif; ?>
                        <?php if (!empty($banner['subtitulo_banner'])): ?>
                        <p class="hero-subtitle"><?php echo htmlspecialchars($banner['subtitulo_banner']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($banner['descripcion_banner'])): ?>
                        <p class="hero-description"><?php echo htmlspecialchars($banner['descripcion_banner']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($banner['texto_boton_banner']) && !empty($banner['enlace_banner'])): ?>
                        <a href="<?php echo htmlspecialchars($banner['enlace_banner']); ?>" class="btn btn-primary btn-lg">
                            <?php echo htmlspecialchars($banner['texto_boton_banner']); ?>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- Hero por defecto si no hay banners -->
        <div class="hero-default">
            <div class="hero-overlay"></div>
            <div class="container">
                <div class="hero-content">
                    <h1 class="hero-title">Transformamos Ideas en Soluciones Digitales</h1>
                    <p class="hero-subtitle">Desarrollo Web y Aplicaciones Móviles de Alto Rendimiento</p>
                    <p class="hero-description">Creamos software innovador que impulsa el crecimiento de tu negocio</p>
                    <div class="hero-actions">
                        <a href="<?php echo URL_SERV; ?>/cotizar" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane"></i> Solicitar Cotización
                        </a>
                        <a href="<?php echo URL_SERV; ?>/proyectos" class="btn btn-outline btn-lg">
                            <i class="fas fa-eye"></i> Ver Portafolio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>

<!-- Estadísticas -->
<?php if (!empty($estadisticas)): ?>
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <?php foreach ($estadisticas as $stat): ?>
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="<?php echo htmlspecialchars($stat['icono_estadistica'] ?? 'fas fa-chart-line'); ?>"></i>
                </div>
                <div class="stat-value">
                    <span class="stat-number" data-target="<?php echo $stat['valor_estadistica']; ?>">0</span>
                    <?php if (!empty($stat['sufijo_estadistica'])): ?>
                    <span class="stat-suffix"><?php echo htmlspecialchars($stat['sufijo_estadistica']); ?></span>
                    <?php endif; ?>
                </div>
                <h3 class="stat-title"><?php echo htmlspecialchars($stat['nombre_estadistica']); ?></h3>
                <?php if (!empty($stat['descripcion_estadistica'])): ?>
                <p class="stat-description"><?php echo htmlspecialchars($stat['descripcion_estadistica']); ?></p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Servicios -->
<?php if (!empty($servicios)): ?>
<section class="services-section section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Nuestros Servicios</h2>
            <p class="section-description">Soluciones tecnológicas completas para tu negocio</p>
        </div>
        <div class="services-grid">
            <?php foreach ($servicios as $servicio): ?>
            <div class="service-card">
                <?php if (!empty($servicio['icono_servicio'])): ?>
                <div class="service-icon">
                    <i class="<?php echo htmlspecialchars($servicio['icono_servicio']); ?>"></i>
                </div>
                <?php endif; ?>
                <h3 class="service-title"><?php echo htmlspecialchars($servicio['nombre_servicio']); ?></h3>
                <?php if (!empty($servicio['descripcion_corta_servicio'])): ?>
                <p class="service-description"><?php echo htmlspecialchars($servicio['descripcion_corta_servicio']); ?></p>
                <?php endif; ?>
                <a href="<?php echo URL_SERV; ?>/servicio/<?php echo htmlspecialchars($servicio['slug_servicio']); ?>" class="service-link">
                    Ver más <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="section-footer">
            <a href="<?php echo URL_SERV; ?>/servicios" class="btn btn-outline">Ver Todos los Servicios</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Proceso de Trabajo -->
<?php if (!empty($procesos)): ?>
<section class="process-section section section-dark">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Cómo Trabajamos</h2>
            <p class="section-description">Un proceso estructurado para garantizar el éxito de tu proyecto</p>
        </div>
        <div class="process-timeline">
            <?php foreach ($procesos as $proceso): ?>
            <div class="process-item">
                <div class="process-number"><?php echo $proceso['numero_proceso']; ?></div>
                <?php if (!empty($proceso['icono_proceso'])): ?>
                <div class="process-icon">
                    <i class="<?php echo htmlspecialchars($proceso['icono_proceso']); ?>"></i>
                </div>
                <?php endif; ?>
                <h3 class="process-title"><?php echo htmlspecialchars($proceso['nombre_proceso']); ?></h3>
                <?php if (!empty($proceso['descripcion_proceso'])): ?>
                <p class="process-description"><?php echo htmlspecialchars($proceso['descripcion_proceso']); ?></p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Proyectos -->
<?php if (!empty($proyectos)): ?>
<section class="projects-section section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Nuestros Proyectos</h2>
            <p class="section-description">Casos de éxito que demuestran nuestra experiencia</p>
        </div>
        <div class="projects-grid">
            <?php foreach ($proyectos as $proyecto): ?>
            <div class="project-card">
                <?php if (!empty($proyecto['imagen_principal_proyecto'])): ?>
                <div class="project-image">
                    <img src="<?php echo htmlspecialchars($proyecto['imagen_principal_proyecto']); ?>" alt="<?php echo htmlspecialchars($proyecto['nombre_proyecto']); ?>">
                    <div class="project-overlay">
                        <a href="<?php echo URL_SERV; ?>/proyecto/<?php echo htmlspecialchars($proyecto['slug_proyecto']); ?>" class="btn btn-primary">
                            Ver Detalles
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                <div class="project-content">
                    <span class="project-type"><?php echo ucfirst($proyecto['tipo_proyecto']); ?></span>
                    <h3 class="project-title"><?php echo htmlspecialchars($proyecto['nombre_proyecto']); ?></h3>
                    <?php if (!empty($proyecto['descripcion_corta_proyecto'])): ?>
                    <p class="project-description"><?php echo truncateText($proyecto['descripcion_corta_proyecto'], 100); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="section-footer">
            <a href="<?php echo URL_SERV; ?>/proyectos" class="btn btn-outline">Ver Todos los Proyectos</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Testimonios -->
<?php if (!empty($testimonios)): ?>
<section class="testimonials-section section section-dark">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Lo que Dicen Nuestros Clientes</h2>
            <p class="section-description">Testimonios de quienes confían en nosotros</p>
        </div>
        <div class="testimonials-grid">
            <?php foreach ($testimonios as $testimonio): ?>
            <div class="testimonial-card">
                <div class="testimonial-rating">
                    <?php for ($i = 0; $i < $testimonio['calificacion_testimonio']; $i++): ?>
                    <i class="fas fa-star"></i>
                    <?php endfor; ?>
                </div>
                <p class="testimonial-text">"<?php echo htmlspecialchars($testimonio['testimonio_texto']); ?>"</p>
                <div class="testimonial-author">
                    <?php if (!empty($testimonio['foto_testimonio'])): ?>
                    <img src="<?php echo htmlspecialchars($testimonio['foto_testimonio']); ?>" alt="<?php echo htmlspecialchars($testimonio['nombre_testimonio']); ?>" class="testimonial-photo">
                    <?php endif; ?>
                    <div class="testimonial-info">
                        <h4 class="testimonial-name"><?php echo htmlspecialchars($testimonio['nombre_testimonio']); ?></h4>
                        <?php if (!empty($testimonio['cargo_testimonio'])): ?>
                        <p class="testimonial-position"><?php echo htmlspecialchars($testimonio['cargo_testimonio']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($testimonio['empresa_testimonio'])): ?>
                        <p class="testimonial-company"><?php echo htmlspecialchars($testimonio['empresa_testimonio']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title">¿Listo para Transformar tu Negocio?</h2>
            <p class="cta-description">Contáctanos hoy y descubre cómo podemos ayudarte a alcanzar tus objetivos digitales</p>
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

