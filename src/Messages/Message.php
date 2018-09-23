<?php

namespace Nahid\Talk\Messages;

use Illuminate\Database\Eloquent\Model;

class Message extends Model {
	protected $table = 'messages';

	public $timestamps = true;

	public $fillable = [
		'message',
		'is_seen',
		'is_read',
		'deleted_from_sender',
		'deleted_from_receiver',
		'user_id',
		'conversation_id',
	];

	/*
	 * make dynamic attribute for human readable time
	 *
	 * @return string
	 * */
	public function getHumansTimeAttribute() {
		//laravel sometimes has $this=null but attributes proprty works perfectly well
		$date = \Carbon\Carbon::parse($this->attributes['created_at']);
		$now  = $date->now();

		if ($date->isToday()) {
			return $date->diffForHumans(null, false, true);
		} else {
			if ($date->isSameYear($now)) {
				return $date->format("M j");
			}
		}

		return $date->format("M j, Y");
		// return $date->diffForHumans(null, true, true) . ' ago';
	}

	/*
	 * make a relation between conversation model
	 *
	 * @return collection
	 * */
	public function conversation() {
		return $this->belongsTo('Nahid\Talk\Conversations\Conversation');
	}

	/*
	 * make a relation between user model
	 *
	 * @return collection
	 * */
	public function user() {
		return $this->belongsTo(config('talk.user.model', 'App\User'));
	}

	/*
	 * its an alias of user relation
	 *
	 * @return collection
	 * */
	public function sender() {
		return $this->user();
	}
}
