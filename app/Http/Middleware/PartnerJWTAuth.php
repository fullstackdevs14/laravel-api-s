<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use App\Partner;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Middleware\BaseMiddleware;

class PartnerJWTAuth extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Config::set('jwt.user', Partner::class);
        Config::set('auth.providers.users.model', Partner::class);

        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());
        }

        $token = $this->auth->setRequest($request)->getToken();
        $decode = decrypt(JWTAuth::getPayLoad($token)->get('role'));

        if( $decode === 'partner') {
            return $next($request);
        } else {
            return \response()->json('wrong_token', 401);
        }
    }
}
