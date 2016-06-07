<?php

namespace Nahid\Talk;

use Nahid\Talk\Conversations\ConversationRepository;
use Nahid\Talk\Messages\Message;
use Nahid\Talk\Messages\MessageRepository;

/**
 * Class Talk
 *
 * @author Nahid Bin Azhar
 * @package Nahid\Talk
 */
class Talk
{
    protected $conversation;
    protected $message;
    protected $authUserId;

    public function __construct(ConversationRepository $conversation, MessageRepository $message)
    {
        $this->conversation = $conversation;
        $this->message = $message;
    }

    public function setAuthUserId($id)
    {
        $this->authUserId = $id;
    }


    public function isConversationExists($userId)
    {
        if (empty($userId)) {
            return false;
        }

        $user = $this->getSerializeUser($this->authUserId, $userId);
        return $this->conversation->isExistsAmongTwoUsers($user['one'], $user['two']);
    }


    public function isAuthenticUser($conversationId, $userId)
    {
        if ($conversationId && $userId) {
            return $this->conversation->isUserExists($conversationId, $userId);
        }
        return false;
    }

    protected function newConversation($receiverId)
    {
        $convId = $this->isConversationExists($receiverId);
        $user = $this->getSerializeUser($this->authUserId, $receiverId);

        if ($convId === false) {
            $conv = $this->conversation->create([
                'user_one' => $user['one'],
                'user_two' => $user['two'],
                'status' => 1
            ]);

            if ($conv) {
                return $conv->id;
            }
        }

        return $convId;
    }

    protected function makeMessage($conversationId, $message)
    {
        $message = $this->message->create([
            'message' => $message,
            'conversation_id' => $conversationId,
            'user_id' => $this->authUserId,
            'is_seen' => 0
        ]);
        return $message;
    }


    public function sendMessage($conversatonId, $message)
    {
        if ($conversatonId && $message) {
            if ($this->conversation->existsById($conversatonId)) {
                $message = $this->makeMessage($conversatonId, $message);
                return $message;
            }
        }

        return false;
    }

    public function sendMessageByUserId($receiverId, $message)
    {
        if ($conversationId = $this->isConversationExists($receiverId)) {
            $message = $this->makeMessage($conversationId, $message);
            return $message;
        }

        $convId = $this->newConversation($this->authUserId, $receiverId);
        $message = $this->makeMessage($convId, $message);
        return $message;
    }

    public function getInbox($user, $offset = 0, $take = 20)
    {
        return $this->conversation->getList($user, $offset, $take);
    }

    public function getConversationsById($convId)
    {
        $allConversations = $this->message->getMessageByConversationId($convId);

        return $allConversations;
    }


    public function getConversationsByUserId($senderId)
    {
        $conversationId = $this->isConversationExists($senderId, $this->authUserId);
        if ($conversationId) {
            return $this->getConversationsById($conversationId);
        }

        return false;
    }

    public function makeSeen($messageId)
    {
        $seen = $this->message->update($messageId, ['is_seen' => 1]);
        if ($seen) {
            return true;
        }

        return false;
    }

    public function deleteMessage($messageId)
    {
        $message = $this->message->find($messageId);

        if ($message->user_id == $this->authUserId) {
            $message->deleted_from_sender = 1;
        } else {
            $message->deleted_from_receiver = 1;
        }
        $deleteMessage = $this->message->update($message);
        $msg = Message::find($msgId);

        if ($deleteMessage) {
            return true;
        }

        return false;
    }


    public function deleteForever($messageId)
    {
        $deleteMessage = $this->message->delete($messageId);
        if ($deleteMessage) {
            return true;
        }

        return false;
    }

    public function deleteConversations($id)
    {
        $deleteConversation = $this->conversation->delete($id);
        if ($deleteConversation) {
            return true;
        }

        return false;
    }

    protected function getSerializeUser($user1, $user2)
    {
        $user = [];
        $user['one'] = ($user1 < $user2) ? $user1 : $user2;
        $user['two'] = ($user1 < $user2) ? $user2 : $user1;
        return $user;
    }
}
