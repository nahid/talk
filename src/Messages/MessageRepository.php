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
        if ($delete) {
            return true;
        }

        return false;
    }

    public function softDeleteMessage($messageId, $authUserId)
    {
        $message = $this->with(['conversation' => function ($q) use ($authUserId) {
            $q->where('user_one', $authUserId);
            $q->orWhere('user_two', $authUserId);
        }])->find($messageId);

        if (!is_null($message->conversation)) {
            if ($message->user_id == $authUserId) {
                $message->deleted_from_sender = 1;
            } else {
                $message->deleted_from_receiver = 1;
            }

            $deleteMessage = $this->update($message);

            if ($deleteMessage) {
                return true;
            }
        }

        return false;
    }
}
