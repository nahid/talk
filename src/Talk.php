<?php
/**
 * Class Talk.
 *
 * @author Nahid Bin Azhar
 *
 * @version 2.0.0
 *
 * @license https://creativecommons.org/licenses/by/4.0/ (CC BY 4.0)
 */

namespace Nahid\Talk;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;
use Nahid\Talk\Conversations\Conversation;
use Nahid\Talk\Conversations\ConversationRepository;
use Nahid\Talk\Live\Broadcast;
use Nahid\Talk\Messages\MessageRepository;
use Nahid\Talk\Tags\Tag;

class Talk
{

	/**
	 * With this constant, conversations can be marked as being specially important by "star-ing" them.
	 * This is made possible because we can add tags to conversations. This constant is the name of a
	 * special tag that is used to label starred conversations.
	 * By the way, special tags, by definition and implementation, can only have one copy (i.e. by namr) in the system,
	 * and they do not have creator/owner, i.e. their user_id is null.
	 * This is useful because it ensures that special tags are not returned when getting the collection of tags that belongs to a user.
	 *
	 * @var string
	 */
	const STAR_TAG = "talk_special_tag_star";

	/**
	 * Just like STAR_TAG, this constant is also courtesy of the tag feature of Talk.
	 *
	 * @var string
	 */
	const NOTIFICATION_TAG = "talk_special_tag_notification";

	/**
	 * configurations instance.
	 *
	 * @var \Illuminate\Contracts\Config\Repository
	 */
	protected $config;

	/**
	 * The ConversationRepository class instance.
	 *
	 * @var \Nahid\Talk\Conversations\ConversationRepository
	 */
	protected $conversation;

	/**
	 * The MessageRepository class instance.
	 *
	 * @var \Nahid\Talk\Messages\MessageRepository
	 */
	protected $message;

	/**
	 * Broadcast class instance.
	 *
	 * @var \Nahid\Talk\Live\Broadcast
	 */
	protected $broadcast;

	/**
	 * just a helper for the last 5 messages in your mailbox
	 */
	protected $latestMessages = null;

	/**
	 * Currently loggedin user id.
	 *
	 * @var int
	 */
	protected $authUserId;

	/**
	 * Initialize and instantiate conversation and message repositories.
	 *
	 * @param \Nahid\Talk\Conversations\ConversationRepository $conversation
	 * @param \Nahid\Talk\Messages\MessageRepository           $message
	 */
	public function __construct(Repository $config, Broadcast $broadcast, ConversationRepository $conversation, MessageRepository $message)
	{
		$this->config       = $config;
		$this->conversation = $conversation;
		$this->message      = $message;
		$this->broadcast    = $broadcast;
	}

	/**
	 * make two users as serialize with ascending order.
	 * It returns an array whose elements are $user1 and $user2
	 *
	 * @param int $user1
	 * @param int $user2 This can be null in case of sending system notification
	 *
	 * @return array
	 */
	protected function getSerializeUser($user1_id, $user2_id = null)
	{
		$users = [
			'one' => (($user1_id && $user1_id < $user2_id) ? $user1_id : $user2_id),
			'two' => (($user1_id && $user1_id < $user2_id) ? $user2_id : $user1_id),
		];

		return $users;
	}

	/**
	 * create a new message by using conversationId.
	 *
	 * @param int    $conversationId
	 * @param string $message
	 *
	 * @return \Nahid\Talk\Messages\Message
	 */
	protected function makeMessage($conversationId, $message, $includeSenderInMessageBroadcast = null)
	{
		$includeSenderInMessageBroadcast = is_null($includeSenderInMessageBroadcast) ? true : $includeSenderInMessageBroadcast;

		$message = $this->message->create([
			'message'         => $message,
			'conversation_id' => $conversationId,
			'user_id'         => $this->authUserId,
			'is_seen'         => 0,
		]);

		$message->conversation->touch();
		$this->broadcast->transmission($message, $includeSenderInMessageBroadcast);

		return $message;
	}

