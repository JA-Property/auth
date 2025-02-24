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
            echo "No token provided.";
            exit;
        }

        // 2) Check if token exists in password_resets
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT email, created_at FROM password_resets WHERE token = :token LIMIT 1");
        $stmt->execute(['token' => $token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            echo "Invalid or expired token.";
            exit;
        }

        // 3) Optional: check if token is older than 1 hour
        //    For example:
        //    $createdTime = strtotime($row['created_at']);
        //    $oneHourAgo  = time() - 3600;
        //    if ($createdTime < $oneHourAgo) {
        //        echo "This reset link has expired. Please request a new one.";
        //        exit;
        //    }

        // 4) If token is valid, show the form
        //    The form MUST include a hidden input with the token
        $viewFile = __DIR__ . '/../Views/ResetPasswordView.html';
        $title = 'Reset Password - JA Property Management';
        include __DIR__ . '/../Views/layout.php';
    }

    /**
     * Process the reset password form submission (POST).
     */
    public function processResetPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = trim($_POST['token'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $confirmPassword = trim($_POST['confirm_password'] ?? '');

            // 1) Basic checks
            if (empty($token) || empty($password) || empty($confirmPassword)) {
                $error = "All fields are required.";
                // Re-display the form with $error if needed:
                $viewFile = __DIR__ . '/../Views/ResetPasswordView.html';
                $title = 'Reset Password - JA Property Management';
                include __DIR__ . '/../Views/layout.php';
                exit;
            }

            if ($password !== $confirmPassword) {
                $error = "Passwords do not match.";
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
                echo "Invalid or expired token.";
                exit;
            }

            // (Optional) check expiration again here if you want to be thorough

            $email = $row['email'];

            // 3) Update user’s password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmtUser = $pdo->prepare("UPDATE users SET password_hash = :hash WHERE email = :email");
            $stmtUser->execute([
                'hash' => $hashedPassword,
                'email' => $email
            ]);

            // 4) Remove the token so it cannot be reused
            $stmtDel = $pdo->prepare("DELETE FROM password_resets WHERE token = :token");
            $stmtDel->execute(['token' => $token]);

            // 5) Inform the user
            echo "Your password has been reset. You can now <a href='index.php?route=login'>login</a>.";
            exit;
        }
    }
}
