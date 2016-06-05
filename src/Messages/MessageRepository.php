<?php

namespace Nahid\Talk\Messages;

use Illuminate\Support\Collection;
use Validator;
use DB;
use SebastianBerc\Repositories\Repository;
use Nahid\Talk\Messages\Message;


class MessageRepository extends Repository
{
    public function takeModel()
    {
        return Message::class;
    }

    public function getConversations($conversationId)
	{
		$readMessage=DB::select(DB::raw(
			"SELECT U.name, M.id, U.id as user_id, M.message, M.created_at
			FROM ".DB::getTablePrefix()."users U, ".DB::getTablePrefix()."messages M
			WHERE M.user_id = U.id
			AND M.conversation_id = {$conversationId}
			order by M.created_at asc"
		));
		return $readMessage;

	}

    public function getMessageByConversationId($id)
    {
        return $this->with('user')->where('conversation_id', $id)->all();
    }


}
