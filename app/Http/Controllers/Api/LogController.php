<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;

class LogController extends Controller
{
    /**
     *   "context": { ...optional... }
     * }
     */
    public function store(Request $request): Response
    {
        $level = $request->input('level', 'info');
        $message = $request->input('message', '');
        $context = $request->input('context', []);

        // Use Laravel logger according to level
        if (method_exists(Log::class, $level)) {
            Log::{$level}($message, $context);
        } else {
            Log::info($message, $context);
        }

        return response()->json([
            'success' => true,
            'message' => 'Log recorded',
        ]);
    }
}
