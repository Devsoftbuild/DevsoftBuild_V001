<?php
?>
<div class="card">
    <div class="card-header">
        <h2>Reportes y Estadísticas</h2>
        <form method="GET" style="display: inline-flex; gap: 10px; align-items: center;">
            <input type="date" name="fecha_desde" value="<?php echo $fecha_desde; ?>">
            <span>a</span>
            <input type="date" name="fecha_hasta" value="<?php echo $fecha_hasta; ?>">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filtrar</button>
        </form>
    </div>
    
    <div class="metrics-grid" style="margin-bottom: 30px;">
        <div class="metric-card">
            <div class="metric-icon" style="background: linear-gradient(135deg, #27AE60, #229954);">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="metric-info">
                <h3><?php echo formatPrice($total_ingresos); ?></h3>
                <p>Total Ingresos</p>
            </div>
        </div>
        <div class="metric-card">
            <div class="metric-icon" style="background: linear-gradient(135deg, #3498DB, #2980B9);">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="metric-info">
                <h3><?php echo count($servicios_vendidos); ?></h3>
                <p>Servicios Únicos</p>
            </div>
        </div>
        <div class="metric-card">
            <div class="metric-icon" style="background: linear-gradient(135deg, #9B59B6, #7D3C98);">
                <i class="fas fa-users"></i>
            </div>
            <div class="metric-info">
                <h3><?php echo count($clientes_frecuentes); ?></h3>
                <p>Clientes Activos</p>
            </div>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px;">
        <div class="card">
            <h3 style="margin-bottom: 15px;">Servicios Más Vendidos</h3>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr><th>Servicio</th><th>Cantidad</th><th>Total</th></tr>
                    </thead>
                    <tbody>
                        <?php if (empty($servicios_vendidos)): ?>
                            <tr><td colspan="3" class="text-center">No hay datos</td></tr>
                        <?php else: ?>
                            <?php foreach ($servicios_vendidos as $sv): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($sv['nombre']); ?></td>
                                    <td><?php echo $sv['cantidad']; ?></td>
                                    <td><?php echo formatPrice($sv['total']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card">
            <h3 style="margin-bottom: 15px;">Clientes Frecuentes</h3>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr><th>Cliente</th><th>Citas</th><th>Total</th></tr>
                    </thead>
                    <tbody>
                        <?php if (empty($clientes_frecuentes)): ?>
                            <tr><td colspan="3" class="text-center">No hay datos</td></tr>
                        <?php else: ?>
                            <?php foreach ($clientes_frecuentes as $cf): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($cf['nombre'] . ' ' . $cf['apellido']); ?></td>
                                    <td><?php echo $cf['citas']; ?></td>
                                    <td><?php echo formatPrice($cf['total']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

