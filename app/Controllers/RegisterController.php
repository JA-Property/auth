<?php
namespace App\Controllers;

use App\Models\Customer;
use App\Models\User;
use PDO;

class RegisterController
{
    /**
     * STEP 1 (GET): Show the form where the user enters "account_number" and "billing_zip".
     */
    public function renderAccountCheckView()
    {
        $viewFile = __DIR__ . '/../Views/RegisterView.html'; 
        $title = 'Register - JA Property Management';
        include __DIR__ . '/../Views/layout.php';
    }

    /**
     * STEP 1 (POST): Process the submitted "account_number" + "billing_zip".
     */
    public function processAccountCheck()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accountNumber = trim($_POST['account_number'] ?? '');
            $billingZip    = trim($_POST['billing_zip'] ?? '');

            // Basic validation
            if (empty($accountNumber) || empty($billingZip)) {
                // Return generic "try again" toast
                $toastMessage = "Please check your info and try again.";
                $error = "Please fill in the required fields.";
                $viewFile = __DIR__ . '/../Views/RegisterView.html';
                $title = 'Register - JA Property Management';
                include __DIR__ . '/../Views/layout.php';
                exit;
            }

            // Look up matching customer record
            $customerModel = new Customer();
            $customer = $customerModel->findByAccountNumberAndZip($accountNumber, $billingZip);

            if (!$customer) {
                // No match found => generic error toast
                $toastMessage = "Please check your info and try again.";
                $error = "We could not find a matching account with that Account Number and Billing ZIP.";
                $viewFile = __DIR__ . '/../Views/RegisterView.html';
                $title = 'Register - JA Property Management';
                include __DIR__ . '/../Views/layout.php';
                exit;
            }

            // If found, store relevant info in SESSION so we can use in the next step
            $_SESSION['reg_customer_id'] = $customer['id'];
            $_SESSION['reg_display_name'] = $customer['display_name'] ?: ($customer['first_name'] . ' ' . $customer['last_name']);

            // Move on to the next step (creating the actual user)
            header("Location: index.php?route=register-create");
            exit;
        }
    }

    /**
     * STEP 2 (GET): Show the form where the user picks email/password
     */
    public function renderUserCreationView()
    {
        // If the user never completed step 1, redirect them
        if (!isset($_SESSION['reg_customer_id'])) {
            header("Location: index.php?route=register");
            exit;
        }

        // Render a new view that asks for email/password
        $viewFile = __DIR__ . '/../Views/RegisterCreateView.html';
        $title = 'Create Account - JA Property Management';
        include __DIR__ . '/../Views/layout.php';
    }

    /**
     * STEP 2 (POST): Actually create the user row in `users`
     */
    public function processUserCreation()
    {
        // Ensure step 1 is completed
        if (!isset($_SESSION['reg_customer_id'])) {
            header("Location: index.php?route=register");
            exit;
        }

        $email            = trim($_POST['email'] ?? '');
        $password         = trim($_POST['password'] ?? '');
        $confirmPassword  = trim($_POST['confirm_password'] ?? '');

        // Validate
        if (empty($email) || empty($password) || empty($confirmPassword)) {
            // Generic "try again" toast
            $toastMessage = "Please check your info and try again.";
            $error = "Please fill in all fields.";
            $viewFile = __DIR__ . '/../Views/RegisterCreateView.html';
            $title = 'Create Account - JA Property Management';
            include __DIR__ . '/../Views/layout.php';
            exit;
        }

        if ($password !== $confirmPassword) {
            $toastMessage = "Please check your info and try again.";
            $error = "Passwords do not match.";
            $viewFile = __DIR__ . '/../Views/RegisterCreateView.html';
            $title = 'Create Account - JA Property Management';
            include __DIR__ . '/../Views/layout.php';
            exit;
        }

        // Hash the password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Build data for new user
        $userData = [
            'email'         => $email,
            'password_hash' => $passwordHash,
            'role'          => 'customer',
            'onboarded'     => 0,
            'verified'      => 0,
        ];

        // Use the display_name from session (or fallback)
        $displayName = $_SESSION['reg_display_name'] ?? 'New Customer';

        // If your `users` table REQUIRES `initials` or `display_name`, set them:
        $initials = $this->makeInitials($displayName);
        $userData['initials']     = $initials;
        $userData['display_name'] = $displayName;

        // Create the user in DB
        $userModel = new User();
        $newUserId = $userModel->createFull($userData);  // We'll define createFull() below

        if ($newUserId) {
            // Optionally link the user to the customer record
            if (isset($_SESSION['reg_customer_id'])) {
                $customerModel = new Customer();
                $customerModel->updateUserId($_SESSION['reg_customer_id'], $newUserId);
            }

            // Clear session data
            unset($_SESSION['reg_customer_id'], $_SESSION['reg_display_name']);

            // Redirect to login or next step
            header("Location: index.php?route=login&registered=1");
            exit;
        } else {
            $toastMessage = "Please check your info and try again.";
            $error = "An error occurred while creating your account. Please try again.";
            $viewFile = __DIR__ . '/../Views/RegisterCreateView.html';
            $title = 'Create Account - JA Property Management';
            include __DIR__ . '/../Views/layout.php';
            exit;
        }
    }

    /**
     * Simple helper to create initials from a display name.
     */
    private function makeInitials($name)
    {
        // Example: "John Smith" => "JS"
        $parts = preg_split('/\s+/', trim($name));
        $initials = '';
        foreach ($parts as $part) {
            $initials .= strtoupper(substr($part, 0, 1));
        }
        return $initials;
    }
}
