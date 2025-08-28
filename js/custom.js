//  nav links and active links
document.addEventListener('DOMContentLoaded', () => {
    // Get current URL path
    const currentPath = window.location.pathname.split('/').pop();
    // Loop through all nav links and check for active
    document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });

    // For dashboard sidebar list group
    document.querySelectorAll('.list-group-item').forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active-sidebar');
        }
    });
});
