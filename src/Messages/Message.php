<?php

namespace Nahid\Talk\Messages;

use Embera\Embera;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Nahid\Talk\Html\HtmlString;
use Nahid\Talk\Html\HtmlStringInterface;
use \Carbon\Carbon;

class Message extends Model implements HtmlStringInterface
{
	protected $table = 'messages';

	public $timestamps = true;

	protected $appends = ['humans_time'];

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
	public function getHumansTimeAttribute()
	{
		//laravel sometimes has $this=null but attributes proprty works perfectly well
		$date = Carbon::parse($this->attributes['created_at']);
		$now  = $date->now();

		return $date->diffForHumans($now, true);
	}

	/*
	 * make dynamic attribute for human readable time - with more naturalisic time modifiers
	 *
	 * @return string
	 * */
	public function getNaturalHumansTimeAttribute()
	{
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
	}

	/*
	 * make a relation between conversation model
	 *
	 * @return collection
	 * */
	public function conversation()
	{
		return $this->belongsTo('Nahid\Talk\Conversations\Conversation');
	}

	/*
	 * make a relation between user model
	 *
	 * @return collection
	 * */
	public function user()
	{
		return $this->belongsTo(
			config('talk.user.model', 'App\User'),
			config('talk.user.foreignKey'),
			config('talk.user.ownerKey')
		);
	}

	/*
	 * its an alias of user relation
	 *
	 * @return collection
	 * */
	public function sender()
	{
		return $this->user();
	}

	/**
	 * @return Htmlable
	 */
	public function toHtmlString()
	{
		$embera = new Embera(['http' => ['curl' => [CURLOPT_SSL_VERIFYPEER => false]]]);

		return new HtmlString($this->message, $embera);
	}
}
