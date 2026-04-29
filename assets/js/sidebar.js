/**
 * Mobile Sidebar Toggle - Professional Task Manager
 * Smooth hamburger menu for responsive design
 */

document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar-custom');
    const body = document.body;
    const overlay = document.createElement('div');
    
    // Create overlay
    overlay.className = 'sidebar-overlay';
    document.querySelector('.app-wrapper').appendChild(overlay);
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('open');
            body.classList.toggle('sidebar-open');
        });
    }
    
    // Close on overlay click
    overlay.addEventListener('click', function() {
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
        body.classList.remove('sidebar-open');
    });
    
    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
            body.classList.remove('sidebar-open');
        }
    });
    
    // Close sidebar on window resize > 1024px
    window.addEventListener('resize', function() {
        if (window.innerWidth > 1024) {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
            body.classList.remove('sidebar-open');
        }
    });
});
