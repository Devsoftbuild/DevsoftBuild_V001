/**
 * JavaScript del Panel de Administración
 */

(function() {
    'use strict';

    // Toggle Sidebar
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('adminSidebar');
    const sidebarBackdrop = document.getElementById('adminSidebarBackdrop');

    function syncSidebarBackdrop() {
        if (!sidebar || !sidebarBackdrop) return;
        var mobile = window.innerWidth <= 768;
        if (mobile && sidebar.classList.contains('active')) {
            sidebarBackdrop.classList.add('is-visible');
            document.body.classList.add('admin-sidebar-open');
        } else {
            sidebarBackdrop.classList.remove('is-visible');
            document.body.classList.remove('admin-sidebar-open');
        }
    }

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.toggle('active');
            syncSidebarBackdrop();
        });
    }

    if (sidebarBackdrop && sidebar) {
        sidebarBackdrop.addEventListener('click', function() {
            sidebar.classList.remove('active');
            syncSidebarBackdrop();
        });
    }

    window.addEventListener('resize', function() {
        if (window.innerWidth > 768 && sidebar) {
            sidebar.classList.remove('active');
        }
        syncSidebarBackdrop();
    });

    // Cerrar sidebar al hacer clic fuera (mobile)
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768 && sidebar && sidebarToggle) {
            if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target) && !(sidebarBackdrop && sidebarBackdrop.contains(e.target))) {
                sidebar.classList.remove('active');
                syncSidebarBackdrop();
            }
        }
    });

    // User Menu Dropdown
    const userMenu = document.getElementById('userMenu');
    const userMenuToggle = document.getElementById('userMenuToggle');
    const userDropdown = document.getElementById('userDropdown');
    
    if (userMenuToggle && userDropdown) {
        userMenuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            userMenu.classList.toggle('active');
        });

        // Cerrar dropdown al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (userMenu && !userMenu.contains(e.target)) {
                userMenu.classList.remove('active');
            }
        });

        // Cerrar dropdown al hacer clic en un item
        const dropdownItems = userDropdown.querySelectorAll('.dropdown-item');
        dropdownItems.forEach(item => {
            item.addEventListener('click', function() {
                userMenu.classList.remove('active');
            });
        });
    }

    syncSidebarBackdrop();

    console.log('Panel de Administración cargado');
})();

