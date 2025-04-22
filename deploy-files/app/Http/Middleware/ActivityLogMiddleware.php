<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\ActivityLogger;
use Symfony\Component\HttpFoundation\Response;

class ActivityLogMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        if (auth()->check()) {
            $user = auth()->user();
            $route = $request->route();
            $routeName = $route ? $route->getName() : 'unknown';
            
            // Get the action description based on the route
            $action = $this->getActionDescription($request, $routeName);
            
            // Log the activity
            ActivityLogger::log(
                $action['type'],
                $action['description']
            );
        }

        return $response;
    }

    private function getActionDescription(Request $request, string $routeName): array
    {
        // Default values
        $type = 'page_access';
        $description = 'Accessed ' . $request->path();

        return [
            'type' => $type,
            'description' => $description
        ];
    }
} 