	/**
	 * Make new message collections to response with formatted data. Note that this method also marks
	 * all returned messages as "read"
	 *
	 *@param \Talk\Conversations\Conversation $conversations
	 *@return object|bool
	 */
	protected function makeMessageCollection($conversations)
	{
		if (!$conversations) {
			return false;
		}

		$collection = (object) null;
		if ($conversations->user_one == $this->authUserId || $conversations->user_two == $this->authUserId) {
			$withUser             = ($conversations->userone->id === $this->authUserId) ? $conversations->usertwo : $conversations->userone;
			$collection->withUser = $withUser;
			$collection->messages = $conversations->messages;

			//mark them as read
			foreach ($collection->messages as $message) {
				if ($message->sender->id != $this->authUserId) {
					if (!Talk::user($this->authUserId)->markRead($message->id)) {
						return false;
					}
				}
			}

			return $collection;
		}

		return false;
	}

	/**
	 * make new conversation with the given receiverId with currently loggedin user.
	 *
	 * @param int $receiverId
	 * @param string $title
	 * @param mixed $tagName
	 *
	 * @return int
	 */
	protected function newConversation($receiverId, $title, $tagName = null)
	{
		// $conversationId = $this->isConversationExists($receiverId);
		$users = $this->getSerializeUser($this->authUserId, $receiverId);

		// if ($conversationId === false) {
		$conversation = $this->conversation->create([
			'user_one' => $users['one'],
			'user_two' => $users['two'],
			'title'    => $title,
			'status'   => 1,
		]);

		if ($conversation) {
			if (!empty($tagName)) {
				$tag = Tags\Tag::firstOrCreate(['user_id' => $this->authUserId, 'name' => $tagName])->first();

				$conversation->addTag($tag);
			}

			return $conversation->id;
		}
		// }

		return $conversation->id;
	}

	/**
	 * set currently authenticated user id for global usage.
	 *
	 * @param int $id
	 *
	 * @return int|bool
	 */
	public function setAuthUserId($id = null)
	{
		if (!is_null($id)) {
			return $this->authUserId = $id;
		}

		return false;
	}

	/**
	 * its set user id instantly when you fetch or access data. if you you haven't
	 * set authenticated user id globally or you want to fetch work with
	 * instant users information, you may use it
	 *
	 * @param   int $id
	 * @return  \Nahid\Talk\Talk|bool
	 */
	public function user($id = null)
	{
		if ($this->setAuthUserId($id)) {
			return $this;
		}

		return false;
	}

