<?php

// app/Http/Middleware/LogAction.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ActionLog;
use Auth;

class LogAction
{
    public function handle(Request $request, Closure $next, $action, $description = null)
    {
        $response = $next($request);

        if (Auth::check()) {
            ActionLog::create([
                'action' => $action,
                'user' => Auth::user()->id,
                'description' => $description ?? "No description provided",
            ]);
        }

        return $response;
    }
}
