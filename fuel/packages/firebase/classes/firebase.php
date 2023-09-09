<?php


namespace Firebase;

class Firebase
{

    public function __construct()
    {
        \Config::load('firebase');
    }


    // sending push message to single user by firebase reg id
    public function send($to, $notification, $push)
    {
        $fields = array(
            'to' => $to,
            'priority' => 'high',
            'content_available' => true,
            'notification' => $notification,
            'data' => $push,
        );
        return $this->sendPushNotification($fields);
    }

    // Sending message to a topic by topic name
    public function sendToTopic($to, $notification, $push)
    {


        $fields = array(
            'to' => '/topics/' . $to,
            'priority' => 'high',
            'content_available' => true,
            'notification' => $notification,
            'data' => $push,
        );

        return $this->sendPushNotification($fields);
    }

    // sending push message to multiple users by firebase registration ids
    public function sendMultiple($registration_ids, $notification, $push)
    {
        $fields = array(
            'token' => $registration_ids,
            'priority' => 'high',
            'content_available' => true,
            'notification' => $notification,
            'data' => $push,
        );

        return $this->sendPushNotification($fields);
    }

    // function makes curl request to firebase servers
    private function sendPushNotification($fields)
    {

        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';

        $headers = array(
            'Authorization: key=' . \Config::get('api_key'),
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            $error = 'Curl failed: ' . curl_error($ch);
            throw new \Exception($error, 1);
        }

        // Close connection
        curl_close($ch);

        return $result;
    }
}
