<?php

namespace App\Services;

class WhatsAppService
{
    public function formatPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Convert Indonesian format to international
        if (str_starts_with($phone, '08')) {
            $phone = '628' . substr($phone, 2);
        } elseif (str_starts_with($phone, '8')) {
            $phone = '62' . $phone;
        } elseif (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }
        
        return $phone;
    }
    
    public function generateWhatsAppUrl(string $phone, string $message = ''): string
    {
        $formattedPhone = $this->formatPhoneNumber($phone);
        $encodedMessage = urlencode($message);
        
        return "https://wa.me/{$formattedPhone}" . ($message ? "?text={$encodedMessage}" : '');
    }
    
    public function processMessage(string $message, array $variables = []): string
    {
        $processedMessage = $message;
        
        foreach ($variables as $key => $value) {
            $processedMessage = str_replace("{{$key}}", $value, $processedMessage);
        }
        
        return $processedMessage;
    }
    
    public function validatePhoneNumber(string $phone): bool
    {
        $formattedPhone = $this->formatPhoneNumber($phone);
        
        // Indonesian phone number should be 10-15 digits after country code
        return preg_match('/^62[0-9]{8,13}$/', $formattedPhone);
    }
}
