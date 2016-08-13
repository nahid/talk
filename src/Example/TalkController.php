<?php
namespace Nahid\Talk\Example;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

//use Auth;
use Illuminate\Http\Request;
use Nahid\Talk\Facades\Talk;
 
class TalkController extends BaseController
{
	use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

	function __construct()
	{
        $this->middleware(['auth']);
        Talk::setAuthUserId(auth()->user()->id);

        view()->composer(['talk::partials.inbox'], function ($view) {
            $inbox = Talk::getInbox();
            $view->with(compact('inbox'));
        });
	}
 
    public function inbox()
    {
        return view('talk::layouts.master');
    }

    public function readMessage($id)
    {
        $messages = Talk::getConversationsById($id);
        return view('talk::message', compact('messages', 'id'));
    }

    public function send(Request $req)
    {
        $conversationId = $req->input('_conversation_id');
        $message = $req->input('message');
        $send = Talk::sendMessage($conversationId, $message);
        if($send) {
            return redirect()->back();
        }
    }

    public function deleteConversation($id)
    {
        if(Talk::deleteConversations($id)) {
            return redirect()->back();
        }
    }
 
}
