<?php

namespace Nahid\Talk;

use Nahid\Talk\Conversations\ConversationRepository;
use Nahid\Talk\Messages\MessageRepository;

/**
 * Class Talk.
 *
 * @author Nahid Bin Azhar
 * @var package
 */
class Talk
{
    protected $conversation;
    protected $message;
    protected $authUserId;

    /**
     * Initialize and instantiate conversation and message repository.
     *
     * @param \Nahid\Talk\Conversations\ConversationRepository $conversation
     * @param \Nahid\Talk\Conversations\MessageRepository      $message
     */
    public function __construct(ConversationRepository $conversation, MessageRepository $message)
    {
        $this->conversation = $conversation;
        $this->message = $message;
    }

    /**
     * set currently authenticated user id for global usage.
     *
     * @param int $id
     *
     * @return int/bool
     */
    public function setAuthUserId($id = null)
    {
        if (!is_null($id)) {
            return $this->authUserId = $id;
        }

        return false;
    }

    /**
     * make sure is this conversation exist for this user with currently loggedin user.
     *
     * @param int $userId
     *
     * @return bool/int
     */
    public function isConversationExists($userId)
    {
        if (empty($userId)) {
            return false;
        }

        $user = $this->getSerializeUser($this->authUserId, $userId);

        return $this->conversation->isExistsAmongTwoUsers($user['one'], $user['two']);
    }

    /**
     * check the given user exist for the given conversation.
     *
     * @param int $conversationId
     * @param int $userId
     *
     * @return bool
     */
    public function isAuthenticUser($conversationId, $userId)
    {
        if ($conversationId && $userId) {
            return $this->conversation->isUserExists($conversationId, $userId);
        }

        return false;
    }

    /**
     * make new conversation the given receiverId with currently loggedin user.
     *
     * @param int $receiverId
     *
     * @return int
     */
    protected function newConversation($receiverId)
    {
        $convId = $this->isConversationExists($receiverId);
        $user = $this->getSerializeUser($this->authUserId, $receiverId);

        if ($convId === false) {
            $conv = $this->conversation->create([
                'user_one' => $user['one'],
                'user_two' => $user['two'],
                'status' => 1,
            ]);

            if ($conv) {
                return $conv->id;
            }
        }

        return $convId;
    }

    /**
     * create a new message by using conversationId.
     *
     * @param int    $conversationId
     * @param string $message
     *
     * @return \Nahid\Talk\Messages\Message
     */
    protected function makeMessage($conversationId, $message)
    {
        $message = $this->message->create([
            'message' => $message,
            'conversation_id' => $conversationId,
            'user_id' => $this->authUserId,
            'is_seen' => 0,
        ]);

        return $message;
    }

    /**
     * send a message by using converstionid.
     *
     * @param int    $conversationId
     * @param string $message
     *
     * @return \Nahid\Talk\Messages\Message / bool
     */
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

    /**
     * create a new message by using receiverid.
     *
     * @param int    $receiverId
     * @param string $message
     *
     * @return \Nahid\Talk\Messages\Message
     */
    public function sendMessageByUserId($receiverId, $message)
    {
        if ($conversationId = $this->isConversationExists($receiverId)) {
            $message = $this->makeMessage($conversationId, $message);

            return $message;
        }

        $convId = $this->newConversation($receiverId);
        $message = $this->makeMessage($convId, $message);

        return $message;
    }

    /**
     * fetch all inbox for currently loggedin user with pagination.
     *
     * @param int $offset
     * @param int $take
     *
     * @return array
     */
    public function getInbox($offset = 0, $take = 20)
    {
        return $this->conversation->threads($this->authUserId, $offset, $take);
    }

    /**
     * fetch all inbox with soft deleted message for currently loggedin user with pagination.
     *
     * @param int $offset
     * @param int $take
     *
     * @return array
     */
    public function getInboxAll($offset = 0, $take = 20)
    {
        return $this->conversation->threadsAll($this->authUserId, $offset, $take);
    }

    /**
     * its a alias of getInbox method.
     *
     * @param int $offset
     * @param int $take
     *
     * @return array
     */
    public function threads($offset = 0, $take = 20)
    {
        return $this->getInbox($offset, $take);
    }

    /**
     * its a alias of getInboxAll method.
     *
     * @param int $offset
     * @param int $take
     *
     * @return array
     */
    public function threadsAll($offset = 0, $take = 20)
    {
        return $this->getInboxAll($offset, $take);
    }

