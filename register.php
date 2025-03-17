<?php
// require_once 'Database.php';
require_once 'include/userConf.php';
$userClass = new User();

// Handle form submission
if (isset($_POST['submit'])) {
    // Escape user input for security
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $cpass = $_POST['cpassword'];

    // Check if passwords match
    if ($pass != $cpass) {
        $message[] = 'Passwords do not match!';
    } else {
        // Check if user already exists
        $select = $userClass->getUsersByEmail($email); // Assuming you create a getUsersByEmail function
        if (!empty($select)) {
            $message[] = 'User already exists!';
        } else {
            // Hash password before storing it
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

            // Register the user
            $userClass->registerUser($name, $email, $hashed_pass);
            $message[] = 'Registration successful!';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Royal University of Phnom Penh</title>
  <link rel="shortcut icon" type="image/png" href="images/logos/rupp_logo.png" />
  <link rel="stylesheet" href="css/styles.min.css" />
  

</head>
    <body>

        <?php include "userstyle.php" ?>
        <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
            <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
                <div class="d-flex align-items-center justify-content-center w-100">
                    <div class="row justify-content-center w-100">
                        <div class="col-md-8 col-lg-6 col-xxl-3">
                            <div class="card mb-0">
                                <div class="card-body">
                                    <a href="index.php?p=home" class="text-nowrap logo-img text-center d-block py-3 w-100">
                                        <img src="images/logos/rupp_logo.png" height="100" alt="">
                                    </a>
                                    <p class="text-center">Your Social Campaigns</p>
                                    <!-- Form to register a user -->
                                    <form method="POST" action="">
                                        <div class="mb-3">
                                            <label for="exampleInputtext1" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="exampleInputtext1" name="name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="exampleInputEmail1" class="form-label">Email Address</label>
                                            <input type="email" class="form-control" id="exampleInputEmail1" name="email" required>
                                        </div>
                                        <div class="mb-4">
                                            <label for="exampleInputPassword1" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="exampleInputPassword1" name="password" required>
                                        </div>
                                        <div class="mb-4">
                                            <label for="exampleInputPassword2" class="form-label">Confirm Password</label>
                                            <input type="password" class="form-control" id="exampleInputPassword2" name="cpassword" required>
                                        </div>
                                        <button type="submit" name="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Sign Up</button>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <p class="fs-4 mb-0 fw-bold">Already have an Account?</p>
                                            <a class="text-primary fw-bold ms-2" href="index.php?p=login">Sign In</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="background:#dffffa;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="successModalLabel">Success</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Registration successful!
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Okay</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Modal -->
        <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="background:#ffe8e8;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="errorModalLabel">Error</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        User already exists!
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Okay</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Password Mismatch Modal -->
        <div class="modal fade" id="passwordMismatchModal" tabindex="-1" aria-labelledby="passwordMismatchModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="passwordMismatchModalLabel">Error</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Passwords do not match!
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScript to trigger modals -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            <?php if (isset($message)): ?>
                <?php if (in_array('Registration successful!', $message)): ?>
                    $(document).ready(function(){
                        $('#successModal').modal('show');
                    });
                <?php elseif (in_array('User already exists!', $message)): ?>
                    $(document).ready(function(){
                        $('#errorModal').modal('show');
                    });
                <?php elseif (in_array('Passwords do not match!', $message)): ?>
                    $(document).ready(function(){
                        $('#passwordMismatchModal').modal('show');
                    });
                <?php endif; ?>
            <?php endif; ?>
        </script>
    </body>
</html>