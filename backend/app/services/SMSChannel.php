<?php

namespace App\Services;

use App\Interfaces\ISMSChannel;

class SMSChannel implements ISMSChannel
{
    private $apiKey;
    private $apiUrl;

    public function __construct(array $config = [])
    {
        $this->apiKey = $config['api_key'] ?? '';
        $this->apiUrl = $config['api_url'] ?? 'https://api.sms-provider.com/send';
    }

    public function sendSMS(string $phoneNumber, string $message): bool
    {
        try {
            // Simulate SMS sending (replace with actual SMS API)
            error_log("Sending SMS to: $phoneNumber");
            error_log("SMS message: $message");
            
            // In real implementation, use cURL to call SMS API
            // $response = $this->callSMSAPI($phoneNumber, $message);
            
            return true;
        } catch (\Exception $e) {
            error_log("SMS sending failed: " . $e->getMessage());
            return false;
        }
    }

    public function validatePhoneNumber(string $phoneNumber): bool
    {
        // Basic phone number validation
        return preg_match('/^\+?[1-9]\d{1,14}$/', $phoneNumber);
    }

    private function callSMSAPI(string $phoneNumber, string $message): array
    {
        // Implementation for actual SMS API call
        return ['status' => 'sent'];
    }
}