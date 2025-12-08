<?php
/**
 * Contenido de la Página Nosotros
 * DevSoft Build SAC
 */
?>

<!-- Hero Section -->
<section class="page-hero">
    <div class="container">
        <h1 class="page-title">Sobre Nosotros</h1>
        <p class="page-subtitle">Transformamos ideas en soluciones digitales innovadoras</p>
    </div>
</section>

<!-- Quiénes Somos -->
<section class="section">
    <div class="container">
        <div class="about-content">
            <div class="about-text">
                <h2>Quiénes Somos</h2>
                <p>DevSoft Build SAC es una empresa especializada en desarrollo de software, aplicaciones web y móviles. Nos dedicamos a crear soluciones tecnológicas innovadoras que impulsen el crecimiento digital de nuestros clientes.</p>
                <p>Con años de experiencia en el mercado, hemos desarrollado proyectos exitosos para empresas de diversos sectores, siempre priorizando la calidad, la innovación y la satisfacción del cliente.</p>
            </div>
        </div>
    </div>
</section>

<!-- Misión y Visión -->
<section class="section section-dark">
    <div class="container">
        <div class="mission-vision-grid">
            <div class="mission-vision-item">
                <div class="mv-icon">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3>Misión</h3>
                <p>Proporcionar soluciones tecnológicas innovadoras y de alta calidad que impulsen el crecimiento digital de nuestros clientes, superando sus expectativas en cada proyecto.</p>
            </div>
            <div class="mission-vision-item">
                <div class="mv-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <h3>Visión</h3>
                <p>Ser reconocidos como la empresa líder en desarrollo de software en la región, destacando por nuestra innovación, calidad y compromiso con la excelencia.</p>
            </div>
        </div>
    </div>
</section>

<!-- Valores -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Nuestros Valores</h2>
        </div>
        <div class="values-grid">
            <div class="value-item">
                <i class="fas fa-lightbulb"></i>
                <h3>Innovación</h3>
                <p>Utilizamos las últimas tecnologías y metodologías para crear soluciones vanguardistas.</p>
            </div>
            <div class="value-item">
                <i class="fas fa-award"></i>
                <h3>Calidad</h3>
                <p>Nos comprometemos a entregar productos de la más alta calidad en cada proyecto.</p>
            </div>
            <div class="value-item">
                <i class="fas fa-handshake"></i>
                <h3>Compromiso</h3>
                <p>Estamos comprometidos con el éxito de nuestros clientes y sus objetivos.</p>
            </div>
            <div class="value-item">
                <i class="fas fa-eye"></i>
                <h3>Transparencia</h3>
                <p>Mantenemos una comunicación clara y transparente en todos nuestros procesos.</p>
            </div>
        </div>
    </div>
</section>

<!-- Estadísticas -->
<?php if (!empty($estadisticas)): ?>
<section class="stats-section section-dark">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">En Números</h2>
        </div>
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
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Tecnologías -->
<?php if (!empty($tecnologias)): ?>
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Tecnologías que Dominamos</h2>
            <p class="section-description">Utilizamos las mejores herramientas y frameworks del mercado</p>
        </div>
        <div class="technologies-grid">
            <?php 
            $categorias = ['frontend', 'backend', 'mobile', 'database', 'cloud'];
            foreach ($categorias as $categoria): 
                $tecs_categoria = array_filter($tecnologias, function($t) use ($categoria) {
                    return $t['categoria_tecnologia'] === $categoria;
                });
                if (!empty($tecs_categoria)):
            ?>
            <div class="tech-category">
                <h3 class="tech-category-title"><?php echo ucfirst($categoria); ?></h3>
                <div class="tech-items">
                    <?php foreach ($tecs_categoria as $tec): ?>
                    <div class="tech-item">
                        <?php if (!empty($tec['logo_tecnologia'])): ?>
                        <img src="<?php echo htmlspecialchars($tec['logo_tecnologia']); ?>" alt="<?php echo htmlspecialchars($tec['nombre_tecnologia']); ?>" class="tech-logo">
                        <?php endif; ?>
                        <span><?php echo htmlspecialchars($tec['nombre_tecnologia']); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php 
                endif;
            endforeach; 
            ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title">¿Listo para trabajar con nosotros?</h2>
            <p class="cta-description">Contáctanos y descubre cómo podemos ayudarte a alcanzar tus objetivos</p>
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


