<?php

namespace App\Http\Middleware;

use App\Services\Telegram\TelegramUserService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TelegramUserSync
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $update = $request->all();

        // message
        if (isset($update['message']['from'])) {
            $from = (object) $update['message']['from'];
            TelegramUserService::syncUser($from);
        }

        // callback_query
        if (isset($update['callback_query']['from'])) {
            $from = (object) $update['callback_query']['from'];
            TelegramUserService::syncUser($from);
        }

        return $next($request);
    }
}
