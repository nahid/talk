<?php

namespace Nahid\Talk\Tags;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
	protected $table   = 'tags';
	public $timestamps = true;
	public $fillable   = [
		'user_id', //the owner/creator of this tag. Very helpful when using with other packages like https://github.com/the-control-group/voyager
		'name',
	];

	/*
	 * make a relation with conversations
	 *
	 * returns collection
	 *
	 * */
	public function conversations()
	{
		return $this->hasMany('Nahid\Talk\Messages\Conversations', 'conversation_id')
			->with('sender');
	}
}
