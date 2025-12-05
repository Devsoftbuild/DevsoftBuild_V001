<?php
/**
 * Footer del Panel de Administración
 */
?>
        </main>
    </div>
    
    <script src="<?php echo URL_SERV; ?>/dw-panel/assets/js/admin.js"></script>
    <?php if (isset($extra_js)): ?>
        <?php foreach ($extra_js as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>

