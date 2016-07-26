<?php

namespace Nahid\Talk\Messages;

use Illuminate\Support\Facades\DB;
use SebastianBerc\Repositories\Repository;

class MessageRepository extends Repository
{
    public function takeModel()
    {
        return Message::class;
    }

    public function getConversations($conversationId)
    {
        $readMessage = DB::select(
            DB::raw(
<<<<<<< HEAD
                'SELECT U.name, M.id, U.id as user_id, M.message, M.created_at
=======
                'SELECT U.name, M.id, U.id as user_id, M.deleted_from_sender, M.deleted_from_reciever, M.message, M.created_at
>>>>>>> a2f2c2ab0029193e0d5cb47bf991f8669f65896b
			    FROM ' . DB::getTablePrefix() . 'users U, ' . DB::getTablePrefix() . 'messages M
			    WHERE M.user_id = U.id
			    AND M.conversation_id = ?
			    order by M.created_at asc',
                [$conversationId]
            )
        );
        return $readMessage;
    }

    public function getMessageByConversationId($id)
    {
        return $this->with('user')->where('conversation_id', $id)->all();
    }
}
