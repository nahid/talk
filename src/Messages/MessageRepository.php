<?php

namespace Nahid\Talk\Messages;

use SebastianBerc\Repositories\Repository;

class MessageRepository extends Repository
{
    public function takeModel()
    {
        return Message::class;
    }

    public function deleteMessages($conversationId)
    {
        $delete = Message::where('conversation_id', $conversationId)->delete();
        if($delete) {
            return true;
        }
        return false;
    }
}
