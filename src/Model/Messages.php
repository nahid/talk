<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Messages extends Model {

	protected $table='messages';
    
    public $timestamps=true;


	
	public function readMessageFromSender($conversationId){
		$readMessage=DB::select(DB::raw(
			"SELECT U.photo, U.first_name, U.last_name, M.id, U.id as user_id, M.body, M.subject, M.created_at
FROM rent_rentastico_users U, rent_messages M
WHERE M.user_id = U.id
AND M.conversation_id = {$conversationId}
order by M.created_at asc"
		));
		return $readMessage;
	}
	
	
	

}
