<?php
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

    public function update($full_name, $email, $dob, $department, $profile_image_url, $student_id) {
        $sql = "UPDATE students SET full_name = :full_name, email = :email, department = :department, profile_image = :profile_image, dob = :dob WHERE id = :id";
        $stmt = $this->conn->prepare($sql);  // Use PDO prepare method here
        $stmt->bindParam(":full_name", $full_name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":department", $department);
        $stmt->bindParam(":profile_image", $profile_image_url);
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
