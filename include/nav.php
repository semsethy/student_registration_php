<style>
/* Hide all submenus by default */
.sidebar-sub {
  display: none;
  padding-left: 20px;
}

/* Show submenu when active */
.sidebar-sub.show {
  display: block;
}
.sidebar-left {
  display: flex;
  align-items: center;
  gap: 14px; /* Adds space between icon and text */
}
/* Toggle icon styles */
.sidebar-toggle {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.toggle-icon {
  transition: transform 0.3s ease;
}

/* Rotate icon when active */
.sidebar-item.active .toggle-icon i {
  transform: rotate(180deg);
}


</style>

<aside class="left-sidebar">
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

        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            
            
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Students</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./index.php?p=student_list" aria-expanded="false">
                <span>
                  <i class="ti ti-users"></i>
                </span>
                <span class="hide-menu">Students List</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./index.php?p=student_add" aria-expanded="false">
                <span>
                  <i class="ti ti-user-plus"></i>
                </span>
                <span class="hide-menu">Add Student</span>
              </a>
            </li>
            
        </nav>
      </div>
    </aside>

    <script>
document.addEventListener("DOMContentLoaded", function () {
  const sidebarToggles = document.querySelectorAll(".sidebar-toggle");

  sidebarToggles.forEach(toggle => {
    toggle.addEventListener("click", function (event) {
      event.preventDefault();

      const parentItem = this.closest(".sidebar-item");
      const submenu = parentItem.querySelector(".sidebar-sub");
      const icon = parentItem.querySelector(".toggle-icon i");

      if (submenu) {
        submenu.classList.toggle("show");
        parentItem.classList.toggle("active");

        // Close other submenus
        document.querySelectorAll(".sidebar-item").forEach(item => {
          if (item !== parentItem) {
            item.classList.remove("active");
            const sub = item.querySelector(".sidebar-sub");
            if (sub) sub.classList.remove("show");
            const otherIcon = item.querySelector(".toggle-icon i");
            if (otherIcon) otherIcon.style.transform = "rotate(0deg)";
          }
        });

        // Rotate icon
        icon.style.transform = submenu.classList.contains("show") ? "rotate(180deg)" : "rotate(0deg)";
      }
    });
  });
});



    </script>