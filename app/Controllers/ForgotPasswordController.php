<?php
namespace App\Controllers;

class ForgotPasswordController {
    /**
     * Render the forgot password view.
     */
    public function renderForgotPasswordView() {
        include __DIR__ . '/../Views/ForgotPasswordView.html';
    }

    /**
     * Process the forgot password form submission.
     */
    public function processForgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            if (empty($email)) {
                $error = "Please enter your email address.";
                include __DIR__ . '/../Views/ForgotPasswordView.html';
                exit;
            }

            // Forgot password logic here (generate a reset token, send email, etc.)
            // For demonstration, assume the email has been sent:
            echo "A password reset link has been sent to your email.";
            exit;
        }
    }
}
