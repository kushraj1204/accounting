<?php

class AzureNotificationHub
{
    const API_VERSION = "?api-version=2013-10";

    private $endpoint;
    private $hubPath;
    private $sasKeyName;
    private $sasKeyValue;

    function __construct($connectionString, $hubPath)
    {
        $this->hubPath = $hubPath;

        $this->parseConnectionString($connectionString);
    }

    private function parseConnectionString($connectionString)
    {
        $parts = explode(";", $connectionString);
        if (sizeof($parts) != 3) {
            throw new Exception("Error parsing connection string: " . $connectionString);
        }

        foreach ($parts as $part) {
            if (strpos($part, "Endpoint") === 0) {
                $this->endpoint = "https" . substr($part, 11);
            } else if (strpos($part, "SharedAccessKeyName") === 0) {
                $this->sasKeyName = substr($part, 20);
            } else if (strpos($part, "SharedAccessKey") === 0) {
                $this->sasKeyValue = substr($part, 16);
            }
        }
    }

    private function generateSasToken($uri)
    {
        $targetUri = strtolower(rawurlencode(strtolower($uri)));

        $expires = time();
        $expiresInMins = 60;
        $expires = $expires + $expiresInMins * 60;
        $toSign = $targetUri . "\n" . $expires;

        $signature = rawurlencode(base64_encode(hash_hmac('sha256', $toSign, $this->sasKeyValue, TRUE)));

        $token = "SharedAccessSignature sr=" . $targetUri . "&sig="
            . $signature . "&se=" . $expires . "&skn=" . $this->sasKeyName;

        return $token;
    }

    public function broadcastNotification($notification)
    {
        $this->sendNotification($notification, "");
    }

    public function sendNotification($notification, $tagsOrTagExpression)
    {
        //echo $tagsOrTagExpression . "<p>";

        if (is_array($tagsOrTagExpression)) {
            $tagExpression = implode(" || ", $tagsOrTagExpression);
        } else {
            $tagExpression = $tagsOrTagExpression;
        }

        # build uri
        $uri = $this->endpoint . $this->hubPath . "/messages" . AzureNotificationHub::API_VERSION;

        //echo $uri . "<p>";

        $ch = curl_init($uri);

        if (in_array($notification->format, ["template", "apple", "gcm"])) {
            $contentType = "application/json";
        } else {
            $contentType = "application/xml";
        }

        $token = $this->generateSasToken($uri);

        $headers = [
            'Authorization: ' . $token,
            'Content-Type: ' . $contentType,
            'ServiceBusNotification-Format: ' . $notification->format
        ];

        if ("" !== $tagExpression) {
            $headers[] = 'ServiceBusNotification-Tags: ' . $tagExpression;
        }

        # add headers for other platforms
        if (is_array($notification->headers)) {
            $headers = array_merge($headers, $notification->headers);
        }

        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $notification->payload
        ));

        // Send the request
        $response = curl_exec($ch);

        // Check for errors
        if ($response === FALSE) {
            throw new Exception(curl_error($ch));
        }

        $info = curl_getinfo($ch);

        if ($info['http_code'] <> 201) {
            throw new Exception('Error sending notification: ' . $info['http_code'] . ' msg: ' . $response);
        }

        //print_r($info);

        //echo $response;
    }

    public function registerDevice($device_type, $tagsOrTagExpression, $device_code)
    {
        $uri = $this->endpoint . $this->hubPath . "/registrations" . AzureNotificationHub::API_VERSION;
        $ch = curl_init();

        $token = $this->generateSasToken($uri);

        $headers = [
            'Authorization: ' . $token,
            'Content-Type: application/xml',
            'x-ms-version: 2015-01'
        ];

        $request_body = $this->requestBodyRegistration($device_type, $tagsOrTagExpression, $device_code);

        if (is_null($request_body)) {
            return null;
        }

        curl_setopt_array($ch, array(
            CURLOPT_URL => $uri,
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $request_body
        ));

        // Send the request
        $response = curl_exec($ch);
        log_message('error', 'azure register response ' . json_encode($response));

        // Check for errors
        if ($response === FALSE) {
            throw new Exception(curl_error($ch));
        }

        $info = curl_getinfo($ch);
        curl_close($ch);
    }

    private function requestBodyRegistration($device_type, $tagsOrTagExpression, $device_code)
    {
        $device_code = strtolower($device_type);
        switch ($device_type) {
            case 'apple':
            case 'ios':
                return '<?xml version="1.0" encoding="utf-8"?>
                        <entry xmlns="http://www.w3.org/2005/Atom">
                            <content type="application/xml">
                                <AppleRegistrationDescription xmlns:i="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://schemas.microsoft.com/netservices/2010/10/servicebus/connect">
                                    <Tags>' . $tagsOrTagExpression . '</Tags>
                                    <DeviceToken>' . $device_code . '</DeviceToken>
                                </AppleRegistrationDescription>
                            </content>
                        </entry>';
            case 'gcm':
            case 'fcm':
            case 'android':
                return '<?xml version="1.0" encoding="utf-8"?>
                                <entry xmlns="http://www.w3.org/2005/Atom">
                                    <content type="application/xml">
                                        <GcmRegistrationDescription xmlns:i="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://schemas.microsoft.com/netservices/2010/10/servicebus/connect">
                                                <Tags>' . $tagsOrTagExpression . '</Tags>
                                                <GcmRegistrationId>' . $device_code . '</GcmRegistrationId>
                                        </GcmRegistrationDescription>
                                    </content>
                                </entry>';
            case 'windows':
                return '<?xml version="1.0" encoding="utf-8"?>
                            <entry xmlns="http://www.w3.org/2005/Atom">
                                <content type="application/xml">
                                    <WindowsRegistrationDescription xmlns:i="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://schemas.microsoft.com/netservices/2010/10/servicebus/connect">
                                        <Tags>' . $tagsOrTagExpression . '</Tags>
                                        <ChannelUri>' . $device_code . '</ChannelUri>
                                    </WindowsRegistrationDescription>
                                </content>
                            </entry>';
            default:
                return null;
        }
    }
}