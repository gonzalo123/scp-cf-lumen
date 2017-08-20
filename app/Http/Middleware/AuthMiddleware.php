<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PDO;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->getUser();
        $pass = $request->getPassword();

        $db = app(PDO::class);
        $stmt = $db->prepare('SELECT name, surname FROM public.user WHERE username=:USER AND pass=:PASS');
        $stmt->execute(['USER' => $user, 'PASS' => md5($pass)]);
        $row = $stmt->fetch();
        if ($row !== false) {
            config(['user' => $row]);
        } else {
            $headers = ['WWW-Authenticate' => 'Basic'];
            return response('Admin Login', 401, $headers);
        }

        return $next($request);
    }
}
