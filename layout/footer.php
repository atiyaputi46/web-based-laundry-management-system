    <!-- End content area -->
    </main>
  </div> <!-- End mainContent -->
</div> <!-- End app -->

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Sidebar & UI Interaction Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const sidebar = document.getElementById('sidebar');
  const mainContent = document.getElementById('mainContent');
  const sidebarToggle = document.getElementById('sidebarToggle');
  const toggleIcon = document.getElementById('toggleIcon');
  const mobileNavToggle = document.getElementById('mobileNavToggle');
  const sidebarBackdrop = document.getElementById('sidebarBackdrop');

  // Desktop Sidebar State from localStorage
  const isMinimized = localStorage.getItem('sidebar_minimized') === 'true';
  if (isMinimized && window.innerWidth >= 992) {
    sidebar.classList.add('minimized');
    mainContent.classList.add('minimized');
    if (toggleIcon) toggleIcon.className = 'bi bi-chevron-right';
  }

  // Desktop Toggle Button Handler
  if (sidebarToggle) {
    sidebarToggle.addEventListener('click', function() {
      const willMinimize = !sidebar.classList.contains('minimized');
      sidebar.classList.toggle('minimized', willMinimize);
      mainContent.classList.toggle('minimized', willMinimize);
      
      if (toggleIcon) {
        toggleIcon.className = willMinimize ? 'bi bi-chevron-right' : 'bi bi-chevron-left';
      }
      
      localStorage.setItem('sidebar_minimized', willMinimize);
    });
  }

  // Mobile Toggle Button Handler
  if (mobileNavToggle) {
    mobileNavToggle.addEventListener('click', function() {
      sidebar.classList.toggle('show');
      sidebarBackdrop.classList.toggle('show');
    });
  }

  // Close Mobile Sidebar on Backdrop Click
  if (sidebarBackdrop) {
    sidebarBackdrop.addEventListener('click', function() {
      sidebar.classList.remove('show');
      sidebarBackdrop.classList.remove('show');
    });
  }

  // Auto close mobile sidebar when window resized to desktop
  window.addEventListener('resize', function() {
    if (window.innerWidth >= 992) {
      sidebar.classList.remove('show');
      sidebarBackdrop.classList.remove('show');
    }
  });
});
</script>
</body>
</html>
