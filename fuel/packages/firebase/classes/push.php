<?php


namespace Firebase;

class Push
{

    // push message title
    private $title;
    private $message;
    private $image;
    private $icon;
    private $action;
    // push message payload
    private $payload;
    // flag indicating whether to show the push
    // notification or not
    // this flag will be useful when perform some opertation
    // in background when push is recevied
    private $is_background;


    private $vibrate;
    private $sound;

    function __construct()
    {
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setImage($imageUrl)
    {
        $this->image = $imageUrl;
    }

    public function setIcon($iconUrl)
    {
        $this->icon = $iconUrl;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function setPayload($payload)
    {
        $this->payload = $payload;
    }

    public function setVibrate($vibrate)
    {
        $this->vibrate = $vibrate;
    }

    public function setSound($sound)
    {
        $this->sound = $sound;
    }

    public function setIsBackground($is_background)
    {
        $this->is_background = $is_background;
    }

    public function getPush()
    {
        $res = array();
        $res['title'] = $this->title;
        $res['is_background'] = $this->is_background;
        $res['message'] = $this->message;
        $res['image'] = $this->image;
        $res['icon'] = $this->icon;
        $res['action'] = $this->action;
        $res['payload'] = $this->payload;
        $res['timestamp'] = date('Y-m-d G:i:s');
        return $res;
    }

    public function getNotification()
    {
        $res = array();
        $res['body'] = $this->message;
        $res['title'] = $this->title;
        $res['vibrate'] = $this->vibrate;
        $res['sound'] = $this->sound;
        return $res;
    }

    public function getpayload()
    {
        $res = array();
        $res['notification'] = $this->getNotification();
        $res['payload'] = $this->getPush();
        return $res;
    }
}
