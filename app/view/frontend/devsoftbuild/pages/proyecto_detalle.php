<?php
/**
 * Detalle de Proyecto
 * DevSoft Build SAC
 */
?>

<!-- Hero Section -->
<section class="page-hero">
    <div class="container">
        <nav class="breadcrumb">
            <a href="<?php echo URL_SERV; ?>">Inicio</a>
            <span>/</span>
            <a href="<?php echo URL_SERV; ?>/proyectos">Proyectos</a>
            <span>/</span>
            <span><?php echo htmlspecialchars($proyecto['nombre_proyecto']); ?></span>
        </nav>
        <h1 class="page-title"><?php echo htmlspecialchars($proyecto['nombre_proyecto']); ?></h1>
        <p class="page-subtitle"><?php echo htmlspecialchars($proyecto['descripcion_corta_proyecto'] ?? ''); ?></p>
    </div>
</section>

<!-- Proyecto Detalle -->
<section class="section">
    <div class="container">
        <div class="project-detail">
            <?php if (!empty($proyecto['imagen_principal_proyecto'])): ?>
            <div class="project-detail-image">
                <img src="<?php echo htmlspecialchars($proyecto['imagen_principal_proyecto']); ?>" alt="<?php echo htmlspecialchars($proyecto['nombre_proyecto']); ?>">
            </div>
            <?php endif; ?>
            
            <div class="project-detail-info">
                <div class="project-meta">
                    <span class="project-type"><?php echo ucfirst($proyecto['tipo_proyecto']); ?></span>
                    <?php if (!empty($proyecto['cliente_proyecto'])): ?>
                    <div class="project-client">
                        <strong>Cliente:</strong> <?php echo htmlspecialchars($proyecto['cliente_proyecto']); ?>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($proyecto['fecha_fin_proyecto'])): ?>
                    <div class="project-date">
                        <strong>Fecha:</strong> <?php echo date('F Y', strtotime($proyecto['fecha_fin_proyecto'])); ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($proyecto['descripcion_larga_proyecto'])): ?>
                <div class="project-detail-description">
                    <?php echo nl2br(htmlspecialchars($proyecto['descripcion_larga_proyecto'])); ?>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($tecnologias)): ?>
                <div class="project-technologies">
                    <h3>Tecnologías Utilizadas</h3>
                    <div class="technologies-list">
                        <?php foreach ($tecnologias as $tecnologia): ?>
                        <span class="tech-badge">
                            <?php if (!empty($tecnologia['logo_tecnologia'])): ?>
                            <img src="<?php echo htmlspecialchars($tecnologia['logo_tecnologia']); ?>" alt="<?php echo htmlspecialchars($tecnologia['nombre_tecnologia']); ?>" class="tech-logo">
                            <?php endif; ?>
                            <?php echo htmlspecialchars($tecnologia['nombre_tecnologia']); ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($proyecto['url_proyecto'])): ?>
                <div class="project-detail-actions">
                    <a href="<?php echo htmlspecialchars($proyecto['url_proyecto']); ?>" target="_blank" rel="noopener" class="btn btn-primary btn-lg">
                        <i class="fas fa-external-link-alt"></i> Ver Proyecto en Vivo
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if (!empty($imagenes)): ?>
        <div class="project-gallery">
            <h3>Galería del Proyecto</h3>
            <div class="gallery-grid">
                <?php foreach ($imagenes as $imagen): ?>
                <div class="gallery-item">
                    <img src="<?php echo htmlspecialchars($imagen['ruta_imagen_proyecto']); ?>" alt="<?php echo htmlspecialchars($imagen['titulo_imagen_proyecto'] ?? ''); ?>">
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Otros Proyectos -->
<?php
$otros_proyectos = ProyectoModel::getAll([
    'estado_proyecto' => 'completado',
    'exclude_id' => $proyecto['id_proyecto']
]);
if (!empty($otros_proyectos)):
    $otros_proyectos = array_slice($otros_proyectos, 0, 3);
?>
<section class="section section-dark">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Otros Proyectos</h2>
            <p class="section-description">Conoce más de nuestro trabajo</p>
        </div>
        <div class="projects-grid">
            <?php foreach ($otros_proyectos as $otro): ?>
            <div class="project-card">
                <?php if (!empty($otro['imagen_principal_proyecto'])): ?>
                <div class="project-image">
                    <img src="<?php echo htmlspecialchars($otro['imagen_principal_proyecto']); ?>" alt="<?php echo htmlspecialchars($otro['nombre_proyecto']); ?>">
                    <div class="project-overlay">
                        <a href="<?php echo URL_SERV; ?>/proyecto/<?php echo htmlspecialchars($otro['slug_proyecto']); ?>" class="btn btn-primary">
                            Ver Detalles
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                <div class="project-content">
                    <span class="project-type"><?php echo ucfirst($otro['tipo_proyecto']); ?></span>
                    <h3 class="project-title"><?php echo htmlspecialchars($otro['nombre_proyecto']); ?></h3>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>


