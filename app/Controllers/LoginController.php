<?php
namespace App\Controllers;

use App\Models\User;

class LoginController {
    
    private $userModel;

    public function __construct() {
        // The User model now automatically uses the global database connection.
        $this->userModel = new User();
    }

    /**
     * Render the login view.
     */
    public function renderLoginView() {
        $viewFile = __DIR__ . '/../Views/LoginView.html';
        $title = 'Login - JA Property Management';
        include __DIR__ . '/../Views/layout.php';
    }

    /**
     * Process the login submission.
     */
    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            // Get the user record based on the email.
            $user = $this->userModel->findByEmail($email);
                        $now = date('Y-m-d H:i:s');

            if ($user && password_verify($password, $user['password_hash'])) {
                // Successful login:
                // Update both the last login attempt and last successful login.
                $this->userModel->updateLoginTimestamps($user['id'], $now, $now);

                // Set session data and redirect based on role.
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_id'] = $user['id'];
                if ($user['role'] === 'staff' || $user['role'] === 'admin') {
                    header("Location: google.com");
                } else {
                    header("Location: bing.com");
                }
                exit;
            } else {
                // If the user exists, record the login attempt.
                if ($user) {
                    $this->userModel->updateLoginTimestamps($user['id'], $now);
                }
                // Prepare error messaging for failed login.
                $toastMessage = "Invalid email or password.";
                $error = "Invalid email or password.";
                $viewFile = __DIR__ . '/../Views/LoginView.html';
                $title = 'Login - JA Property Management';
                include __DIR__ . '/../Views/layout.php';
                exit;
            }
        }
    }
}
