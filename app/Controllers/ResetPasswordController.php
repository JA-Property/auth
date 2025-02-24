<?php
namespace App\Controllers;

use App\Database;
use App\Models\User;
use PDO;

class ResetPasswordController {
    /**
     * Render the reset password view (GET). We check if token is valid before showing form.
     */
    public function renderResetPasswordView() {
        // 1) Get token from URL
        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            // Instead of echoing, let's show a toast message & re-render (or you can do direct echo if you prefer).
            $toastMessage = "No token provided.";
            $viewFile = __DIR__ . '/../Views/ResetPasswordView.html';
            $title = 'Reset Password - JA Property Management';
            include __DIR__ . '/../Views/layout.php';
            exit;
        }

        // 2) Check if token exists in password_resets
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT email, created_at FROM password_resets WHERE token = :token LIMIT 1");
        $stmt->execute(['token' => $token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            $toastMessage = "Invalid or expired token.";
            $viewFile = __DIR__ . '/../Views/ResetPasswordView.html';
            $title = 'Reset Password - JA Property Management';
            include __DIR__ . '/../Views/layout.php';
            exit;
        }

        // (Optional) check if token is older than 1 hour, etc.

        // If token is valid, show the form
        $viewFile = __DIR__ . '/../Views/ResetPasswordView.html';
        $title = 'Reset Password - JA Property Management';
        include __DIR__ . '/../Views/layout.php';
    }

    /**
     * Process the reset password form submission (POST).
     */
    public function processResetPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token           = trim($_POST['token'] ?? '');
            $password        = trim($_POST['password'] ?? '');
            $confirmPassword = trim($_POST['confirm_password'] ?? '');

            // 1) Basic checks
            if (empty($token) || empty($password) || empty($confirmPassword)) {
                $toastMessage = "All fields are required.";
                $viewFile = __DIR__ . '/../Views/ResetPasswordView.html';
                $title = 'Reset Password - JA Property Management';
                include __DIR__ . '/../Views/layout.php';
                exit;
            }

            if ($password !== $confirmPassword) {
                $toastMessage = "Passwords do not match.";
                $viewFile = __DIR__ . '/../Views/ResetPasswordView.html';
                $title = 'Reset Password - JA Property Management';
                include __DIR__ . '/../Views/layout.php';
                exit;
            }

            // 2) Look up token in the DB
            $pdo = Database::connect();
            $stmt = $pdo->prepare("SELECT email, created_at FROM password_resets WHERE token = :token LIMIT 1");
            $stmt->execute(['token' => $token]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                $toastMessage = "Invalid or expired token.";
                $viewFile = __DIR__ . '/../Views/ResetPasswordView.html';
                $title = 'Reset Password - JA Property Management';
                include __DIR__ . '/../Views/layout.php';
                exit;
            }

            $email = $row['email'];

            // 3) Update user’s password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmtUser = $pdo->prepare("UPDATE users SET password_hash = :hash WHERE email = :email");
            $stmtUser->execute([
                'hash'  => $hashedPassword,
                'email' => $email
            ]);

            // 4) Remove the token so it cannot be reused
            $stmtDel = $pdo->prepare("DELETE FROM password_resets WHERE token = :token");
            $stmtDel->execute(['token' => $token]);

            // 5) Inform the user with a toast
            $toastMessage = "Your password has been reset. <a href='index.php?route=login' class='underline'>Login now</a>.";
            $viewFile = __DIR__ . '/../Views/ResetPasswordView.html';
            $title = 'Reset Password - JA Property Management';
            include __DIR__ . '/../Views/layout.php';
            exit;
        }
    }
}