    /**
     * fetch all conversation by using coversation id.
     *
     * @param int $convId
     *
     * @return \Nahid\Talk\Messages\Message
     */
    public function getConversationsById($conversationId, $offset = 0, $take = 20)
    {
        $conversations = $this->conversation->getMessagesById($conversationId, $offset, $take);
        $collection = (object) null;
        if ($conversations->user_one == $this->authUserId || $conversations->user_two == $this->authUserId) {
            $withUser = ($conversations->userone->id === $this->authUserId)?$conversations->usertwo:$conversations->userone;
            $collection->withUser = $withUser;
            $collection->messages = $conversations->messages;

            return $collection;
        }
        return false;
    }

    /**
     * create a new message by using sender id.
     *
     * @param int $senderId
     *
     * @return \Nahid\Talk\Messages\Message / bool
     */
    public function getConversationsByUserId($senderId, $offset = 0, $take = 20)
    {
        $conversationId = $this->isConversationExists($senderId, $this->authUserId);
        if ($conversationId) {
            return $this->getConversationsById($conversationId, $offset, $take);
        }

        return false;
    }

    /**
     * its an alias of getConversationById.
     *
     * @param int $conversationId
     *
     * @return \Nahid\Talk\Messages\Message / bool
     */
    public function getMessages($conversationId)
    {
        return $this->getConversationsById($conversationId);
    }

    /**
     * its an alias by getConversationByUserId.
     *
     * @param int $senderId
     *
     * @return \Nahid\Talk\Messages\Message / bool
     */
    public function getMessagesByUserId($userId)
    {
        return $this->getConversationsByUserId($userId);
    }

    /**
     * read a single message by message id.
     *
     * @param int $messageId
     *
     * @return Nahid\Talk\Messages\Message / bool
     */
    public function readMessage($messageId = null)
    {
        if (!is_null($messageId)) {
            $message = $this->message->with(['sender', 'conversation'])->find($messageId);

            if ($message->coversation->user_one == $this->authUserId || $message->coversation->user_two == $this->authUserId) {
                return $message;
            }
        }

        return false;
    }

    /**
     * make a message as seen.
     *
     * @param int $messageId
     *
     * @return bool
     */
    public function makeSeen($messageId)
    {
        $seen = $this->message->update($messageId, ['is_seen' => 1]);
        if ($seen) {
            return true;
        }

        return false;
    }

    /**
     * get receiver information for this conversation.
     *
     * @param int $conversationId
     *
     * @return UserModel
     */
    public function getReceiverInfo($conversationId)
    {
        $conversation = $this->conversation->find($conversationId);
        $receiver = '';
        if ($conversation->user_one == $this->authUserId) {
            $receiver = $conversation->user_two;
        } else {
            $receiver = $conversation->user_one;
        }

        $userModel = config('talk.user.model');
        $user = new $userModel();

        return $user->find($receiver);
    }

    /**
     * delete a specific message, its a softdelete process. All message stay in db.
     *
     * @param int $messageId
     *
     * @return bool
     */
    public function deleteMessage($messageId)
    {
        $message = $this->message->find($messageId);

        if ($message->user_id == $this->authUserId) {
            $message->deleted_from_sender = 1;
        } else {
            $message->deleted_from_receiver = 1;
        }

        $deleteMessage = $this->message->update($message);

        if ($deleteMessage) {
            return true;
        }

        return false;
    }

    /**
     * permanently delete message for this id.
     *
     * @param int $messageId
     *
     * @return bool
     */
    public function deleteForever($messageId)
    {
        $deleteMessage = $this->message->delete($messageId);
        if ($deleteMessage) {
            return true;
        }

        return false;
    }

    /**
     * delete message threat or conversation by conversation id.
     *
     * @param int $id
     *
     * @return bool
     */
    public function deleteConversations($id)
    {
        $deleteConversation = $this->conversation->delete($id);
        if ($deleteConversation) {
            return $this->message->deleteMessages($id);
        }

        return false;
    }

    /**
     * make two users as serialize with ascending order.
     *
     * @param int $user1
     * @param int $user2
     *
     * @return array
     */
    protected function getSerializeUser($user1, $user2)
    {
        $user = [];
        $user['one'] = ($user1 < $user2) ? $user1 : $user2;
        $user['two'] = ($user1 < $user2) ? $user2 : $user1;

        return $user;
    }
}
