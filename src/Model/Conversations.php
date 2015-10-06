<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
class Conversations extends Model {

	protected $table="conversations";
	public $timestamps=true;

	public function messages()
	{
		return $this->hasMany('App\Messages', 'conversation_id');
	}


	public function getConversationList($user){
		$conversations=DB::select(
		DB::raw("SELECT user.id as userid, user.name, conv.id as conv_id, msg.message
	FROM ".DB::getTablePrefix().config('talk.user_table')." user, ".DB::getTablePrefix()."conversations conv, ".DB::getTablePrefix()."messages msg
	WHERE conv.id = msg.conversation_id
				AND (
					conv.user_one ={$user}
					OR conv.user_two ={$user}
				) and (msg.created_at)
	in (
		SELECT max(msg.created_at) as created_at
		FROM ".DB::getTablePrefix()."conversations conv, ".DB::getTablePrefix()."messages msg
		WHERE CASE
			WHEN conv.user_one ={$user}
			THEN conv.user_two = user.id
			WHEN conv.user_two ={$user}
			THEN conv.user_one = user.id
		END
		AND conv.id = msg.conversation_id
		AND (
			conv.user_one ={$user}
			OR conv.user_two ={$user}
			)
	GROUP BY conv.id
)
	ORDER BY msg.created_at DESC")
		);


	return $conversations;
	}

	public function getUserAuthConversation($conversationId, $user=null){
		$user=!is_null($user)?$user:Auth::user()->id;

		$check=$this->where('id',$conversationId)
		->where(function($query) use ($user){
			$query->where('user_one', $user)->orWhere('user_two', $user);
		})->get()->count();

		if($check<1) {
			return false;
		}

		return true;
	}

	public function getReceiverInfoByConversationId($id){
		$data=$this->find($id);

		if($data->user_one==Auth::user()->id){
			return $data->user_two;
		}

		return $data->user_one;
	}

	public function getSenderInfoByConversationId($id){
        $data=$this->find($id);

        if($data->user_one!=Auth::user()->id){
            return $data->user_two;
        }

        return $data->user_one;
    }


}
