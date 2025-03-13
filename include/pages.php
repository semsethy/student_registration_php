
<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit(); 
}
    $page = "student_list.php";
    $p = "student_list.php";
    if(isset($_GET['p'])){
        $p = $_GET['p'];
        switch($p) {
            case "student_list":
                $page = "student_list.php";
                break;
            case "student_add":
                $page = "student_add.php";
                break;
            default:
                $page = "student_list.php";
                break;
        }
    }
?>
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
  data-sidebar-position="fixed" data-header-position="fixed">
  <?php include 'include/nav.php'?>
  <div class="body-wrapper">
    <?php include 'include/header.php'?>
    <?php include "$page" ?>
  </div>
</div>

