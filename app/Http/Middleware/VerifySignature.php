<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifySignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $signature = $request->header('X-Signature');
        $timestamp = $request->header('X-Timestamp');

        if (!$signature || !$timestamp) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $secret = 'verysecret';
        $body = json_encode($request->all());
        $payload = $timestamp . $body;
        $computedSignature = hash_hmac('sha256', $payload, $secret);

        if (!hash_equals($computedSignature, $signature)) {
            return response()->json([
                'message' => 'Invalid signature'
            ], 401);
        }

        return $next($request);
    }
}
