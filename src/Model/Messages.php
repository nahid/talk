<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class Messages extends Model {

	protected $table='messages';
    
    public $timestamps=true;


	public function conversation()
	{
	 	return $this->belongsTo('App\Conversations');
	}

	public function getConversations($conversationId)
	{
		$readMessage=DB::select(DB::raw(
			"SELECT U.name, M.id, U.id AS user_id, M.message, M.created_at
			FROM ".DB::getTablePrefix()."users U, ".DB::getTablePrefix()."messages M
			WHERE M.user_id = U.id
	            AND M.conversation_id = {$conversationId}
	            AND ((M.user_id=".Auth::user()->id." AND M.deleted_from_sender=0) OR (M.user_id!=".Auth::user()->id." AND M.deleted_from_reciever=0))
			ORDER BY M.created_at asc"
		));
		return $readMessage;
	}
	
	
	

}