	/**
	 * make sure is this conversation exist for this user with currently loggedin user.
	 *
	 * @param int $userId
	 *
	 * @return bool|int
	 */
	public function isConversationExists($userId)
	{
		if (empty($userId)) {
			return false;
		}

		$users = $this->getSerializeUser($this->authUserId, $userId);

		return $this->conversation->isExistsAmongTwoUsers($users['one'], $users['two']);
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
	 * send a message by using conversationId.
	 *
	 * @param int    $conversationId
	 * @param string $message
	 *
	 * @return \Nahid\Talk\Messages\Message|bool
	 */
	public function sendMessage($conversationId, $message)
	{
		if ($conversationId && $message) {
			if ($this->conversation->existsById($conversationId)) {
				$message = $this->makeMessage($conversationId, $message);

				return $message;
			}
		}

		return false;
	}

	/**
	 * create a new message by using receiverId.
	 *
	 * @param int    $receiverId
	 * @param string $message
	 *
	 * @return \Nahid\Talk\Messages\Message
	 */
	public function sendMessageByUserId($receiverId, $message, $title = null, $tagName = null, bool $includeSenderInMessageBroadcast = null)
	{
		$includeSenderInMessageBroadcast = is_null($includeSenderInMessageBroadcast) ? true : $includeSenderInMessageBroadcast;

		if ($conversationId = $this->isConversationExists($receiverId)) {
			$con = \Nahid\Talk\Conversations\Conversation::find($conversationId);
			if ($con->title == $title) {
				$message = $this->makeMessage($conversationId, $message);
				return $message;
			}
		}

		$conversationId = $this->newConversation($receiverId, $title, $tagName);
		$message        = $this->makeMessage($conversationId, $message, $includeSenderInMessageBroadcast);

		return $message;
	}

	/**
	 * Undocumented function
	 *
	 * @param int $receiverId
	 * @param string $message
	 * @param string $title
	 * @param string $customNotificationTagName
	 *
	 * @return \Nahid\Talk\Messages\Message
	 */
	public function sendNotificationToUser($receiverId, $message, $title = null, $customNotificationTagName = null)
	{
		if (is_null($this->authUserId)) {
			throw new \Exception("Authenticated user not found");
		}

		$customNotificationTagName = empty($customNotificationTagName) ? self::NOTIFICATION_TAG : $customNotificationTagName;

		return $this->sendMessageByUserId($receiverId, $message, $title, $customNotificationTagName, false);
	}

	/**
	 * fetch all inbox (i.e. conversations) for currently loggedin user with pagination.
	 *
	 * @param int $offset
	 * @param int $take
	 *
	 * @return array
	 */
	public function getInbox($order = 'desc', $offset = 0, $take = 20)
	{
		return $this->conversation->threads($this->authUserId, $order, $offset, $take);
	}

	/**
	 * fetch all inbox with soft deleted message for currently loggedin user with pagination.
	 *
	 * @param int $offset
	 * @param int $take
	 *
	 * @return array
	 */
	public function getInboxAll($order = 'desc', $offset = 0, $take = 20)
	{
		return $this->conversation->threadsAll($this->authUserId, $order, $offset, $take);
	}

	/**
	 * its a alias of getInbox method.
	 *
	 * @param int $offset
	 * @param int $take
	 *
	 * @return array
	 */
	public function threads($order = 'desc', $offset = 0, $take = 20)
	{
		return $this->getInbox($order, $offset, $take);
	}

	/**
	 * its a alias of getInboxAll method.
	 *
	 * @param int $offset
	 * @param int $take
	 *
	 * @return array
	 */
	public function threadsAll($order = 'desc', $offset = 0, $take = 20)
	{
		return $this->getInboxAll($order, $offset, $take);
	}

	/**
	 * fetch all conversation by using conversation id. Note that this method also marks
	 * all the messages in the returned conversations as "read"
	 *
	 * @param int $conversationId
	 * @param int $offset         = 0
	 * @param int $take           = 20
	 *
	 * @return \Nahid\Talk\Messages\Message
	 */
	public function getConversationsById($conversationId, $offset = 0, $take = 20)
	{
		$conversations = $this->conversation->getMessagesById($conversationId, $this->authUserId, $offset, $take);

		return $this->makeMessageCollection($conversations);
	}

	/**
	 * Get conversations that have STAR_TAG
	 *
	 * @return Collection
	 */
	public function getStarredConversations()
	{
		$tag = Tag::where('name', self::STAR_TAG)->first();

		if (is_null($tag)) {
			return collect();
		}

		$conversations = $this->conversation->getMessagesByTagId($tag->id, $this->authUserId);

		return $this->makeMessageCollection($conversations);
	}

	/**
	 * fetch all conversations that match the given tag id
	 *
	 * @param int $tag_id
	 *
	 * @return collection
	 */
	public function getConversationsByTagId($tag_id)
	{
		if (empty($tag_id)) {
			return collect();
		}

		// $threads = $this->conversation->threads($this->authUserId, 'id', 6, 6);
		$conversations_ = $this->conversation->getMessagesByTagId($tag_id, $this->authUserId);

		$user_id = $this->authUserId;

		$conversations = collect($conversations_)->filter(function ($item) use ($tag_id) {
			return ($item->tags->pluck('id')->contains($tag_id));
		});

		$threads = [];

		foreach ($conversations as $conversation) {
			$collection                 = (object) null;
			$conversationWith           = ($conversation->userone->id == $user_id) ? $conversation->usertwo : $conversation->userone;
			$collection->thread         = $conversation->messages->first();
			$collection->conversation   = $conversation;
			$collection->messages       = $conversation->messages;
			$collection->unreadmessages =
			$conversation->messages()->where(function ($query) use ($user_id) {
				$query
					->where('user_id', '!=', $user_id)
					->where('is_read', '=', '0');
			})->get();
			$collection->withUser = $conversationWith;
			$threads[]            = $collection;
		}

		return collect($threads);
	}

	/**
	 * fetch all conversation with soft deleted messages by using conversation id. Note that this method also marks
	 * all the messages in the returned conversations as "read".
	 *
	 * @param int $conversationId
	 * @param int $offset         = 0
	 * @param int $take           = 20
	 *
	 * @return \Nahid\Talk\Messages\Message
	 */
	public function getConversationsAllById($conversationId, $offset = 0, $take = 20)
	{
		$conversations = $this->conversation->getMessagesAllById($conversationId, $offset, $take);

		return $this->makeMessageCollection($conversations);
	}

	/**
	 * create a new message by using sender id.
	 *
	 * @param int $senderId
	 * @param int $offset   = 0
	 * @param int $take     = 20
	 *
	 * @return \Nahid\Talk\Messages\Message|bool
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
	 * create a new message by using sender id.
	 *
	 * @param int $senderId
	 * @param int $offset   = 0
	 * @param int $take     = 20
	 *
	 * @return \Nahid\Talk\Messages\Message|bool
	 */
	public function getConversationsAllByUserId($senderId, $offset = 0, $take = 20)
	{
		$conversationId = $this->isConversationExists($senderId, $this->authUserId);
		if ($conversationId) {
			return $this->getConversationsAllById($conversationId, $offset, $take);
		}

		return false;
	}

	/**
	 * gets tags owned/created by this user
	 *
	 * @return collection
	 */
	public function getUserTags()
	{
		return Tags\Tag::where(['user_id' => $this->authUserId])
			->get();
	}

	/**
	 * creates tag for user
	 *
	 * @param string $tagName
	 *
	 * @return bool
	 */
	public function createTagForUser($tagName)
	{
		if (!empty($tagName)) {
			$tag = Tags\Tag::where(['user_id' => $this->authUserId, 'name' => $tagName])->first();
			if (is_null($tag)) {
				$tag = Tags\Tag::create(['user_id' => $this->authUserId, 'name' => $tagName]);
			}

			return !empty($tag);
		}

		return false;
	}

	/**
	 * adds a tag to an existing conversation. Creates the tag if it does not exist for the user
	 * This allows for several users to maintain same tag name conveniently without any conflicts/issues
	 *
	 * @param int $conversationId
	 * @param string $tagName
	 * @param bool $makeSpecialTag when set to true, ensures that only one tag with the specified name should be maintained, thus supporting use of custom "system tags" e.g. for notifications
	 *
	 * @return bool
	 */
	public function addTagToConversation($conversationId, string $tagName, bool $makeSpecialTag = null)
	{
		$makeSpecialTag = is_bool($makeSpecialTag) ? $makeSpecialTag : false;

		if (empty($tagName)) {
			return false;
		}

		$tag = Tags\Tag::where(['user_id' => $this->authUserId, 'name' => $tagName])->first();

		//treat star tag specially
		if ($tagName == \Nahid\Talk\Talk::STAR_TAG || $makeSpecialTag) {
			//at any time, we want to always have only one star tag, irrespective of who created it
			//Therefore, this will ensure that we have only one star tag in our db table
			$tag = Tags\Tag::where(['name' => $tagName])->first();
		}

		if (is_null($tag)) {
			//special tags do not have owners
			if ($tagName == \Nahid\Talk\Talk::STAR_TAG || $makeSpecialTag) {
				$tag = Tags\Tag::forceCreate([
					'name'           => $tagName,
					'is_special_tag' => 1,
				]);
			} else {
				$tag = Tags\Tag::create(['user_id' => $this->authUserId, 'name' => $tagName]);
			}
		}

		$conversation = Conversation::with('tags')->findOrFail($conversationId);
		if (!$conversation->tags->pluck('id')->contains($tag->id)) {
			$conversation->addTag($tag);
		}

		return true;
	}

	public function starThisConversation($conversationId)
	{
		return $this->addTagToConversation($conversationId, self::STAR_TAG);
	}

	/**
	 *removes tag from a conversation
	 *
	 * @param int $conversationId
	 * @param int $tagId
	 *
	 * @return bool
	 */
	public function removeTagFromConversation($conversationId, $tagId)
	{
		if (!empty($conversationId) && !empty($tagId)) {
			$conversation = \Nahid\Talk\Conversations\Conversation::with('tags')
				->where(function ($query) {
					//just to ensure user belongs to the conversation
					$query
						->where("user_one", $this->authUserId)
						->orWhere("user_two", $this->authUserId);
				})
				->findOrFail($conversationId);

			$conversation->tags()->detach($tagId);

			return true;
		}

		return false;
	}
	/**
	 * its an alias of getConversationById.
	 *
	 * @param int $conversationId
	 *
	 * @return \Nahid\Talk\Messages\Message|bool
	 */
	public function getMessages($conversationId, $offset = 0, $take = 20)
	{
		return $this->getConversationsById($conversationId, $offset, $take);
	}

	/**
	 * its an alias of getConversationAllById.
	 *
	 * @param int $conversationId
	 *
	 * @return \Nahid\Talk\Messages\Message|bool
	 */
	public function getMessagesAll($conversationId, $offset = 0, $take = 20)
	{
		return $this->getConversationsAllById($conversationId, $offset, $take);
	}

	/**
	 * its an alias by getConversationByUserId.
	 *
	 * @param int $senderId
	 *
	 * @return \Nahid\Talk\Messages\Message|bool
	 */
	public function getMessagesByUserId($userId, $offset = 0, $take = 20)
	{
		return $this->getConversationsByUserId($userId, $offset, $take);
	}

	/**
	 * its an alias by getConversationAllByUserId.
	 *
	 * @param int $senderId
	 *
	 * @return \Nahid\Talk\Messages\Message|bool
	 */
	public function getMessagesAllByUserId($userId, $offset = 0, $take = 20)
	{
		return $this->getConversationsAllByUserId($userId, $offset, $take);
	}

	/**
	 * read a single message by message id.
	 *
	 * @param int $messageId
	 *
	 * @return \Nahid\Talk\Messages\Message|bool
	 */
	public function readMessage($messageId = null)
	{
		if (!is_null($messageId)) {
			$message = $this->message->with(['sender', 'conversation'])->find($messageId);

			if ($message->conversation->user_one == $this->authUserId || $message->conversation->user_two == $this->authUserId) {
				return $message;
			}
		}

		return false;
	}

	/**
	 * marks a message as seen.
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
	 * marks a message as read.
	 *
	 * @param int $messageId
	 *
	 * @return bool
	 */
	public function markRead($messageId)
	{
		if (!is_null($messageId)) {
			$message = $this->message->with(['sender', 'conversation'])->find($messageId);
			if ($message->conversation->user_one == $this->authUserId || $message->conversation->user_two == $this->authUserId) {

				//only needful to mark as read if you are the recipient
				if ($message->sender->id != $this->authUserId) {
					$read = $this->message->update($messageId, ['is_read' => 1]);
					if (!$read) {
						return false;
					}
				}

				return true;
			}
		}

		return false;
	}

	/**
	 * gets messages not yet read in a particular conversation
	 *
	 * @param int $conversationId
	 *
	 * @return mixed
	 */
	public function getUnreadMessagesInConversation($conversationId)
	{
		if (!is_null($conversationId)) {
			$message = $this->conversation->with(['messages'])->find($conversationId);
			if ($message->conversation->user_one == $this->authUserId || $message->conversation->user_two == $this->authUserId) {

				$unread = [];
				$unread = collect($this->conversation->messages)->filter(function ($message) {
					return (($message->sender->id != $this->authUserId) && ($message->is_read != 1));
				});

				return $unread;
			}
		}

		return false;
	}

	/**
	 * gets all messages not yet read in all conversations altogether
	 *
	 *
	 * @param int $removeSpecialMessages When true will remove conversations that have special tags.
	 * Special tags is one of those features of Talk that makes it easy to mimic sending of system notifications
	 * As a rule of thumb, any conversation that is not "normal" message/conversation should simply be tagged as special, so
	 * that it is easy to get unread messages without confusing message contexts
	 *
	 * @return collection
	 */
	public function getAllUnreadMessages($removeSpecialMessages = false)
	{
		$messages      = collect();
		$user_id       = $this->authUserId;
		$conversation  = new \Nahid\Talk\Conversations\Conversation();
		$conversations = $conversation->with(
			[
				'messages' => function ($query) use ($user_id) {
					$query
						->where('user_id', '!=', $user_id)
						->where('is_read', '=', '0');
				},

				'tags'     => function ($query) use ($removeSpecialMessages) {
					$query->when($removeSpecialMessages, function ($query) {
						$query->where(function ($query) {
							$query->whereNull('is_special_tag')
								->orWhere('is_special_tag', '!=', '1');
						});
					});
				},
			])
			->where(function ($query) use ($user_id) {
				$query->where('user_one', $user_id)
					->orWhere('user_two', $user_id);
			})
			->get();

		$messages = $conversations->pluck('messages')->flatten();

		return $messages;
	}

	/**
	 * gets all latest messages sent to the authenticated user
	 *
	 * @param int $conversationId
	 *
	 * @return collection
	 */
	public function getLatestMessages()
	{
		if ($this->latestMessages == null) {

			$messages     = collect();
			$user_id      = $this->authUserId;
			$conversation = new \Nahid\Talk\Conversations\Conversation();
			$msgThread    = $conversation->with(['messages' => function ($query) use ($user_id) {
				$query->where('user_id', '!=', $user_id)->with(['conversation']);
			}])
				->where('user_one', $user_id)
				->orWhere('user_two', $user_id)
				->orderBy('created_at')
				->take(5)
				->get();

			foreach ($msgThread as $thread) {
				$messages = collect($messages)->merge($thread->messages);
			}

			$this->latestMessages = $messages;
		}

		return $this->latestMessages;
	}

	/**
	 * gets the count of all messages not yet read in all conversations altogether
	 *
	 * @param int $removeSpecialMessages whether to remove special tag messages: This allows for Nahid to be useful for sending custom system notification messages to users
	 *
	 * @return int
	 */
	public function getUnreadMessagesCount($removeSpecialMessages = false)
	{
		return $this->getAllUnreadMessages($removeSpecialMessages)->count();
	}

	/**
	 * get receiver information for this conversation.
	 *
	 * @param int $conversationId
	 *
	 * @return UserModel
	 *
	 * @deprecated since version 2.0.0 Remove it from version 2.0.2
	 */
	public function getReceiverInfo($conversationId)
	{
		$conversation = $this->conversation->find($conversationId);
		$receiver     = '';
		if ($conversation->user_one == $this->authUserId) {
			$receiver = $conversation->user_two;
		} else {
			$receiver = $conversation->user_one;
		}

		$userModel = $this->config('talk.user.model');
		$user      = new $userModel();

		return $user->find($receiver);
	}

	/**
	 * delete a specific message, it is a softDelete process. All message stored in db.
	 *
	 * @param int $messageId
	 *
	 * @return bool
	 */
	public function deleteMessage($messageId)
	{
		return $this->message->softDeleteMessage($messageId, $this->authUserId);
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
	 * its an alias of deleteConversations.
	 *
	 * @param int $id
	 *
	 * @return bool
	 */
	public function deleteThread($id = null)
	{
		return $this->deleteConversations($id);
	}
}
