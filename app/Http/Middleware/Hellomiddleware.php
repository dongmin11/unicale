<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;

class Hellomiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        //コントローラーのアクション実行その結果を$responseへ代入
        $response = $next($request);
        //送り返されるHTMLソースコードを取得
        $content = $response->content();
        //
        $pattern = '/<middleware>(.*)<\/middleware>/i';
        $replace = '<a href="http://$1">$1</a>';
        $content = preg_replace($pattern,$replace,$content);
        $response->setContent($content);
        return $response;
    }
}