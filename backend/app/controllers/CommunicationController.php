<?php

namespace App\Controllers;

use App\Facades\CommunicationFacade;
use App\Services\EmailChannel;
use App\Services\SMSChannel;
use App\Services\MessagingService;
use App\Services\NotificationService;
use Core\Db;

class CommunicationController
{
    
    private $communicationFacade;

    public function __construct()
    {
        $pdo = Db::connection();
        
        // Load email configuration
        $emailConfig = include __DIR__ . '/../../config/email.php';
        
        // Initialize channels and services
        $emailChannel = new EmailChannel($emailConfig);
        $smsChannel = new SMSChannel();
        $messagingService = new MessagingService($pdo);
        $notificationService = new NotificationService($pdo);
        
        // Initialize facade
        $this->communicationFacade = new CommunicationFacade(
            $emailChannel,
            $smsChannel,
            $messagingService,
            $notificationService,
            $pdo
        );
    }

    public function sendEmailNotification()
    {
        header('Content-Type: application/json');
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            error_log("Email request received: " . json_encode($input));
            
            if (!isset($input['email'], $input['title'], $input['message'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Missing required fields', 'success' => false]);
                return;
            }

            // Load email configuration
            $emailConfig = include __DIR__ . '/../../config/email.php';
            
            // Create email channel
            $emailChannel = new \App\Services\EmailChannel($emailConfig);
            
            // Send email
            $result = $emailChannel->sendEmail(
                $input['email'],
                $input['title'],
                $input['message']
            );

            error_log("Email send result: " . ($result ? 'SUCCESS' : 'FAILED'));

            echo json_encode([
                'success' => $result,
                'message' => $result ? 'Email sent successfully' : 'Failed to send email'
            ]);

        } catch (\Exception $e) {
            error_log("Email controller error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage(), 'success' => false]);
        }
    }

    public function sendSMSNotification()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['phone'], $input['message'])) {
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        $result = $this->communicationFacade->sendNotificationBySMS(
            $input['phone'],
            $input['message']
        );

        echo json_encode([
            'success' => $result,
            'message' => $result ? 'SMS sent successfully' : 'Failed to send SMS'
        ]);
    }

    public function sendInternalMessage()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['sender_id'], $input['receiver_id'], $input['subject'], $input['content'])) {
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        $result = $this->communicationFacade->sendInternalMessage(
            $input['sender_id'],
            $input['receiver_id'],
            $input['subject'],
            $input['content']
        );

        echo json_encode([
            'success' => $result,
            'message' => $result ? 'Message sent successfully' : 'Failed to send message'
        ]);
    }

    public function broadcastNotification()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['recipients'], $input['title'], $input['message'])) {
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        $channels = $input['channels'] ?? ['email'];
        
        $results = $this->communicationFacade->broadcastNotification(
            $input['recipients'],
            $input['title'],
            $input['message'],
            $channels
        );

        echo json_encode([
            'success' => true,
            'message' => 'Broadcast completed',
            'results' => $results
        ]);
    }
}



