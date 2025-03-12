<?php
require_once 'include/studentConf.php';

// Get the student ID from the URL
$student_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$student = new Student();

if ($student_id > 0) {
    if ($student->delete($student_id)) {
        // Redirect to the student list page after successful deletion
        header("Location: index.php?p=student_list");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Error deleting student.</div>";
    }
}
?>
