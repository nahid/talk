<?php

namespace Nahid\Talk\Conversations;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
	protected $table   = 'conversations';
	public $timestamps = true;
	public $fillable   = [
		'user_one',
		'user_two',
		'title',
		'status',
	];

	/*
	 * make a relation with tags
	 *
	 * return relationship
	 * */
	public function tags()
	{
		return $this->belongsToMany('Nahid\Talk\Tags\Tag');
	}

	/*
	 * make a relation between message
	 *
	 * return collection
	 * */
	public function messages()
	{
		return $this->hasMany('Nahid\Talk\Messages\Message', 'conversation_id')
			->with('sender');
	}

	/*
	 * make a relation between first user from conversation
	 *
	 * return collection
	 * */
	public function userone()
	{
		return $this->belongsTo(config('talk.user.model', 'App\User'), 'user_one', config('talk.user.ownerKey'));
	}

	/*
	 * make a relation between second user from conversation
	 *
	 * return collection
	 * */
	public function usertwo()
	{
		return $this->belongsTo(config('talk.user.model', 'App\User'), 'user_two', config('talk.user.ownerKey'));
	}

	/*
	 * adds a tag to this conversation
	 *
	 * return void
	 * */
	public function addTag(\Nahid\Talk\Tags\Tag $tag)
	{
		return $this->tags()->attach($tag->id);
	}
}
