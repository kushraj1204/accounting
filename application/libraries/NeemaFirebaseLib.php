<?php

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class NeemaFirebaseLib
{
    private $firebase;

    public function __construct()
    {
        if (!$this->firebase) {
            $this->firebase = (new Factory)
                ->withServiceAccount(FCPATH . '../private/firebase-adminsdk.json');
        }
    }

    public function sendMessage($title, $body, $device_token, $data = array())
    {
        $message = CloudMessage::fromArray([
            'token' => $device_token,
            'notification' => ['title' => $title, 'body' => $body],
            'data' => ['Title' => $title, 'Message' => $body],
            'android' => [
                'priority' => 'high',
                'notification' => [
                    'sound' => 'default',
                    'default_vibrate_timings' => true,
                    'default_sound' => true,
                    //'notification_count' => 42,
                    //'color' => '#200e57',
                    'notification_priority' => 'PRIORITY_HIGH' // PRIORITY_LOW , PRIORITY_DEFAULT , PRIORITY_HIGH , PRIORITY_MAX
                ],
            ],
            'apns' => [
                'headers' => [
                    'apns-priority' => '10',
                ],
                'payload' => [
                    'aps' => [
                        'alert' => [
                            'title' => $title,
                            'body' => $body,
                        ],
                        'sound' => 'default',
                        //'badge' => 42,
                    ],
                ],
            ],

        ]);

        $messaging = $this->firebase->createMessaging();
        try {
            $response = $messaging->send($message);
            log_message('error', 'fcm response ' . json_encode($response));
        } catch (\Kreait\Firebase\Exception\MessagingException $e) {
            log_message('error', 'Messaging exception ' . $e->getMessage());
        } catch (\Kreait\Firebase\Exception\FirebaseException $e) {
            log_message('error', 'Firebase exception ' . $e->getMessage());
        } catch (Exception $e) {
            log_message('error', 'exception occurred ' . $e->getMessage());
        }
    }

    public function sendToMultiple($title, $body, $devices = array())
    {
        $notification = Notification::create($title, $body);
        $message = CloudMessage::new();
        $message->withNotification($notification);
        $messaging = $this->firebase->getMessaging();
        $report = $messaging->sendMulticast($message, $devices);
        echo 'Successful sends: ' . $report->successes()->count() . PHP_EOL;
        echo 'Failed sends: ' . $report->failures()->count() . PHP_EOL;

        if ($report->hasFailures()) {
            foreach ($report->failures()->getItems() as $failure) {
                echo $failure->error()->getMessage() . PHP_EOL;
            }
        }
    }
}