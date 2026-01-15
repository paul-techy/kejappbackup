<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ZettatelSmsService
{
    protected $apiUrl = 'https://portal.zettatel.com/SMSApi/send';
    protected $userId;
    protected $password;
    protected $apiKey;
    protected $senderId;

    public function __construct()
    {
        $settings = settings();
        $this->userId = $settings['zettatel_user_id'] ?? '';
        $this->password = $settings['zettatel_password'] ?? '';
        $this->apiKey = $settings['zettatel_api_key'] ?? '';
        $this->senderId = $settings['zettatel_sender_id'] ?? '';
    }

    /**
     * Send SMS using file upload method
     * 
     * @param string $filePath Path to the CSV/Excel file with mobile numbers
     * @param string $message Message content to send
     * @param array $options Additional options (msgType, duplicatecheck, scheduleTime, etc.)
     * @return array Response from Zettatel API
     */
    public function sendSmsFile($filePath, $message, $options = [])
    {
        try {
            // Check if file exists
            if (!file_exists($filePath)) {
                return [
                    'status' => 'error',
                    'message' => 'File not found: ' . $filePath,
                ];
            }

            // Get file MIME type
            $mimeType = 'text/csv';
            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
            if ($fileExtension == 'xls' || $fileExtension == 'xlsx') {
                $mimeType = 'application/vnd.ms-excel';
            } elseif ($fileExtension == 'zip') {
                $mimeType = 'application/zip';
            }

            // Prepare form data
            $formData = [
                'sendMethod' => 'bulkupload',
                'file' => new \CURLFile($filePath, $mimeType, basename($filePath)),
                'msg' => $message,
                'senderid' => $this->senderId,
                'msgType' => $options['msgType'] ?? 'text',
                'output' => $options['output'] ?? 'json',
                'duplicatecheck' => $options['duplicatecheck'] ?? 'true',
            ];

            // Add optional parameters
            if (isset($options['scheduleTime'])) {
                $formData['scheduleTime'] = $options['scheduleTime'];
            }
            if (isset($options['trackLink'])) {
                $formData['trackLink'] = $options['trackLink'];
            }
            if (isset($options['smartLinkTitle'])) {
                $formData['smartLinkTitle'] = $options['smartLinkTitle'];
            }
            if (isset($options['testMessage'])) {
                $formData['testMessage'] = $options['testMessage'];
            }

            // Use userId/password or apiKey for authentication
            if (!empty($this->apiKey)) {
                $headers = [
                    'apiKey' => $this->apiKey,
                ];
            } else {
                $formData['userid'] = $this->userId;
                $formData['password'] = urlencode($this->password);
                $headers = [];
            }

            // Initialize cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $formData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            // Set headers if using API key
            if (!empty($headers)) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['apiKey: ' . $this->apiKey]);
            }

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                Log::error('Zettatel SMS cURL Error: ' . $error);
                return [
                    'status' => 'error',
                    'message' => 'Failed to connect to Zettatel API: ' . $error,
                ];
            }

            // Parse response
            $responseData = json_decode($response, true);

            if ($httpCode == 200 && isset($responseData['status']) && $responseData['status'] == 'success') {
                return [
                    'status' => 'success',
                    'transactionId' => $responseData['transactionId'] ?? '',
                    'message' => $responseData['reason'] ?? 'SMS sent successfully',
                    'data' => $responseData,
                ];
            } else {
                Log::error('Zettatel SMS Error Response: ' . $response);
                return [
                    'status' => 'error',
                    'message' => $responseData['reason'] ?? 'Failed to send SMS',
                    'data' => $responseData,
                ];
            }
        } catch (\Exception $e) {
            Log::error('Zettatel SMS Exception: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Exception occurred: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Create CSV file with mobile numbers
     * 
     * @param array $mobileNumbers Array of mobile numbers
     * @param string $filename Optional filename
     * @return string Path to created file
     */
    public function createMobileFile($mobileNumbers, $filename = null)
    {
        $filename = $filename ?? 'mobile_numbers_' . time() . '.csv';
        $filePath = storage_path('app/temp/' . $filename);

        // Create temp directory if it doesn't exist
        $directory = dirname($filePath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Create CSV file
        $file = fopen($filePath, 'w');
        if ($file) {
            // Write header
            fputcsv($file, ['Mobile']);
            
            // Write mobile numbers
            foreach ($mobileNumbers as $number) {
                // Clean and format mobile number
                $cleanNumber = preg_replace('/[^0-9+]/', '', $number);
                if (!empty($cleanNumber)) {
                    fputcsv($file, [$cleanNumber]);
                }
            }
            fclose($file);
        }

        return $filePath;
    }

    /**
     * Clean up temporary file
     * 
     * @param string $filePath
     * @return bool
     */
    public function cleanupFile($filePath)
    {
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }

    /**
     * Validate configuration
     * 
     * @return bool
     */
    public function isConfigured()
    {
        return (!empty($this->apiKey) || (!empty($this->userId) && !empty($this->password))) 
            && !empty($this->senderId);
    }
}
