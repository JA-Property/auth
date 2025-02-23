<?php
namespace App\Controllers;

class ResetPasswordController {
    /**
     * Render the reset password view.
     */
    public function renderResetPasswordView() {
        include __DIR__ . '/../Views/ResetPasswordView.html';
    }

    /**
     * Process the reset password form submission.
     */
    public function processResetPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = trim($_POST['token'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $confirmPassword = trim($_POST['confirm_password'] ?? '');

            if (empty($token) || empty($password) || empty($confirmPassword)) {
                $error = "All fields are required.";
                include __DIR__ . '/../Views/ResetPasswordView.html';
                exit;
            }
            if ($password !== $confirmPassword) {
                $error = "Passwords do not match.";
                include __DIR__ . '/../Views/ResetPasswordView.html';
                exit;
            }

            // Reset password logic here (verify token, update password hash in database, etc.)
            // For demonstration, assume success:
            echo "Your password has been reset. You can now <a href='index.php?route=login'>login</a>.";
            exit;
        }
    }
}
