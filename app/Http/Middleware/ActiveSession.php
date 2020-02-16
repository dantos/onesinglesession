<?php

namespace App\Http\Middleware;

use Auth;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;

class ActiveSession
{

	/**
	 * Handle an incoming request.
	 * @param $request
	 * @param Closure $next
	 *
	 * @return ResponseFactory|RedirectResponse|Response|Redirector|mixed
	 */
	public function handle($request, Closure $next)
    {
	    /**
	     * Determine if the logged user has more than one session opened
	     * and hasn't chosen if continue with the current one or logout
	     */
	    $multiSessionFlag = Session::get( 'multiple_session_flag' );

	    if ( $multiSessionFlag['active'] == '1' ) {

		    $lastActivity = $multiSessionFlag['request_time'];
		    $lastActivity = Carbon::parse( $lastActivity );

		    //if no action have been done after one minute, the session will be closed
		    if ( $lastActivity->diffInMinutes( Carbon::now() ) >= 1 ) {
			    Auth::logout();
			    Session::flush();

			    return redirect( '/' );
		    }

		    return response( view( 'auth.sessionChooser' ) );
	    }

	    return $next( $request );
    }
}
