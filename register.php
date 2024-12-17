<?php
require_once 'config.php';
require_once 'functions.php';

// Initialize the error variable
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 8 || !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        $error = "Password must be at least 8 characters long and contain at least one special character.";
    } elseif (strlen($name) > 20) {
        $error = "Name must be 20 characters or fewer.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Email already exists. Please use a different email.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashed_password);
            
            if ($stmt->execute()) {
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['user_name'] = $name;
                header("Location: index.php"); // Redirect to home page after successful registration
                exit();
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .form-box {
            box-sizing: border-box;
            border-radius: 5px;
            padding: 10px;
        }
        .btn-block {
            width: 100%;
        }
        .invalid-feedback {
            display: none; /* Hide by default */
        }
        .form-control.is-invalid ~ .invalid-feedback {
            display: block; /* Show if invalid */
        }
    </style>
</head>
<body>
    <header class="bg-pink text-white text-center py-3">
        <h1>Register</h1>
    </header>

    <main class="container my-4">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <form action="register.php" method="post" class="needs-validation" novalidate>
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" class="form-control form-box" required maxlength="20">
                        <div class="invalid-feedback">
                            Please enter your name (max 20 characters).
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control form-box" required>
                        <div class="invalid-feedback">
                            Please enter a valid email address.
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" class="form-control form-box" required minlength="8">
                        <small id="passwordHelp" class="form-text text-muted">
                            Password must be at least 8 characters long and contain at least one special character.
                        </small>
                        <div class="invalid-feedback">
                            Password must be at least 8 characters long and contain at least one special character.
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control form-box" required>
                        <div class="invalid-feedback" id="confirmPasswordFeedback">
                            Passwords do not match.
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Register</button>
                </form>

                <p class="mt-3 text-center">Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                Array.prototype.filter.call(forms, function(form) {
                    var password = document.getElementById('password');
                    var confirm_password = document.getElementById('confirm_password');
                    var confirmPasswordFeedback = document.getElementById('confirmPasswordFeedback');

                    form.addEventListener('input', function() {
                        var passwordValue = password.value;
                        var confirmPasswordValue = confirm_password.value;

                        if (passwordValue.length < 8 || !/[!@#$%^&*(),.?":{}|<>]/.test(passwordValue)) {
                            password.classList.add('is-invalid');
                        } else {
                            password.classList.remove('is-invalid');
                        }

                        if (passwordValue !== confirmPasswordValue) {
                            confirm_password.classList.add('is-invalid');
                            confirmPasswordFeedback.style.display = 'block';
                        } else {
                            confirm_password.classList.remove('is-invalid');
                            confirmPasswordFeedback.style.display = 'none';
                        }

                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>
</html>
