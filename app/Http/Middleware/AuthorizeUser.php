<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ... $roles): Response
    {
        $user_role = $request->user()->getRole();
        if(in_array($user_role, $roles)){
            return $next($request);
        }
        
        //jika tidak punya role, maka tampilkan eror 403
        abort(403, 'Forbidden. Kamu tidak punya akses pada halaman ini');
        
        // $user = $request->user(); //mengambil data user yang login
        //                           // fungsi user() diambil dari UserModel.php
        // if($user->hasRole($role)){ //cek apakah user punya role yang diinginkan
        //     return $next($request);
        // }
    }
}
