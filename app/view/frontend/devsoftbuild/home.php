<?php
/**
 * Home / Landing Page
 * DevSoft Build SAC
 */

// Configuración de la página
$page_title = 'Inicio';
$page_description = 'Desarrollo de software, aplicaciones web y móviles. Soluciones tecnológicas innovadoras para tu negocio.';
$current_page = 'home';

// Cargar datos
$banners = BannerModel::getByPosicion('home_hero');
$servicios = ServicioModel::getDestacados(4);
$proyectos = ProyectoModel::getDestacados(6);
$estadisticas = EstadisticaModel::getAll(['estado_estadistica' => 1]);
$testimonios = TestimonioModel::getDestacados(3);
$procesos = ProcesoModel::getAll(['estado_proceso' => 1]);
$tecnologias = TecnologiaModel::getAll(['estado_tecnologia' => 1]);

// Contenido de la página
$content_file = TEMPLATE_PATH . '/pages/home_content.php';

// Incluir layout (que incluirá header, contenido y footer)
include TEMPLATE_PATH . '/includes/layout.php';
?>

