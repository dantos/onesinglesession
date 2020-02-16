<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

	public function chooseCurrentSession(Request $request) 
	{

		$user          = Auth::user();
		$sessionId     = Session::getId(); //get the new session id after user signed in
		$lastSessionId = $user->last_session_id;

		if ( ! empty( $lastSessionId ) ) {

			$lastSession                = Session::getHandler()->read( $lastSessionId );
			$continueWithCurrentSession = ( $request->continue == 'yes' ) ? true : false;

			if ( $continueWithCurrentSession && $lastSession ) {
				//if there was an active last session, then it's closed
				Session::getHandler()->destroy( $lastSessionId );
				Session::forget( 'multiple_session_flag' );

			} else {
				return view( 'auth.sessionChooser' );
			}
		}

		$user->last_session_id = $sessionId;
		$user->save();

		return redirect( 'login' );
	}
}
