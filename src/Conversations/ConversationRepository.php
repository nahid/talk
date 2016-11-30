<?php

namespace Nahid\Talk\Conversations;

use SebastianBerc\Repositories\Repository;

class ConversationRepository extends Repository
{
    public function takeModel()
    {
        return Conversation::class;
    }

    public function existsById($id)
    {
        $conversation = $this->find($id);
        if ($conversation) {
            return true;
        }

        return false;
    }

    public function isExistsAmongTwoUsers($user1, $user2)
    {
        $conversation = Conversation::where('user_one', $user1)
            ->where('user_two', $user2);

        if ($conversation->exists()) {
            return $conversation->first()->id;
        }

        return false;
    }

    public function isUserExists($conversationId, $userId)
    {
        $exists = Conversation::where('id', $conversationId)
            ->where(function ($query) use ($userId) {
                $query->where('user_one', $userId)->orWhere('user_two', $userId);
            })
            ->exists();

        return $exists;
    }

    public function threads($user, $offset, $take)
    {
        $conv = new Conversation();
        $conv->authUser = $user;
        $msgThread = $conv->with(['messages' => function ($q) use ($user) {
            return $q->where(function ($q) use ($user) {
                $q->where('user_id', $user)
                        ->where('deleted_from_sender', 0);
            })
                ->orWhere(function ($q) use ($user) {
                    $q->where('user_id', '!=', $user);
                    $q->where('deleted_from_receiver', 0);
                })
            ->latest();
        }, 'userone', 'usertwo'])
            ->where('user_one', $user)->orWhere('user_two', $user)->offset($offset)->take($take)->get();

        $threads = [];

        foreach ($msgThread as $thread) {
            $collection = (object) null;
            $conversationWith = ($thread->userone->id == $user) ? $thread->usertwo : $thread->userone;
            $collection->thread = $thread->messages->first();
            $collection->withUser  = $conversationWith;
            $threads[] = $collection;
        }

        return collect($threads);
    }

    public function threadsAll($user, $offset, $take)
    {
        $msgThread = Conversation::with(['messages' => function ($q) use ($user) {
            return $q->latest();
        }, 'userone', 'usertwo'])
            ->where('user_one', $user)->orWhere('user_two', $user)->offset($offset)->take($take)->get();

        $threads = [];

        foreach ($msgThread as $thread) {
            $conversationWith = ($thread->userone->id == $user) ? $thread->usertwo : $thread->userone;
            $message = $thread->messages->first();
            $message->user = $conversationWith;
            $threads[] = $message;
        }

        return collect($threads);
    }

    public function getMessagesById($conversationId, $offset, $take)
    {
        return $this->with(['messages' => function ($q) use ($offset, $take) {
            $q->offset($offset);
            $q->take($take);
        }, 'userone', 'usertwo'])->find($conversationId);


    }
}
