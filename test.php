<!-- Database.php -->
<?php
class Database {
    private $host = "localhost";
    private $db_name = "bda3";
    private $username = "root";
    private $password = "root";
    private $conn;

    // Get the PDO connection
    public function getConnection() {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $exception) {
                echo "Connection error: " . $exception->getMessage();
            }
        }
        return $this->conn;
    }
}
?>


<!-- student_add.php -->
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
                echo "<div class='alert alert-success'>Student updated successfully!</div>";
            } else {
                echo "<div class='alert alert-danger'>Error updating student!</div>";
            }
        } else {
            // Insert new student
            if ($student->create($full_name, $email, $dob, $department, $profile_image_url, $register_date, $expire_date)) {
                echo "<div class='alert alert-success'>Registration successful!</div>";
            } else {
                echo "<div class='alert alert-danger'>Error registering student!</div>";
            }
        }
    } else {
        echo "<div class='alert alert-warning'>Full Name and Email are required.</div>";
    }
}

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


<!-- student_list.php -->

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
                                echo "<td><img src='{$row['profile_image']}' alt='Product Image' style='width: 50px; height: 50px; object-fit: cover; border-radius: 5px;'/></td>";
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
                            echo "<tr><td colspan='6' class='text-center'>No students found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- student_delete.php -->

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


<!-- studentConf.php --><?php
require_once 'Database.php';

class Student {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();  // Correctly get PDO connection
    }

    public function create($full_name, $email, $dob, $department, $profile_image, $register_date, $expire_date) {
        $sql = "INSERT INTO students (full_name, email, department, dob, profile_image, register_date, expire_date) 
                VALUES (:full_name, :email, :department, :dob, :profile_image, :register_date, :expire_date)";
        $stmt = $this->conn->prepare($sql);  // Use PDO prepare method here
        $stmt->bindParam(":full_name", $full_name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":department", $department);
        $stmt->bindParam(":dob", $dob);
        $stmt->bindParam(":profile_image", $profile_image);
        $stmt->bindParam(":register_date", $register_date);
        $stmt->bindParam(":expire_date", $expire_date);
        return $stmt->execute();  // Ensure you execute the statement
    }

    public function update($full_name, $email, $department, $dob, $profile_image, $student_id) {
        $sql = "UPDATE students SET full_name = :full_name, email = :email, department = :department, profile_image = :profile_image, dob = :dob WHERE id = :id";
        $stmt = $this->conn->prepare($sql);  // Use PDO prepare method here
        $stmt->bindParam(":full_name", $full_name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":department", $department);
        $stmt->bindParam(":profile_image", $profile_image);
        $stmt->bindParam(":dob", $dob);
        $stmt->bindParam(":id", $student_id);
        return $stmt->execute();  // Ensure you execute the statement
    }

    public function getById($student_id) {
        $query = "SELECT * FROM students WHERE id = :id";
        $stmt = $this->conn->prepare($query);  // Use PDO prepare method here
        $stmt->bindParam(":id", $student_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);  // Fetch and return the student data
    }

    public function delete($student_id) {
        // First, fetch the student's data to get the profile image path
        $query = "SELECT profile_image FROM students WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $student_id);
        $stmt->execute();
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Check if the profile image exists and delete it
        if ($student && !empty($student['profile_image'])) {
            $image_path = $student['profile_image'];
    
            // Check if the image exists and delete it
            if (file_exists($image_path)) {
                unlink($image_path);  // Delete the image file
            }
        }
    
        // Now, delete the student from the database
        $query = "DELETE FROM students WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $student_id);
        return $stmt->execute();  // Execute the delete query
    }
    

    public function getStudent() {
        $query = "SELECT * FROM students"; 
        $stmt = $this->conn->prepare($query);  // Use PDO prepare method here
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Return all students
    }
}

?>
