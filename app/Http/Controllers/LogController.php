<?php

namespace App\Http\Controllers;

use App\Models\ActionLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $logs = ActionLog::orderBy('created_at', 'desc')->with('user')->get();
        return response()->json($logs, 200);
    }
}
