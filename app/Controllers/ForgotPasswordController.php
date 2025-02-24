<?php
namespace App\Controllers;

use App\Database;
use App\Models\User;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ForgotPasswordController
{
    /**
     * Render the forgot password view (GET request).
     */
    public function renderForgotPasswordView() {
        $viewFile = __DIR__ . '/../Views/ForgotPasswordView.html';
        $title = 'Forgot Password - JA Property Management';
        include __DIR__ . '/../Views/layout.php';
    }

    /**
     * Process the forgot password form submission (POST request).
     */
    public function processForgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');

            // 1) Basic validation
            if (empty($email)) {
                // Provide a toast message if the field is empty
                $toastMessage = "Please enter your email address.";
                $viewFile = __DIR__ . '/../Views/ForgotPasswordView.html';
                $title = 'Forgot Password - JA Property Management';
                include __DIR__ . '/../Views/layout.php';
                exit;
            }

            // 2) Check if user exists
            $userModel = new User();
            $user = $userModel->findByEmail($email);

            // For security, we show a generic success message even if the email doesn't exist
            $genericMessage = "If that email exists in our system, a reset link has been sent.";

            // Generate the toast message
            $toastMessage = $genericMessage;

            if (!$user) {
                // We do NOT reveal whether the user is found
                $viewFile = __DIR__ . '/../Views/ForgotPasswordView.html';
                $title = 'Forgot Password - JA Property Management';
                include __DIR__ . '/../Views/layout.php';
                exit;
            }

            // 3) Generate random token (64 chars in hex)
            $token = bin2hex(random_bytes(32));

            // 4) Insert into `password_resets`
            $pdo = Database::connect();
            $stmt = $pdo->prepare("
                INSERT INTO password_resets (email, token, created_at)
                VALUES (:email, :token, NOW())
            ");
            $stmt->execute([
                'email' => $email,
                'token' => $token
            ]);

            // 5) Send an email with a reset link
            $this->sendResetEmail($email, $token);

            // 6) Show the generic success message (as a toast)
            $viewFile = __DIR__ . '/../Views/ForgotPasswordView.html';
            $title = 'Forgot Password - JA Property Management';
            include __DIR__ . '/../Views/layout.php';
            exit;
        }
    }

    /**
     * Send a password reset email using PHPMailer.
     */
    private function sendResetEmail($toEmail, $token)
    {
        // Build a link pointing to /reset-password?token=XYZ
        $resetLink = "https://" . $_SERVER['HTTP_HOST'] . "/index.php?route=reset-password&token=" . $token;
    
        $subject = "Password Reset Request";
        $body = "Hello,\n\n"
              . "We received a request to reset your password. Please click the link below to set a new password:\n"
              . $resetLink . "\n\n"
              . "If you did not request this, please ignore this email.\n\n"
              . "Thank you.";
    
        try {
            $mail = new PHPMailer(true);
    
            // Optional for more detailed debug output:
            // $mail->SMTPDebug = 2;
    
            // 1) Use isSMTP
            $mail->isSMTP();
    
            // 2) Hardcode the SMTP details from your cPanel (ignore getenv calls)
            $mail->Host       = 'server239.web-hosting.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'no-reply@japropertysc.com';
            $mail->Password   = 'XTok6J^h%0]4';
            $mail->SMTPSecure = 'ssl';     // typically "ssl" for port 465
            $mail->Port       = 465;       // cPanel says SMTP Port: 465
    
            // 3) Set the "From" and "To"
            $mail->setFrom('no-reply@japropertysc.com', 'JA Property Management');
            $mail->addAddress($toEmail);
    
            // 4) Subject & Body
            $mail->Subject = $subject;
            $mail->Body    = $body;
    
            // 5) Send
            $mail->send();
    
        } catch (Exception $e) {
            // Log any PHPMailer error messages
            error_log('PHPMailer Error: ' . $mail->ErrorInfo);
        }
    }
    
}
