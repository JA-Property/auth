<?php
namespace App\Controllers;

use App\Models\User;

class LoginController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Render the login view.
     */
    public function renderLoginView()
    {
        $viewFile = __DIR__ . '/../Views/LoginView.html';
        $title = 'Login - JA Property Management';
        include __DIR__ . '/../Views/layout.php';
    }

    /**
     * Process the login submission.
     */
    public function processLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            // Get the user record based on the email.
            $user = $this->userModel->findByEmail($email);
            $now = date('Y-m-d H:i:s');

            if ($user && password_verify($password, $user['password_hash'])) {
                // Successful login:
                // 1) Update both the last login attempt and last successful login
                $this->userModel->updateLoginTimestamps($user['id'], $now, $now);

                // 2) Store all user columns (minus password_hash) in the session
                //    This way, the staff portal can see every DB column (like role, status, etc.).
                $_SESSION['user'] = $user;
                unset($_SESSION['user']['password_hash']); // Keep the session safer

                // 3) Redirect based on role
                if ($user['role'] === 'staff' || $user['role'] === 'admin') {
                    header("Location: https://backoffice.japropertysc.com");
                } else {
                    header("Location: https://customer.japropertysc.com");
                }
                exit;

            } else {
                // If the user exists, record the login attempt timestamp (failed).
                if ($user) {
                    $this->userModel->updateLoginTimestamps($user['id'], $now);
                }

                // Prepare error messaging for a failed login
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
