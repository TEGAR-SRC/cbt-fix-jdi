<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class ProctoringAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if request has valid API key or admin PIN
        $apiKey = $request->header('X-API-Key');
        $adminPin = $request->header('X-Admin-Pin');
        
        // For session-based endpoints, check session ID
        if ($request->has('session_id')) {
            if ($this->isValidSession($request->session_id)) {
                return $next($request);
            }
        }
        
        // For admin endpoints, check admin PIN
        if ($adminPin && $this->isValidAdminPin($adminPin)) {
            return $next($request);
        }
        
        // For API key authentication
        if ($apiKey && $this->isValidApiKey($apiKey)) {
            return $next($request);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized access',
            'error' => 'Valid API key, admin PIN, or session ID required',
        ], 401);
    }
    
    /**
     * Check if session ID is valid
     */
    private function isValidSession(string $sessionId): bool
    {
        // Check if session exists in database
        return \App\Models\ProctoringSession::where('session_id', $sessionId)
            ->where('status', 'active')
            ->exists();
    }
    
    /**
     * Check if admin PIN is valid
     */
    private function isValidAdminPin(string $pin): bool
    {
        $storedHash = Cache::get('admin_pin_hash');
        
        // If no PIN is set, use default PIN
        if (!$storedHash) {
            $defaultPin = config('proctoring.default_admin_pin', '1234');
            return $pin === $defaultPin;
        }
        
        return Hash::check($pin, $storedHash);
    }
    
    /**
     * Check if API key is valid
     */
    private function isValidApiKey(string $apiKey): bool
    {
        // Check against configured API keys
        $validApiKeys = config('proctoring.api.valid_keys', []);
        
        if (empty($validApiKeys)) {
            // If no API keys configured, check against default
            $defaultApiKey = config('proctoring.api.default_key', 'default_cbt_api_key');
            return $apiKey === $defaultApiKey;
        }
        
        return in_array($apiKey, $validApiKeys);
    }
}