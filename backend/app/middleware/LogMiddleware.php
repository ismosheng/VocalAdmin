<?php

declare(strict_types=1);
namespace app\middleware;

use think\facade\Log;
use think\facade\Request;

class LogMiddleware 
{
    public function handle(\think\Request $request, \Closure $next){
        
        Log::record(Request::url(true),Request::method());
        Log::record(Request::header(),'header');
        Log::record(input(),'params');
        return $next($request);
    }
}