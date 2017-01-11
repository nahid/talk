<?php

namespace Nahid\Talk\Live;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Config\Repository;
use Nahid\Talk\Messages\Message;
use Nahid\Talk\Live\Webcast;
use Pusher;

class Broadcast
{
    use DispatchesJobs;

    const CONFIG_PATH = 'talk';

    protected $config;
    protected $options = [
        'encrypted'        => false
    ];

    public $pusher;

    function __construct(Repository $config)
    {
        $this->config = $config;
        $this->pusher = $this->connectPusher();
    }


    protected function connectPusher($options = [])
    {
        if ($this->getConfig('broadcast.enable')) {
            $appId = $this->getConfig('broadcast.pusher.app_id');
            $appKey = $this->getConfig('broadcast.pusher.app_key');
            $appSecret = $this->getConfig('broadcast.pusher.app_secret');

            $newOptions = array_merge($this->options, $options);
            $pusher = new Pusher($appKey, $appSecret, $appId, $newOptions);
            return $pusher;
        }

        return false;
    }

    public function transmission(Message $message)
    {
        if (!$this->pusher) return false;

        $sender = $message->sender->toArray();
        $messageArray = $message->toArray();
        $messageArray['sender'] = $sender;
        $this->dispatch(new Webcast($messageArray));
    }

    public function getConfig($name)
    {
        return $this->config->get(self::CONFIG_PATH . '.' .$name);
    }

}
