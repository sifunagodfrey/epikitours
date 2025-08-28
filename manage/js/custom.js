document.addEventListener('DOMContentLoaded', () => {
    const currentPath = window.location.pathname;

    // 1. Highlight  sidebar links
    document.querySelectorAll('.list-group-item').forEach(link => {
        const href = link.getAttribute('href');
        if (href && link.pathname === currentPath) {
            link.classList.add('active-link');

            // 2. If it's a dropdown child, open its parent collapse
            const collapseDiv = link.closest('.collapse');
            if (collapseDiv) {
                collapseDiv.classList.add('show');

                const parentToggle = document.querySelector(`[href="#${collapseDiv.id}"]`);
                parentToggle?.setAttribute('aria-expanded', 'true');
            }
        }
    });

    // 3. Sidebar toggle for mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarClose = document.getElementById('sidebarClose');
    const sidebar = document.getElementById('sidebar');

    sidebarToggle?.addEventListener('click', () => {
        sidebar?.classList.toggle('active');
        document.body.classList.toggle('sidebar-open');
    });

    sidebarClose?.addEventListener('click', () => {
        sidebar?.classList.remove('active');
        document.body.classList.remove('sidebar-open');
    });

    document.addEventListener('click', function (e) {
        if (
            document.body.classList.contains('sidebar-open') &&
            !sidebar.contains(e.target) &&
            !e.target.closest('#sidebarToggle')
        ) {
            sidebar?.classList.remove('active');
            document.body.classList.remove('sidebar-open');
        }
    });
});
