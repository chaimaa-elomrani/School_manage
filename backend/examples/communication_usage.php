<?php

use App\Facades\CommunicationFacade;
use App\Services\EmailChannel;
use App\Services\SMSChannel;
use App\Services\MessagingService;
use App\Services\NotificationService;
use PDO; 
// Initialize facade
$pdo = new PDO('mysql:host=localhost;dbname=school_manage', 'your_db_user', 'your_db_password');
$facade = new CommunicationFacade(
    new EmailChannel(),
    new SMSChannel(),
    new MessagingService($pdo),
    new NotificationService($pdo),
    $pdo
);

// Send email notification
$facade->sendNotificationByEmail(
    'student@example.com',
    'Grade Update',
    'Your grade has been updated'
);

// Send SMS notification
$facade->sendNotificationBySMS(
    '+1234567890',
    'Payment reminder: Your fee is due tomorrow'
);

// Send internal message
$facade->sendInternalMessage(
    1, // teacher ID
    2, // student ID
    'Assignment Feedback',
    'Great work on your assignment!'
);

// Broadcast to multiple recipients
$recipients = [
    ['id' => 1, 'email' => 'parent1@example.com', 'phone' => '+1111111111'],
    ['id' => 2, 'email' => 'parent2@example.com', 'phone' => '+2222222222']
];

$facade->broadcastNotification(
    $recipients,
    'School Event',
    'Parent-teacher meeting scheduled for next week',
    ['email', 'sms']
);