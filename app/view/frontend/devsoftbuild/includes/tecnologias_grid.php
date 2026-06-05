<?php
/**
 * Bloque: grid de tecnologías por categoría
 * Requiere: $tecnologias (array de filas activas desde TecnologiaModel)
 * Opcional: $tecnologias_section_class (clases extra en <section>)
 */
if (!defined('APP_PATH') || empty($tecnologias)) {
    return;
}

$labels_categoria = [
    'frontend' => 'Frontend',
    'backend' => 'Backend',
    'mobile' => 'Móvil',
    'database' => 'Base de datos',
    'cloud' => 'Cloud',
    'otro' => 'Otras',
];

$orden_categorias = ['frontend', 'backend', 'mobile', 'database', 'cloud', 'otro'];
$por_categoria = [];
foreach ($tecnologias as $tec) {
    $cat = $tec['categoria_tecnologia'] ?? 'otro';
    if ($cat === '') {
        $cat = 'otro';
    }
    $por_categoria[$cat][] = $tec;
}
$categorias_mostrar = [];
foreach ($orden_categorias as $cat) {
    if (!empty($por_categoria[$cat])) {
        $categorias_mostrar[] = $cat;
    }
}
foreach (array_keys($por_categoria) as $cat) {
    if (!in_array($cat, $categorias_mostrar, true)) {
        $categorias_mostrar[] = $cat;
    }
}
?>
<section class="section technologies-section <?php echo isset($tecnologias_section_class) ? htmlspecialchars($tecnologias_section_class) : ''; ?>">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Tecnologías que Dominamos</h2>
            <p class="section-description">Utilizamos las mejores herramientas y frameworks del mercado</p>
        </div>
        <div class="technologies-grid">
            <?php foreach ($categorias_mostrar as $categoria): ?>
            <div class="tech-category">
                <h3 class="tech-category-title"><?php echo htmlspecialchars($labels_categoria[$categoria] ?? ucfirst($categoria)); ?></h3>
                <div class="tech-items">
                    <?php foreach ($por_categoria[$categoria] as $tec): ?>
                    <div class="tech-item">
                        <?php if (!empty($tec['logo_tecnologia'])): ?>
                        <img src="<?php echo htmlspecialchars($tec['logo_tecnologia']); ?>" alt="<?php echo htmlspecialchars($tec['nombre_tecnologia']); ?>" class="tech-logo" loading="lazy">
                        <?php endif; ?>
                        <span><?php echo htmlspecialchars($tec['nombre_tecnologia']); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
