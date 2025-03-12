
<?php
require_once 'include/studentConf.php';
$student = new Student();
$students = $student->getStudent();
?>

<style>
    .table td {
        text-align: left; /* Center horizontally */
        vertical-align: middle; /* Center vertically */
    }
</style>
<div class="container-fluid">
    <h1>Student List</h1>

    <div class="mb-4" style="text-align: right;">
        <a href="index.php?p=student_add" style="background-color: #28a745; color: white; border-radius: 4px; padding: 8px 12px; border: none; cursor: pointer;">+ New</a>
    </div>

    <div class="table-container">
        <div class="card p-3">
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Profile Image</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Date of Birth</th>
                            <th>Department</th>
                            <th>Register Date</th>
                            <th>Expire Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($students) > 0) {
                            foreach ($students as $row) {
                                echo "<tr>";
                                echo "<td><img src='{$row['profile_image']}' alt='Product Image' style='width: 60px; height: 60px; object-fit: cover; border-radius: 5px;'/></td>";
                                echo "<td>{$row['full_name']}</td>";
                                echo "<td>{$row['email']}</td>";
                                echo "<td>{$row['dob']}</td>";
                                echo "<td>{$row['department']}</td>";
                                echo "<td>{$row['register_date']}</td>";
                                echo "<td>{$row['expire_date']}</td>";
                                echo "<td>
                                        <div class='dropdown'>
                                            <button type='button' class='btn dropdown-toggle hide-arrow' data-bs-toggle='dropdown'>
                                                <i class='bx bx-dots-vertical-rounded'></i>
                                            </button>
                                            <div class='dropdown-menu'>
                                                <a class='dropdown-item' href='index.php?p=student_add&id={$row['id']}'><i class='bx bx-edit-alt'></i> Edit</a>
                                                <a class='dropdown-item' href='student_delete.php?id={$row['id']}' style='color: red;'><i class='bx bx-trash'></i> Delete</a>
                                            </div>
                                        </div>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center'>No students found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
