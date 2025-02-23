<?php
namespace App\Controllers;

class RegisterController {
    /**
     * Render the registration view.
     */
    public function renderRegisterView() {
               // Define the path to the specific view file (the card content)
               $viewFile = __DIR__ . '/../Views/RegisterView.html';
               $title = 'Register - JA Property Management';
               // Optionally, a toast message could have been set by processLogin
               include __DIR__ . '/../Views/layout.php';
    }

    /**
     * Process registration data from the form.
     */
    public function processRegistration() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $confirmPassword = trim($_POST['confirm_password'] ?? '');

            // Basic validation
            if (empty($email) || empty($password) || empty($confirmPassword)) {
                $error = "Please fill in all fields.";
                include __DIR__ . '/../Views/RegisterView.html';
                exit;
            }
            if ($password !== $confirmPassword) {
                $error = "Passwords do not match.";
                include __DIR__ . '/../Views/RegisterView.html';
                exit;
            }

            // Registration logic here (hash password, insert into database, etc.)
            // For demonstration, assume registration is successful:
            header("Location: index.php?route=login&registered=1");
            exit;
        }
    }
}
