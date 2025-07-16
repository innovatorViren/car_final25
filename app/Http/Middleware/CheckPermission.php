<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;

class CheckPermission
{

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next, $permission) {

		if (!Sentinel::check()) {
			return $this->denied($request);
		}
		$permission = explode('|', $permission);
		$permission[] = "users.superadmin";
		if (!Sentinel::hasAnyAccess($permission)) {
			return $this->denied($request);
		}

		return $next($request);
	}

	public function denied($request) {
		if ($request->ajax()) {
			$message = 'Unauthorized';
			return response()->json(['error' => $message], 401);
		} else {
			$message = 'You do not have permission to do that.';
			session()->flash('error', $message);
			return redirect()->route('dashboard');
		}
	}
}
