<?php
namespace App\Controllers;

use App\Database;
use App\Models\User;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ForgotPasswordController {
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
    public function processForgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');

            // 1) Basic validation
            if (empty($email)) {
                $error = "Please enter your email address.";
                // Re-render the same view with $error if you want:
                $viewFile = __DIR__ . '/../Views/ForgotPasswordView.html';
                $title = 'Forgot Password - JA Property Management';
                include __DIR__ . '/../Views/layout.php';
                exit;
            }

            // 2) Check if user exists
            $userModel = new User();
            $user = $userModel->findByEmail($email);

            // For security, we can show a generic success message even if the email doesn't exist
            $genericMessage = "If that email exists in our system, a reset link has been sent.";

            if (!$user) {
                // We do NOT reveal whether the user is found. Just show the generic message.
                echo $genericMessage;
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

            // 6) Show the generic success message
            echo $genericMessage;
            exit;
        }
    }

    /**
     * Send a password reset email using PHPMailer.
     */
    private function sendResetEmail($toEmail, $token) {
        // Build a link pointing to /reset-password?token=XYZ
        // Use your domain name or environment variable to construct the URL:
        $resetLink = "https://" . $_SERVER['HTTP_HOST'] . "/index.php?route=reset-password&token=" . $token;

        $subject = "Password Reset Request";
        $body = "Hello,\n\n"
              . "We received a request to reset your password. Please click the link below to set a new password:\n"
              . $resetLink . "\n\n"
              . "If you did not request this, please ignore this email.\n\n"
              . "Thank you.";

        try {
            $mail = new PHPMailer(true);
            // Server settings
            $mail->isSMTP();
            $mail->Host       = getenv('MAIL_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = getenv('MAIL_USERNAME');
            $mail->Password   = getenv('MAIL_PASSWORD');
            $mail->SMTPSecure = getenv('MAIL_ENCRYPTION') ?: 'tls';
            $mail->Port       = getenv('MAIL_PORT') ?: 587;

            // Recipients
            $mail->setFrom('no-reply@japropertysc.com', 'JA Property Management');
            $mail->addAddress($toEmail);

            // Content
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
        } catch (Exception $e) {
            // Log or handle error. In production, you might want to
            // fall back to a different mail service or log the error.
        }
    }
}
