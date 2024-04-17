<style>
  .nav-link.active,
  .nav-link:active,
  .nav-link:focus {
    background-color: #840F1E !important;
    /* Change the color to #840F1E */
  }

  .medi {
    background-color: whitesmoke;
  }

  /* Hide scrollbar */
  .main-sidebar::-webkit-scrollbar {
    width: 0;
    /* Hide scrollbar for Chrome, Safari, and Opera */
  }

  /* Optional: Hide scrollbar for Firefox */
  .main-sidebar {
    scrollbar-width: none;
  }

  /* Optional: Hide scrollbar for Edge */
  .main-sidebar {
    -ms-overflow-style: none;
  }
</style>
<aside class="main-sidebar elevation-4" style="background-color: white; color: #840F1E; overflow-y: auto;">
  <div class="medi" style="text-align: center; align-items: center;">
    <img src="./assets/uploads/logo-navbar.png" style="margin-top: 1.5rem; background-position: center; background-repeat: none;" height="40" alt=""> <br>
    <!-- <h5>Medi-Caps University</h5> -->
    <h5 style="text-align: center; align-items: center; font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;"><strong>Department of Information Technology</strong></h5>
  </div>
  <div class="dropdown">
    <a href="./" class="">
      <div style="border: 1px solid #840F1E; margin-top: 1rem;"></div>
      <h4 class="text-center" style="color: #840F1E; font-weight: 500; margin-top: 1rem;">
        <strong>Student Panel</strong>
      </h4>
    </a>
  </div>
  <div class="sidebar" style="background-color: white; margin-top: 0.5rem;">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item dropdown">
          <a href="./" class="nav-link nav-home">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a href="./index.php?page=evaluate" class="nav-link nav-evaluate">
            <i class="nav-icon fas fa-th-list"></i>
            <p>
              Evaluate
            </p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>
<script>
  $(document).ready(function() {
    var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
    var s = '<?php echo isset($_GET['s']) ? $_GET['s'] : '' ?>';
    if (s != '')
      page = page + '_' + s;
    if ($('.nav-link.nav-' + page).length > 0) {
      $('.nav-link.nav-' + page).addClass('active')
      if ($('.nav-link.nav-' + page).hasClass('tree-item') == true) {
        $('.nav-link.nav-' + page).closest('.nav-treeview').siblings('a').addClass('active')
        $('.nav-link.nav-' + page).closest('.nav-treeview').parent().addClass('menu-open')
      }
      if ($('.nav-link.nav-' + page).hasClass('nav-is-tree') == true) {
        $('.nav-link.nav-' + page).parent().addClass('menu-open')
      }

    }

  })
</script>