<?php

namespace Nahid\Talk\Live;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Nahid\Talk\Live\Broadcast;

class Webcast implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;


    protected $message;
    protected $broadcast;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($message)
    {

        $this->message = $message;

    }

    /*
     * Execute the job.
     *
     * @return void
     */
    public function handle(Broadcast $broadcast)
    {
        $this->broadcast = $broadcast;
        $toUser = ($this->message['sender']['id']==$this->message['conversation']['user_one'])?$this->message['conversation']['user_two']:$this->message['conversation']['user_one'];

        $channelForUser = $this->broadcast->getConfig('broadcast.app_name') . '-user-' . $toUser;
        $channelForConversation = $this->broadcast->getConfig('broadcast.app_name') . '-conversation-' . $this->message['conversation_id'];

        $this->broadcast->pusher->trigger([$channelForUser, $channelForConversation], 'talk-send-message', $this->message);
    }
}
