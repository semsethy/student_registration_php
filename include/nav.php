
<aside class="left-sidebar bg-light">
  <!-- Sidebar scroll-->
  <div>
  <div class="brand-logo d-flex align-items-center justify-content-center">
    <a href="./index.php" class="text-nowrap logo-img">
      <img src="images/logos/rupp_logo.png" alt="" style="height:100px;width:auto;margin-top:20px;" />
    </a>
    <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
      <i class="ti ti-x fs-8"></i>
    </div>
  </div>
  <!-- <h6 class="text-center pt-3">Royal University of Phnom Penh</h6> -->
    <!-- Sidebar navigation-->
    <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
      <ul id="sidebarnav">
        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Students</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link <?php echo isset($_GET['p']) && $_GET['p'] == 'student_list' ? 'active' : ''; ?>" href="./index.php?p=student_list" aria-expanded="false">
            <span>
              <i class="ti ti-users"></i>
            </span>
            <span class="hide-menu">Students List</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link <?php echo isset($_GET['p']) && $_GET['p'] == 'student_add' ? 'active' : ''; ?>" href="./index.php?p=student_add" aria-expanded="false">
            <span>
              <i class="ti ti-user-plus"></i>
            </span>
            <span class="hide-menu">Add Student</span>
          </a>
        </li>
    </nav>
  </div>
</aside>