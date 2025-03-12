<?php
require_once 'include/studentConf.php';

$student = new Student(); 
$student_data = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = htmlspecialchars($_POST['full_name']);
    $email = htmlspecialchars($_POST['email']);
    $dob = htmlspecialchars($_POST['dob']);
    $department = $_POST['department'];
    $register_date = date('Y-m-d');
    $expire_date = date('Y-m-d', strtotime('+4 years +1 day'));

    $upload_dir = "images/students/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    $profile_image_url = "";

    // Handle profile image upload
    if (!empty($_FILES['profile_image']['name']) && $_FILES['profile_image']['error'] == 0) {
        // If there is a new profile image, check if there's an old one to delete
        if (isset($_POST['existing_profile_image']) && !empty($_POST['existing_profile_image'])) {
            // Delete the old image if a new image is uploaded
            $old_image_path = $_POST['existing_profile_image'];
            if (file_exists($old_image_path)) {
                unlink($old_image_path); // Delete the old image
            }
        }

        // Upload the new profile image
        $file_ext = pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION);
        $profile_image_url = $upload_dir . uniqid() . "." . $file_ext;

        // Move the uploaded file to the directory
        if (!move_uploaded_file($_FILES["profile_image"]["tmp_name"], $profile_image_url)) {
            die("Error uploading profile image.");
        }
    } else {
        // If no new image is uploaded, use the existing one from the form
        $profile_image_url = $_POST['existing_profile_image'] ?? null;
    }

    if (!empty($full_name) && !empty($email)) {
        if (isset($_POST['student_id']) && $_POST['student_id'] != '') {
            // Update existing student
            $student_id = $_POST['student_id'];
            if ($student->update($full_name, $email, $dob, $department, $profile_image_url, $student_id)) {
                echo "<div id='alert' style='width:80%; margin: 0 auto;  z-index: 1000;' class='alert alert-success mt-4'>Student updated successfully!</div>";
            } else {
                echo "<div id='alert' style='width:80%;margin: 0 auto; z-index: 1000;' class='alert alert-danger mt-4'>Error updating student!</div>";
            }
        } else {
            // Insert new student
            if ($student->create($full_name, $email, $dob, $department, $profile_image_url, $register_date, $expire_date)) {
                echo "<div id='alert' style='width:80%;margin: 0 auto; z-index: 1000;' class='alert alert-success mt-4'>Registration successful!</div>";
                
            } else {
                echo "<div id='alert' style='width:80%;margin: 0 auto; z-index: 1000;' class='alert alert-danger mt-4'>Error registering student!</div>";
            }
        }
    } else {
        echo "<div id='alert' style='width:80%; margin: 0 auto; z-index: 1000;' class='alert alert-warning mt-4'>Full Name and Email are required.</div>";
    }
}
echo "<script>setTimeout(function(){ $('#alert').fadeOut(1000); }, 2000);</script>";

// Fetch student data for editing if an id is provided
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];
    $student_data = $student->getById($student_id);
}
?>

<!-- Registration Form -->
<div class="container-fluid">
    <h1><?php echo isset($student_data['id']) ? "Edit Student" : "Student Registeration"; ?></h1>
    <div class="card p-4 mt-5">
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="student_id" value="<?php echo isset($student_data['id']) ? $student_data['id'] : ''; ?>" />
            <div class="mb-3">
                <label for="profile_image" class="form-label">Profile Image</label>
                <input type="file" name="profile_image" id="profile_image" class="form-control" accept="image/*" onchange="previewMainImage(event)">
                <div id="profile_image_preview" style="margin-top: 20px; margin-left: 20px;">
                    <img src="<?php echo isset($student_data['profile_image']) && !empty($student_data['profile_image']) ? $student_data['profile_image'] : 'images/logos/placeholder2.jpg'; ?>" 
                        alt="Profile Image" style="width: 80px; height: 80px; object-fit: cover; border-radius: 5px; box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3); border: 1px solid lightgray;">
                </div> 
                <input type="hidden" name="existing_profile_image" value="<?php echo htmlspecialchars($student_data['profile_image'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Full Name:</label>
                <input type="text" name="full_name" class="form-control" placeholder="Enter Full Name" required value="<?php echo htmlspecialchars($student_data['full_name'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" placeholder="Enter Email" required value="<?php echo htmlspecialchars($student_data['email'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Date of Birth:</label>
                <input type="date" name="dob" class="form-control" required value="<?php echo htmlspecialchars($student_data['dob'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Department</label>
                <select class="form-select" name="department" required>
                    <option value="CS" <?php echo (isset($student_data['department']) && $student_data['department'] == 'CS' ? 'selected' : ''); ?>>CS</option>
                    <option value="IT" <?php echo (isset($student_data['department']) && $student_data['department'] == 'IT' ? 'selected' : ''); ?>>IT</option>
                    <option value="ITE" <?php echo (isset($student_data['department']) && $student_data['department'] == 'ITE' ? 'selected' : ''); ?>>ITE</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
</div>

<script>
    function previewMainImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('profile_image_preview');
            output.innerHTML = '<img src="' + reader.result + '" alt="Profile Image" style="width: 80px; height: 80px; object-fit: cover; border-radius: 5px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
