<?php
namespace Nahid\Talk\Example;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

use Illuminate\Http\Request;
use Auth;

use Nahid\Talk\Facades\Talk;
 
class LoginController extends BaseController
{
	use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

	function __construct()
	{

	}
 
    public function login()
    {
        return view('talk::login');
    }

    public function makeLogin(Request $req)
    {
        $email = $req->input('email');
        $password = $req->input('password');

        if (Auth::attempt(['email'=>$email, 'password'=>$password])) {
            //return 'hello';
            return redirect('laravel-talk/example/message/inbox');
        }
    }
 
}
