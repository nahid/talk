<?php

namespace Nahid\Talk\Messages;

use Illuminate\Database\Eloquent\Model;

class MessageRepository extends Model
{
    protected $table = 'messages';

    public function deleteMessages($conversationId)
    {
        return (bool) Message::where('conversation_id', $conversationId)->delete();
    }

    public function softDeleteMessage($messageId, $authUserId)
    {
        $message = $this->with(['conversation' => function ($q) use ($authUserId) {
            $q->where('user_one', $authUserId);
            $q->orWhere('user_two', $authUserId);
        }])->find($messageId);

        if (is_null($message->conversation)) {
            return false;
        }

        if ($message->user_id == $authUserId) {
            $message->deleted_from_sender = 1;
        } else {
            $message->deleted_from_receiver = 1;
        }

        return (bool) $this->update($message);
    }

    }
}
