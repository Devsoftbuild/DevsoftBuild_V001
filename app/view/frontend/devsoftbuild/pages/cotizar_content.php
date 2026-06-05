<?php
/**
 * Contenido de la Página Cotizar
 * DevSoft Build SAC
 */

// Mostrar mensajes flash
$flash_message = getFlashMessage();
?>

<!-- Hero Section -->
<section class="page-hero">
    <div class="container">
        <h1 class="page-title">Solicitar Cotización</h1>
        <p class="page-subtitle">Cuéntanos sobre tu proyecto y te enviaremos una propuesta personalizada</p>
    </div>
</section>

<!-- Formulario de Cotización -->
<section class="section">
    <div class="container">
        <?php if ($flash_message): ?>
            <div class="alert alert-<?php echo $flash_message['type']; ?>" style="margin-bottom: 2rem; padding: 1rem; border-radius: 8px; background: <?php echo $flash_message['type'] === 'success' ? '#d4edda' : '#f8d7da'; ?>; color: <?php echo $flash_message['type'] === 'success' ? '#155724' : '#721c24'; ?>; border: 1px solid <?php echo $flash_message['type'] === 'success' ? '#c3e6cb' : '#f5c6cb'; ?>;">
                <?php echo htmlspecialchars($flash_message['message']); ?>
            </div>
        <?php endif; ?>
        
        <div class="quote-form-wrapper">
            <form action="<?php echo URL_SERV; ?>/cotizar" method="POST" class="quote-form" id="cotizarForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre Completo *</label>
                        <input type="text" id="nombre" name="nombre" required placeholder="Tu nombre completo">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required placeholder="tu@email.com">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" id="telefono" name="telefono" placeholder="+51 999 999 999">
                    </div>
                    
                    <div class="form-group">
                        <label for="empresa">Empresa</label>
                        <input type="text" id="empresa" name="empresa" placeholder="Nombre de tu empresa">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="tipo_proyecto">Tipo de Proyecto *</label>
                        <select id="tipo_proyecto" name="tipo_proyecto" required>
                            <option value="">Selecciona una opción</option>
                            <option value="web">Desarrollo Web</option>
                            <option value="mobile">Aplicación Móvil</option>
                            <option value="software">Software a Medida</option>
                            <option value="ecommerce">E-commerce</option>
                            <option value="consultoria">Consultoría Tecnológica</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="servicio_id">Servicio Específico (Opcional)</label>
                        <select id="servicio_id" name="servicio_id">
                            <option value="">Selecciona un servicio</option>
                            <?php foreach ($servicios as $serv): ?>
                            <option value="<?php echo $serv['id_servicio']; ?>" <?php echo $servicio_seleccionado == $serv['id_servicio'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($serv['nombre_servicio']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="descripcion">Descripción del Proyecto *</label>
                    <textarea id="descripcion" name="descripcion" rows="6" required placeholder="Describe tu proyecto, objetivos, funcionalidades principales, etc."></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="presupuesto">Presupuesto Estimado</label>
                        <select id="presupuesto" name="presupuesto">
                            <option value="">Selecciona un rango</option>
                            <option value="menos-5000">Menos de S/. 5,000</option>
                            <option value="5000-10000">S/. 5,000 - S/. 10,000</option>
                            <option value="10000-25000">S/. 10,000 - S/. 25,000</option>
                            <option value="25000-50000">S/. 25,000 - S/. 50,000</option>
                            <option value="mas-50000">Más de S/. 50,000</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="plazo">Plazo Estimado</label>
                        <select id="plazo" name="plazo">
                            <option value="">Selecciona un plazo</option>
                            <option value="1-3-meses">1 - 3 meses</option>
                            <option value="3-6-meses">3 - 6 meses</option>
                            <option value="6-12-meses">6 - 12 meses</option>
                            <option value="mas-12-meses">Más de 12 meses</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane"></i> Enviar Solicitud de Cotización
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Proceso de Cotización -->
<section class="section section-dark">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">¿Qué pasa después?</h2>
            <p class="section-description">Nuestro proceso de cotización es simple y rápido</p>
        </div>
        <div class="quote-process">
            <div class="quote-process-item">
                <div class="quote-process-number">1</div>
                <h3>Envías tu solicitud</h3>
                <p>Completa el formulario con los detalles de tu proyecto</p>
            </div>
            <div class="quote-process-item">
                <div class="quote-process-number">2</div>
                <h3>Revisamos tu proyecto</h3>
                <p>Analizamos tus necesidades y requerimientos</p>
            </div>
            <div class="quote-process-item">
                <div class="quote-process-number">3</div>
                <h3>Te enviamos la propuesta</h3>
                <p>Recibirás una cotización detallada y personalizada</p>
            </div>
            <div class="quote-process-item">
                <div class="quote-process-number">4</div>
                <h3>Comenzamos a trabajar</h3>
                <p>Si aceptas, iniciamos el desarrollo de tu proyecto</p>
            </div>
        </div>
    </div>
</section>
