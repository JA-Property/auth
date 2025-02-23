<?php
namespace App\Controllers;

class LoginController {
    /**
     * Loads the login view.
     */
    public function renderLoginView() {
        include __DIR__ . '/../Views/LoginView.html';
    }

    /**
     * Process login submission (this is where you would add your authentication logic).
     * This method isn’t called directly in this simple example.
     */
    public function processLogin() {
        // Example (replace with your actual authentication logic):
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            // Dummy authentication logic:
            if ($email === 'staff@example.com' && $password === 'password') {
                $_SESSION['user_role'] = 'staff';
                header("Location: https://staff.yourdomain.com");
                exit;
            } elseif ($email === 'customer@example.com' && $password === 'password') {
                $_SESSION['user_role'] = 'customer';
                header("Location: https://customer.yourdomain.com");
                exit;
            } else {
                $error = "Invalid email or password.";
                include __DIR__ . '/../Views/LoginView.html';
                exit;
            }
        }
    }
}
