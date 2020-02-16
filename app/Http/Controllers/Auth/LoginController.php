<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

	/**
	 * @param Request $request
	 * @param $user
	 *
	 * @return Factory|RedirectResponse|View
	 */
	protected function authenticated( Request $request, $user ) {

		$sessionId     = Session::getId();
		$lastSessionId = $user->last_session_id;

		if ( ! empty( $lastSessionId ) ) {
			$lastSession = Session::getHandler()->read( $lastSessionId ); // retrieve last session
			if ( $lastSession ) {
				Session::put( 'multiple_session_flag', [ 'active' => '1', 'request_time' => Carbon::now() ] );

				return view( 'auth.sessionChooser' );
			}
		}

		$user->last_session_id = $sessionId;
		$user->save();

		return redirect()->intended( $this->redirectPath() );
	}


}
