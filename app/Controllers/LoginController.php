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
                // Update both the last login attempt and last successful login.
                $this->userModel->updateLoginTimestamps($user['id'], $now, $now);

                // If you have 'firstname'/'lastname' columns in your DB:
                // $fullName = $user['firstname'] . ' ' . $user['lastname'];
                // $initials = strtoupper($user['firstname'][0] . $user['lastname'][0]);
                // Otherwise, just hard-code or store from DB as you wish.

                $_SESSION['user'] = [
                    'role'         => $user['role'],
                    'id'           => $user['id'],
                    // Example placeholders:
                    'display_name' => 'Jane Doe',   // or from DB: $fullName
                    'initials'     => 'JD',        // or from DB: $initials
                ];

                // Redirect based on role.
                if ($user['role'] === 'staff' || $user['role'] === 'admin') {
                    // Example: go to staff.japropertysc.com
                    header("Location: https://staff.japropertysc.com");
                } else {
                    // Example: go to customer.japropertysc.com
                    header("Location: https://customer.japropertysc.com");
                }
                exit;
            } else {
                // If the user exists, record the login attempt for throttling/log purposes.
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
