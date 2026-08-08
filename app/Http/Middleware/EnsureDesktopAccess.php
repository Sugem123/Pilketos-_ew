<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDesktopAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->isSuccessful() && $request->routeIs('dashboard','*') ) {
            $content = $response->getContent();
            $script = '<script>if(window.innerWidth<1024){document.body.innerHTML=\'<div style="display:flex;align-items:center;justify-content:center;height:100vh;font-family:Montserrat,sans-serif;text-align:center;padding:2rem"><div><h2 style="font-size:1.5rem;font-weight:700;margin-bottom:1rem">Akses Dibatasi</h2><p style="color:#6b7280">Halaman ini hanya dapat diakses dari desktop/laptop dengan lebar layar minimal 1024px.</p></div></div>\';}</script>';
            $content = str_replace('</body>', $script . '</body>', $content);
            $response->setContent($content);
        }

        return $response;
    }
}
