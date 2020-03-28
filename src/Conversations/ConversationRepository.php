<?php

namespace Nahid\Talk\Conversations;

use SebastianBerc\Repositories\Repository;

class ConversationRepository extends Repository
{
	/**
	 * this method is default method for repository package
	 *
	 * @return  \Nahid\Talk\Conversations\Conversation
	 */
	public function takeModel()
	{
		return Conversation::class;
	}

	/**
	 * check this given user is exists
	 *
	 * @param   int $id
	 * @return  bool
	 */
	public function existsById($id)
	{
		$conversation = $this->find($id);
		if ($conversation) {
			return true;
		}

		return false;
	}

	/**
	 * check this given two users is already make a conversation
	 *
	 * @param   int $user1
	 * @param   int $user2
	 * @return  int|bool
	 */
	public function isExistsAmongTwoUsers($user1, $user2)
	{
		$conversation = Conversation::where(
			function ($query) use ($user1, $user2) {
				$query->where(
					function ($q) use ($user1, $user2) {
						$q->where('user_one', $user1)
							->where('user_two', $user2);
					}
				)
					->orWhere(
						function ($q) use ($user1, $user2) {
							$q->where('user_one', $user2)
								->where('user_two', $user1);
						}
					);
			}
		);

		if ($conversation->exists()) {
			return $conversation->first()->id;
		}

		return false;
	}

	/**
	 * check this given user is involved with this given $conversation
	 *
	 * @param   int $conversationId
	 * @param   int $userId
	 * @return  bool
	 */
	public function isUserExists($conversationId, $userId)
	{
		$exists = Conversation::where('id', $conversationId)
			->where(function ($query) use ($userId) {
				$query->where('user_one', $userId)->orWhere('user_two', $userId);
			})
			->exists();

		return $exists;
	}

	/**
	 * retrieve all message threads (i.e. conversations) without soft deleted message, including latest messages,
	 * sender and receiver user model. This method differs from the threadsAll() method in that each conversation returned
	 * contains a lot more data (each conversation contains the conversation model itself, the messages in the conversation, a
	 * collection of only unread messages in the conversation, the corresponding user, etc.)
	 *
	 * @param   int $user
	 * @param   int $offset
	 * @param   int $take
	 * @return  collection
	 */
	public function threads($user_id, $order, $offset, $take)
	{
		$conversation           = new Conversation();
		$conversation->authUser = $user_id;

		$conversations = $conversation->with(['messages' => function ($q) use ($user_id) {
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
			$conversationWith = ($conversation->userone->id == $user_id) ? $conversation->usertwo : $conversation->userone;

			$collection                 = (object) null;
			$collection->thread         = $conversation->messages->first();
			$collection->conversation   = $conversation;
			$collection->messages       = $conversation->messages;
			$collection->unreadmessages = $conversation->messages()->where(function ($query) use ($user_id) {
				return $query
					->where('user_id', '!=', $user_id)
					->where('is_read', '=', '0');
			})->get();

			$collection->withUser = $conversationWith;

			$threads[] = $collection;
		}

		return collect($threads);
	}

	/**
	 * retrieve all message threads (i.e. conversations) with the latest message and the corresponding user model.
	 * This method defers from the threads() method in that it is more light-weight and thus more efficient, because it
	 * only includes just a handful of information for each conversation (each conversation only contains the
	 * first message in the conversation and the corresponding user).
	 *
	 * @param   int $user_id
	 * @param   int $offset
	 * @param   int $take
	 *
	 * @return  collection
	 */
	public function threadsAll($user_id, $offset, $take)
	{
		$conversations = Conversation::with(['messages' => function ($q) use ($user_id) {
			return $q->latest();
		}, 'userone', 'usertwo'])
			->where('user_one', $user_id)->orWhere('user_two', $user_id)->offset($offset)->take($take)->get();

		$threads = [];

		foreach ($conversations as $conversation) {
			$conversationWith = ($conversation->userone->id == $user_id) ? $conversation->usertwo : $conversation->userone;

			$message       = $conversation->messages->first();
			$message->user = $conversationWith;
			$threads[]     = $message;
		}

		return collect($threads);
	}

	/**
	 * get all conversations by given conversation id
	 *
	 * @param   int $conversationId
	 * @param   int $userId
	 * @param   int $offset
	 * @param   int $take
	 * @return  collection
	 */
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

	/**
	 * get all conversations by given tag id
	 *
	 * @param   int $conversationId
	 * @param   int $userId
	 * @return  collection
	 */
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
		},
			'userone',
			'usertwo',
			'tags'                                => function ($query) use ($tagId) {
				$query->where('id', $tagId);
			},
		])
			->get();
	}

	/**
	 * get all conversations with soft deleted message by given conversation id
	 *
	 * @param   int $conversationId
	 * @param   int $offset
	 * @param   int $take
	 * @return  collection
	 */
	public function getMessagesAllById($conversationId, $offset, $take)
	{
		return $this->with(['messages' => function ($q) use ($offset, $take) {
			return $q->offset($offset)
				->take($take);
		}, 'userone', 'usertwo'])->find($conversationId);
	}
}
