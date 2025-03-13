<?php
session_start();
unset($_SESSION['admin_id']);
header("Location: index.php?p=dashboard"); 
exit();
?>
