<?php

namespace App\Services;

use App\Interfaces\IEmailChannel;

class EmailChannel implements IEmailChannel
{
    private $smtpHost;
    private $smtpPort;
    private $username;
    private $password;

    public function __construct(array $config = [])
    {
        $this->smtpHost = $config['smtp_host'] ?? 'localhost';
        $this->smtpPort = $config['smtp_port'] ?? 587;
        $this->username = $config['username'] ?? '';
        $this->password = $config['password'] ?? '';
    }

    public function sendEmail(string $to, string $subject, string $body): bool
    {
        try {
            // Simulate email sending (replace with actual SMTP implementation)
            error_log("Sending email to: $to, Subject: $subject");
            error_log("Email body: $body");
            
            // In real implementation, use PHPMailer or similar
            // mail($to, $subject, $body);
            
            return true;
        } catch (\Exception $e) {
            error_log("Email sending failed: " . $e->getMessage());
            return false;
        }
    }

    public function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}