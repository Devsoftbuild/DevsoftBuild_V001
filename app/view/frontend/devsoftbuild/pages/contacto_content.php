<?php
/**
 * Contenido de la Página Contacto
 * DevSoft Build SAC
 */
?>

<!-- Hero Section -->
<section class="page-hero">
    <div class="container">
        <h1 class="page-title">Contacto</h1>
        <p class="page-subtitle">Estamos aquí para ayudarte. Hablemos sobre tu proyecto</p>
    </div>
</section>

<!-- Contacto -->
<section class="section">
    <div class="container">
        <div class="contact-wrapper">
            <div class="contact-info">
                <h2>Información de Contacto</h2>
                <p>Puedes contactarnos a través de cualquiera de estos medios:</p>
                
                <div class="contact-items">
                    <?php if ($email_contacto): ?>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Email</h3>
                            <a href="mailto:<?php echo htmlspecialchars($email_contacto); ?>"><?php echo htmlspecialchars($email_contacto); ?></a>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($telefono_contacto): ?>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Teléfono</h3>
                            <a href="tel:<?php echo htmlspecialchars($telefono_contacto); ?>"><?php echo htmlspecialchars($telefono_contacto); ?></a>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($direccion_contacto): ?>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Dirección</h3>
                            <p><?php echo htmlspecialchars($direccion_contacto); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($horario_atencion): ?>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Horario de Atención</h3>
                            <p><?php echo htmlspecialchars($horario_atencion); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="contact-form-wrapper">
                <h2>Envíanos un Mensaje</h2>
                <form action="<?php echo URL_SERV; ?>/contacto" method="POST" class="contact-form">
                    <div class="form-group">
                        <label for="nombre">Nombre Completo *</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" id="telefono" name="telefono">
                    </div>
                    
                    <div class="form-group">
                        <label for="empresa">Empresa</label>
                        <input type="text" id="empresa" name="empresa">
                    </div>
                    
                    <div class="form-group">
                        <label for="tipo_proyecto">Tipo de Proyecto</label>
                        <select id="tipo_proyecto" name="tipo_proyecto">
                            <option value="">Selecciona una opción</option>
                            <option value="web">Desarrollo Web</option>
                            <option value="mobile">Aplicación Móvil</option>
                            <option value="software">Software a Medida</option>
                            <option value="consultoria">Consultoría</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="mensaje">Mensaje *</label>
                        <textarea id="mensaje" name="mensaje" rows="5" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane"></i> Enviar Mensaje
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>


