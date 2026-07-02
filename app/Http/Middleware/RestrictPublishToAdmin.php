<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictPublishToAdmin
{
	public function handle(Request $request, Closure $next): Response
	{
		if (! optional($request->user())->isAdmin()) {
			if ($request->isJson()) {
				$request->json()->remove('publish');
			} else {
				$request->request->remove('publish');
			}
		}

		return $next($request);
	}
}
