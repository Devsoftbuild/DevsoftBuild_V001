<?php
/**
 * Contenido de la Página Proyectos
 * DevSoft Build SAC
 */
?>

<!-- Hero Section -->
<section class="page-hero">
    <div class="container">
        <h1 class="page-title">Nuestros Proyectos</h1>
        <p class="page-subtitle">Casos de éxito que demuestran nuestra experiencia</p>
    </div>
</section>

<!-- Filtros -->
<section class="section">
    <div class="container">
        <div class="projects-filters">
            <a href="<?php echo URL_SERV; ?>/proyectos" class="filter-btn <?php echo empty($filtro_tipo) ? 'active' : ''; ?>">
                Todos
            </a>
            <?php foreach ($tipos_proyectos as $tipo): ?>
            <a href="<?php echo URL_SERV; ?>/proyectos?tipo=<?php echo $tipo; ?>" class="filter-btn <?php echo $filtro_tipo === $tipo ? 'active' : ''; ?>">
                <?php echo ucfirst($tipo); ?>
            </a>
            <?php endforeach; ?>
        </div>
        
        <?php if (!empty($proyectos)): ?>
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
                    <p class="project-description"><?php echo truncateText($proyecto['descripcion_corta_proyecto'], 120); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($proyecto['url_proyecto'])): ?>
                    <a href="<?php echo htmlspecialchars($proyecto['url_proyecto']); ?>" target="_blank" rel="noopener" class="project-link">
                        <i class="fas fa-external-link-alt"></i> Ver Proyecto
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-folder-open"></i>
            <h3>No hay proyectos disponibles</h3>
            <p>Próximamente agregaremos nuestros proyectos.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title">¿Tienes un proyecto en mente?</h2>
            <p class="cta-description">Hablemos sobre cómo podemos ayudarte a hacerlo realidad</p>
            <div class="cta-actions">
                <a href="<?php echo URL_SERV; ?>/cotizar" class="btn btn-primary btn-lg">
                    <i class="fas fa-paper-plane"></i> Solicitar Cotización
                </a>
            </div>
        </div>
    </div>
</section>

