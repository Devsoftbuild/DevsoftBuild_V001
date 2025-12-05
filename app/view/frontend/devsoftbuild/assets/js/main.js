/**
 * JavaScript Principal
 * DevSoft Build SAC
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Mobile Menu Toggle
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const navbarMenu = document.getElementById('navbarMenu');
    
    if (mobileMenuToggle && navbarMenu) {
        mobileMenuToggle.addEventListener('click', function() {
            navbarMenu.classList.toggle('active');
            this.classList.toggle('active');
        });
    }
    
    // Navbar scroll effect
    const navbar = document.getElementById('navbar');
    let lastScroll = 0;
    
    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll > 100) {
            navbar.style.background = 'rgba(10, 26, 31, 0.98)';
            navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.3)';
        } else {
            navbar.style.background = 'rgba(10, 26, 31, 0.95)';
            navbar.style.boxShadow = 'none';
        }
        
        lastScroll = currentScroll;
    });
    
    const statNumbers = document.querySelectorAll('.stat-number');
    
    const animateCounter = (element) => {
        const target = parseInt(element.getAttribute('data-target'));
        const duration = 2000; 
        const increment = target / (duration / 16); // 60fps
        let current = 0;
        
        const updateCounter = () => {
            current += increment;
            if (current < target) {
                element.textContent = Math.floor(current);
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = target;
            }
        };
        
        updateCounter();
    };
    
    // Intersection Observer para animar contadores cuando son visibles
    const observerOptions = {
        threshold: 0.5
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statNumber = entry.target;
                if (!statNumber.classList.contains('animated')) {
                    statNumber.classList.add('animated');
                    animateCounter(statNumber);
                }
            }
        });
    }, observerOptions);
    
    statNumbers.forEach(stat => {
        observer.observe(stat);
    });
    
    // Smooth scroll para enlaces internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
    
    // Cerrar mobile menu al hacer click en un enlace
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (navbarMenu) {
                navbarMenu.classList.remove('active');
            }
            if (mobileMenuToggle) {
                mobileMenuToggle.classList.remove('active');
            }
        });
    });
    
    // Auto-hide flash messages
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            message.style.transform = 'translateY(-100%)';
            setTimeout(() => {
                message.remove();
            }, 300);
        }, 5000);
    });
    
});

