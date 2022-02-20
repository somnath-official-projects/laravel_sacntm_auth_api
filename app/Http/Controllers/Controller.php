<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Display our routes
     *
     * @return string
     */
    public function routes()
    {
        exec(base_path('artisan route:list'), $routes);
        foreach ($routes as $index => $route) {
            if (str_contains($route, 'debugbar')) {
                unset($routes[$index]);
            }
        }
        return '<pre>' . implode("\n", $routes) . '</pre>';
    }
}
