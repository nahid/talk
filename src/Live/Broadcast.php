<?php

namespace Nahid\Talk\Live;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Config\Repository;
use Nahid\Talk\Messages\Message;
use Pusher;

class Broadcast
{
    use DispatchesJobs;

    /*
     * Constant for talk config prefix
     *
     * @const string
     * */
    const CONFIG_PATH = 'talk';

    /*
   * Set all configs from talk configurations
   *
   * @var array
   * */
    protected $config;


    /*
   * Pusher instance
   *
   * @var object
   * */
    public $pusher;

    /**
     * Connect pusher and get all credentials from config.
     *
     * @param \Illuminate\Contracts\Config\Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;
        $this->pusher = $this->connectPusher();
    }

    /**
     * Make pusher connection.
     *
     * @param array $options
     *
     * @return object | bool
     */
    protected function connectPusher($options = [])
    {
        if ($this->getConfig('broadcast.enable')) {
            $appId = $this->getConfig('broadcast.pusher.app_id');
            $appKey = $this->getConfig('broadcast.pusher.app_key');
            $appSecret = $this->getConfig('broadcast.pusher.app_secret');
            $appOptions = $this->getConfig('broadcast.pusher.options');

            $newOptions = array_merge($appOptions, $options);
            $pusher = new Pusher($appKey, $appSecret, $appId, $newOptions);

            return $pusher;
        }

        return false;
    }

    /**
     * Dispatch the job to the queue.
     *
     * @param \Nahid\Talk\Messages\Message $message
     */
    public function transmission(Message $message)
    {
        if (!$this->pusher) {
            return false;
        }

        $sender = $message->sender->toArray();
        $messageArray = $message->toArray();
        $messageArray['sender'] = $sender;
        $this->dispatch(new Webcast($messageArray));
    }

    /**
     * get specific config from talk configurations.
     *
     * @param  string
     *
     * @return string|array|int
     */
    public function getConfig($name)
    {
        return $this->config->get(self::CONFIG_PATH.'.'.$name);
    }
}
