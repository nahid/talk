<?php namespace Nahid\Talk;
/*
*@Author:		Nahid Bin Azhar
*@Author URL:	http://nahid.co
*/
use Illuminate\Http\Request;
use Response;


use App\Conversations;
use App\Messages;
use Auth;
use DB;

class Talk
{


	public function checkConversationExists($user1, $user2)
	{
		$newUser1=$user1<$user2?$user1:$user2;  
		$newUser2=$user1<$user2?$user2:$user1; 
		$conv=Conversations::where('user_one', $newUser1)
				->where('user_two', $newUser2)
				->first();

		if(isset($conv->id)){
			return $conv->id;
		}

		return false;
	}


	public function isUserAuthConversation($convId, $userId=null)
	{
		$conv=new Conversations;
		return $conv->getUserAuthConversation($convId, $userId);
	}

	protected function makeConversation($userid)
	{
	   $user1=$userid<Auth::user()->id?$userid:Auth::user()->id;  
       $user2=$userid>Auth::user()->id?$userid:Auth::user()->id; 
       
       $convId=$this->checkConversationExists($user1, $user2);
       if($convId==false){
	       $conv=new Conversations;
	       
	       $conv->user_one=$user1;
	       $conv->user_two=$user2;
	       $conv->status=1;
	       
	       if($conv->save()){
	           return $conv->id;
	       }
   		}

   		return $convId;
         
	}

    
	public function sendMessageByConversationId($convId, $message) 
	{
			$conv=new Conversations;
			$msg = new Messages;

			$msg -> message = $message;
			$msg -> conversation_id = $convId;
			$msg -> user_id = Auth::user()->id;
			$msg -> is_seen = 0;

			

			if ($msg -> save()) {
				return true;			
			}

			return false;
		
	}


	public function sendMessageByUserId($userId, $message) 
	{
			$conv=new Conversations;
			$msg = new Messages;

			$convId=$this->makeConversation($userId);
 
			$msg -> message=$message;
			$msg -> conversation_id = $convId;
			$msg -> user_id = Auth::user()->id;
			$msg -> is_seen = 0;

			

			if ($msg -> save()) {
				return true;			
			}

			return false;
		
	}

	public function getInbox() 
	{
		$conv=new Conversations;
		
		return $conv->getConversationList(Auth::user()->id);
	}

	public function getAllConversations($convId) 
	{
		$conv=new Conversations;
		$message = new Messages;
		
		if($conv->getUserAuthConversation($convId)==false) {
			return Response::json(['msg'=>'error']);
		}
		
		if ($convId=='') return false;
		
		$getConversations = $message -> getConversations($convId);
        
		return $getConversations;;	
	}



	public function getAllConversationsByUserId($userId) 
	{
		$conversationId=$this->checkConversationExists($userId, Auth::user()->id);
		if($conversationId)
			return $this->getAllConversations($conversationId);

		return false;
	}

	public function makeSeen($msgId)
	{
		$msg=Messages::find($msgId);
		$msg->is_seen=1;
		if($msg->save()){
			return true;
		}

		return false;
	}

	public function deleteMessage($msgId)
	{
		$msg=Messages::find($msgId);
		if($this->isUserAuthConversation($msg->conversation->id)){
			if($msg->user_id==Auth::user()->id){
				$msg->deleted_from_sender=1;
			}else{
				$msg->deleted_from_reciever=1;

			}

			if($msg->save()){
				return true;
			}
		}

		return false;
	}


	public function deleteForever($msgId)
	{
		$msg=Messages::find($msgId);
			if($msg->delete()){
				return true;
			}
		

		return false;
	}

	public function deleteConversations($id)
	{
		$conv=Conversations::find($id);
		if($conv->messages()->delete()){
			if($conv->delete()){
				return true;
			}
		}

		return true;
	}


}