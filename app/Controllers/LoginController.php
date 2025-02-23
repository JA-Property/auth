<?php
namespace App\Controllers;

class LoginController {
    /**
     * Renders the login view.
     */
    public function renderLoginView() {
        // Define the path to the specific view file (the card content)
        $viewFile = __DIR__ . '/../Views/LoginView.html';
        $title = 'Login - JA Property Management';
        // Optionally, a toast message could have been set by processLogin
        include __DIR__ . '/../Views/layout.php';
    }

    /**
     * Processes the login submission.
     */
    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            // Dummy authentication logic for demonstration:
            if ($email === 'staff@example.com' && $password === 'password') {
                $_SESSION['user_role'] = 'staff';
                header("Location: https://staff.yourdomain.com");
                exit;
            } elseif ($email === 'customer@example.com' && $password === 'password') {
                $_SESSION['user_role'] = 'customer';
                header("Location: https://customer.yourdomain.com");
                exit;
            } else {
                // Set a toast message to be displayed in the layout
                $toastMessage = "Invalid email or password.";
                // Optionally, you can also set an error variable to show inline error in the view
                $error = "Invalid email or password.";
                // Define the view file and title, then load the global layout
                $viewFile = __DIR__ . '/../Views/LoginView.html';
                $title = 'Login - JA Property Management';
                include __DIR__ . '/../Views/layout.php';
                exit;
            }
        }
    }
}
