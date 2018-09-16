<?php

namespace Nahid\Talk\Conversations;

use SebastianBerc\Repositories\Repository;

class ConversationRepository extends Repository
{
    /*
     * this method is default method for repository package
     *
     * @return  \Nahid\Talk\Conersations\Conversation
     * */
    public function takeModel()
    {
        return Conversation::class;
    }

    /*
     * check this given user is exists
     *
     * @param   int $id
     * @return  bool
     * */
    public function existsById($id)
    {
        $conversation = $this->find($id);
        if ($conversation) {
            return true;
        }

        return false;
    }

    /*
     * check this given two users is already make a conversation
     *
     * @param   int $user1
     * @param   int $user2
     * @return  int|bool
     * */
    public function isExistsAmongTwoUsers($user1, $user2)
    {
        $conversation = Conversation::where('user_one', $user1)
            ->where('user_two', $user2);

        if ($conversation->exists()) {
            return $conversation->first()->id;
        }

        return false;
    }

    /*
     * check this given user is involved with this given $conversation
     *
     * @param   int $conversationId
     * @param   int $userId
     * @return  bool
     * */
    public function isUserExists($conversationId, $userId)
    {
        $exists = Conversation::where('id', $conversationId)
            ->where(function ($query) use ($userId) {
                $query->where('user_one', $userId)->orWhere('user_two', $userId);
            })
            ->exists();

        return $exists;
    }

    /*
     * retrieve all message thread without soft deleted message with latest one message and
     * sender and receiver user model
     *
     * @param   int $user_id
     * @param   int $offset
     * @param   int $take
     * @return  collection
     * */
    public function threads($user_id, $order, $offset, $take)
    {
        $conv           = new Conversation();
        $conv->authUser = $user_id;
        $conversations  = $conv->with(['messages' => function ($q) use ($user_id) {
            return $q->where(function ($q) use ($user_id) {
                $q->where('user_id', $user_id)
                    ->where('deleted_from_sender', 0);
            })
                ->orWhere(function ($q) use ($user_id) {
                    $q->where('user_id', '!=', $user_id);
                    $q->where('deleted_from_receiver', 0);
                })
                ->latest();
        }, 'messages.sender', 'userone', 'usertwo'])
            ->where('user_one', $user_id)
            ->orWhere('user_two', $user_id)
            ->offset($offset)
            ->take($take)
            ->orderBy('updated_at', $order)
            ->get();

        $threads = [];

        foreach ($conversations as $conversation) {
            $collection                 = (object) null;
            $conversationWith           = ($conversation->userone->id == $user_id) ? $conversation->usertwo : $conversation->userone;
            $collection->thread         = $conversation->messages->first();
            $collection->conversation   = $conversation;
            $collection->messages       = $conversation->messages;
            $collection->unreadmessages = $conversation->messages()->where(function ($query) use ($user_id) {
                return $query
                    ->where('user_id', '!=', $user_id)
                    ->where('is_read', '=', '0');
            })->get();
            // dump($conversation->id);
            // dump($collection->unreadmessages);
            $collection->withUser = $conversationWith;
            $threads[]            = $collection;
        }

        return collect($threads);
    }

    /*
     * retrieve all message thread with latest one message and sender and receiver user model
     *
     * @param   int $user_id
     * @param   int $offset
     * @param   int $take
     * @return  collection
     * */
    public function threadsAll($user_id, $offset, $take)
    {
        $msgThread = Conversation::with(['messages' => function ($q) use ($user_id) {
            return $q->latest();
        }, 'userone', 'usertwo'])
            ->where('user_one', $user_id)->orWhere('user_two', $user_id)->offset($offset)->take($take)->get();

        $threads = [];

        foreach ($msgThread as $thread) {
            $conversationWith = ($thread->userone->id == $user_id) ? $thread->usertwo : $thread->userone;
            $message          = $thread->messages->first();
            $message->user    = $conversationWith;
            $threads[]        = $message;
        }

        return collect($threads);
    }

    /*
     * get all conversations by given conversation id
     *
     * @param   int $conversationId
     * @param   int $userId
     * @param   int $offset
     * @param   int $take
     * @return  collection
     * */
    public function getMessagesById($conversationId, $userId, $offset, $take)
    {
        return Conversation::with(['messages' => function ($query) use ($userId, $offset, $take) {
            $query->where(function ($qr) use ($userId) {
                $qr->where('user_id', '=', $userId)
                    ->where('deleted_from_sender', 0);
            })
                ->orWhere(function ($q) use ($userId) {
                    $q->where('user_id', '!=', $userId)
                        ->where('deleted_from_receiver', 0);
                });

            $query->offset($offset)->take($take);

        }])->with(['userone', 'usertwo'])->find($conversationId);
    }

    /*
     * get all conversations by given tag id
     *
     * @param   int $conversationId
     * @param   int $userId
     * @return  collection
     * * /
    public function getMessagesByTagId($conversationId, $userId)
    {
    return Conversation::with(['messages' => function ($query) use ($userId) {
    $query->where(function ($qr) use ($userId) {
    $qr->where('user_id', '=', $userId)
    ->where('deleted_from_sender', 0);
    })
    ->orWhere(function ($q) use ($userId) {
    $q->where('user_id', '!=', $userId)
    ->where('deleted_from_receiver', 0);
    });

    $query->offset($offset)->take($take);

    }])->with(['userone', 'usertwo', 'tags'])->find($conversationId);
    }
     */

    /*
     * get all conversations by given tag id
     *
     * @param   int $conversationId
     * @param   int $userId
     * @return  collection
     * */
    public function getMessagesByTagId($tagId, $userId)
    {
        return Conversation::with(['messages' => function ($query) use ($userId) {
            $query->where(function ($qr) use ($userId) {
                $qr->where('user_id', '=', $userId)
                    ->where('deleted_from_sender', 0);
            })
                ->orWhere(function ($q) use ($userId) {
                    $q->where('user_id', '!=', $userId)
                        ->where('deleted_from_receiver', 0);
                });
        }])->with(['userone', 'usertwo', 'tags'])->get();
    }

    /*
     * get all conversations with soft deleted message by given conversation id
     *
     * @param   int $conversationId
     * @param   int $offset
     * @param   int $take
     * @return  collection
     * */
    public function getMessagesAllById($conversationId, $offset, $take)
    {
        return $this->with(['messages' => function ($q) use ($offset, $take) {
            return $q->offset($offset)
                ->take($take);
        }, 'userone', 'usertwo'])->find($conversationId);
    }
}
