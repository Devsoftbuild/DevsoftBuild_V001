# Sistema de Plantillas - Frontend

## Estructura

```
app/view/frontend/
├── plantilla_1/          # Plantilla activa actualmente
│   ├── includes/         # Componentes reutilizables
│   │   ├── header.php    # Header/Navbar
│   │   ├── footer.php    # Footer
│   │   └── layout.php    # Layout principal
│   ├── pages/            # Páginas específicas
│   │   └── home_content.php
│   ├── utilices/         # Recursos estáticos
│   │   ├── css/
│   │   │   ├── main.css      # Estilos principales
│   │   │   └── responsive.css # Media queries
│   │   └── js/
│   │       └── main.js       # JavaScript principal
│   └── home.php          # Página de inicio
└── plantilla_2/          # Plantilla alternativa (futuro)
```

## Configuración

La plantilla activa se define en `app/config.php`:

```php
define('TEMPLATE_ACTIVA', 'plantilla_1'); // Cambiar a 'plantilla_2' para otra plantilla
```

## Uso de la Plantilla

### Crear una nueva página

```php
<?php
// Configuración de la página
$page_title = 'Título de la Página';
$page_description = 'Descripción para SEO';
$current_page = 'servicios'; // Para activar el menú correspondiente

// Contenido de la página
$content_file = TEMPLATE_PATH . '/pages/mi_pagina_content.php';

// Incluir layout
include TEMPLATE_PATH . '/includes/layout.php';
?>
```

### Variables disponibles en los templates

- `$page_title`: Título de la página (aparece en `<title>`)
- `$page_description`: Descripción para meta tags
- `$current_page`: Página actual (para activar menú)
- `$content_file`: Ruta al archivo de contenido
- `$content`: Contenido directo (alternativa a `$content_file`)
- `$extra_css`: Array de archivos CSS adicionales
- `$extra_js`: Array de archivos JS adicionales

### Ejemplo completo

**mi_pagina.php:**
```php
<?php
$page_title = 'Servicios';
$page_description = 'Nuestros servicios de estética';
$current_page = 'servicios';
$content_file = TEMPLATE_PATH . '/pages/servicios_content.php';
include TEMPLATE_PATH . '/includes/layout.php';
?>
```

**pages/servicios_content.php:**
```php
<section class="section">
    <div class="container">
        <h1>Nuestros Servicios</h1>
        <!-- Contenido aquí -->
    </div>
</section>
```

## Paleta de Colores

La plantilla usa variables CSS para fácil personalización:

```css
--color-primary: #9B59B6;      /* Morado principal */
--color-primary-light: #BB8FCE;
--color-primary-dark: #7D3C98;
--color-secondary: #FFFFFF;
--color-accent: #FFD700;        /* Dorado */
--color-accent-alt: #FF7F50;    /* Coral */
--color-text: #2C3E50;
```

## Componentes Disponibles

### Botones
```html
<a href="#" class="btn btn-primary">Primario</a>
<a href="#" class="btn btn-secondary">Secundario</a>
<a href="#" class="btn btn-outline">Outline</a>
```

### Cards
```html
<div class="card">
    <img src="..." class="card-image" alt="...">
    <h3 class="card-title">Título</h3>
    <p class="card-description">Descripción</p>
    <div class="card-footer">
        <span class="card-price">$50</span>
        <a href="#" class="btn btn-outline">Ver más</a>
    </div>
</div>
```

### Grid de Cards
```html
<div class="cards-grid">
    <div class="card">...</div>
    <div class="card">...</div>
    <div class="card">...</div>
</div>
```

## Responsive Design

La plantilla es completamente responsive con breakpoints:

- **Mobile**: 320px - 768px
- **Tablet**: 769px - 1024px
- **Desktop**: 1025px+

## Cambiar de Plantilla

Para cambiar a otra plantilla, simplemente modifica `app/config.php`:

```php
define('TEMPLATE_ACTIVA', 'plantilla_2');
```

Asegúrate de que la estructura de `plantilla_2` sea similar a `plantilla_1` para que funcione correctamente.

## JavaScript

El archivo `main.js` incluye:

- Menú móvil responsive
- Botón "Volver arriba"
- Scroll suave
- Lazy loading de imágenes
- Funciones de validación
- Utilidades (debounce, throttle)

## Notas

- Todas las rutas usan `URL_SERV` definido en `config.php`
- Los assets (CSS/JS) se cargan desde `TEMPLATE_URL`
- El layout maneja automáticamente header y footer
- El menú se activa según `$current_page`

