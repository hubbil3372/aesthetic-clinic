<aside class="main-sidebar sidebar-bg-light sidebar-color-white shadow">
  <div class="brand-container">
    <a href="<?= site_url(); ?>backoffice/dasbor" class="brand-link waitme text-truncate sidebar-font-color-primary">
      <img src="<?= base_url(); ?>_assets/images/logo-clinic.png" alt="Logo" class="brand-image opacity-80 rounded-circle shadow">
      <span class="brand-text sidebar-font-color-primary"><?= $this->front_auth->ecommerce()->ecomNama ?></span>
    </a>
    <a class="pushmenu mx-1" data-lte-toggle="sidebar-mini" href="<?= site_url(); ?>backoffice/dasbor" role="button"><i class="fas fa-angle-double-left"></i></a>
  </div>
  <!-- Sidebar -->
  <div class="sidebar m-0 p-0">
    <nav class="mt-2">
      <!-- Sidebar Menu -->
      <ul class="nav nav-pills nav-sidebar flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
        <!-- Menu -->
        <?= $this->menus->menu_sidebar(); ?>
        <!-- /Menu -->
      </ul>
    </nav>
  </div>
  <!-- /.sidebar -->
</aside>