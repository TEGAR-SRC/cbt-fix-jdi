<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class ConfigController extends Controller
{
    /**
     * Get CBT URL configuration
     */
    public function getCbtUrl(): JsonResponse
    {
        try {
            $cbtUrl = Cache::get('cbt_url', config('proctoring.default_cbt_url', 'https://cbt.edupus.id'));
            
            return response()->json([
                'success' => true,
                'data' => [
                    'cbt_url' => $cbtUrl,
                    'updated_at' => Cache::get('cbt_url_updated_at'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get CBT URL',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update CBT URL configuration
     */
    public function updateCbtUrl(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'cbt_url' => 'required|url',
            'admin_pin' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify admin PIN
        if (!$this->verifyAdminPin($request->admin_pin)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid admin PIN'
            ], 401);
        }

        try {
            $newUrl = $request->cbt_url;
            
            // Store in cache and persistent storage
            Cache::put('cbt_url', $newUrl, now()->addYears(1));
            Cache::put('cbt_url_updated_at', now(), now()->addYears(1));
            
            // Log the configuration change
            \Log::info('CBT URL updated', [
                'old_url' => Cache::get('cbt_url_backup'),
                'new_url' => $newUrl,
                'admin_ip' => $request->ip(),
                'updated_at' => now(),
            ]);
            
            Cache::put('cbt_url_backup', Cache::get('cbt_url'), now()->addYears(1));
            
            return response()->json([
                'success' => true,
                'message' => 'CBT URL updated successfully',
                'data' => [
                    'cbt_url' => $newUrl,
                    'updated_at' => now(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update CBT URL',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify admin PIN via API
     */
    public function verifyAdminPinApi(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'admin_pin' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $isValid = $this->verifyAdminPin($request->admin_pin);
        
        return response()->json([
            'success' => true,
            'data' => [
                'is_valid' => $isValid,
                'verified_at' => $isValid ? now() : null,
            ]
        ]);
    }

    /**
     * Update admin PIN
     */
    public function updateAdminPin(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'current_pin' => 'required|string',
            'new_pin' => 'required|string|min:4|max:8',
            'confirm_pin' => 'required|string|same:new_pin',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify current PIN
        if (!$this->verifyAdminPin($request->current_pin)) {
            return response()->json([
                'success' => false,
                'message' => 'Current PIN is incorrect'
            ], 401);
        }

        try {
            $newPinHash = Hash::make($request->new_pin);
            
            // Store new PIN hash
            Cache::put('admin_pin_hash', $newPinHash, now()->addYears(1));
            Cache::put('admin_pin_updated_at', now(), now()->addYears(1));
            
            // Log the PIN change
            \Log::info('Admin PIN updated', [
                'admin_ip' => $request->ip(),
                'updated_at' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Admin PIN updated successfully',
                'data' => [
                    'updated_at' => now(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update admin PIN',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get app configuration
     */
    public function getAppConfig(): JsonResponse
    {
        try {
            $config = [
                'app_name' => config('app.name', 'CBT Proctoring'),
                'app_version' => config('proctoring.app_version', '1.0.0'),
                'photo_interval' => config('proctoring.photo_interval', 30),
                'max_violations' => config('proctoring.max_violations', 3),
                'network_check_interval' => config('proctoring.network_check_interval', 10),
                'violation_threshold' => config('proctoring.violation_threshold', 5),
                'auto_submit_enabled' => config('proctoring.auto_submit_enabled', true),
                'cbt_url' => Cache::get('cbt_url', config('proctoring.default_cbt_url')),
                'security_features' => [
                    'flag_secure' => true,
                    'lock_task_mode' => true,
                    'anti_root_check' => true,
                    'anti_emulator_check' => true,
                    'screenshot_detection' => true,
                ],
                'proctoring_features' => [
                    'camera_monitoring' => true,
                    'network_monitoring' => true,
                    'activity_logging' => true,
                    'violation_reporting' => true,
                ],
            ];
            
            return response()->json([
                'success' => true,
                'data' => $config
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get app configuration',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper method to verify admin PIN
     */
    private function verifyAdminPin(string $pin): bool
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
     * Reset configuration to defaults
     */
    public function resetConfig(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'admin_pin' => 'required|string',
            'confirm_reset' => 'required|boolean|accepted',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify admin PIN
        if (!$this->verifyAdminPin($request->admin_pin)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid admin PIN'
            ], 401);
        }

        try {
            // Reset CBT URL to default
            $defaultUrl = config('proctoring.default_cbt_url', 'https://cbt.edupus.id');
            Cache::put('cbt_url', $defaultUrl, now()->addYears(1));
            Cache::put('cbt_url_updated_at', now(), now()->addYears(1));
            
            // Clear admin PIN (will use default)
            Cache::forget('admin_pin_hash');
            Cache::forget('admin_pin_updated_at');
            
            // Log the reset
            \Log::info('Configuration reset to defaults', [
                'admin_ip' => $request->ip(),
                'reset_at' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Configuration reset to defaults successfully',
                'data' => [
                    'cbt_url' => $defaultUrl,
                    'admin_pin_reset' => true,
                    'reset_at' => now(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset configuration',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}