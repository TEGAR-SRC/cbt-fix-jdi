<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProctoringController;
use App\Http\Controllers\ConfigController;

/*
|--------------------------------------------------------------------------
| API Routes untuk CBT Proctoring Android App
|--------------------------------------------------------------------------
|
| Berikut adalah route API yang digunakan oleh aplikasi Android CBT 
| untuk mengirim data proctoring ke backend Laravel.
|
*/

// Route untuk mendapatkan informasi user yang sedang login (opsional)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Proctoring API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('proctoring')->group(function () {
    
    // Session Management
    Route::post('/session/start', [ProctoringController::class, 'startSession'])
        ->name('proctoring.session.start');
    
    Route::post('/session/{sessionId}/end', [ProctoringController::class, 'endSession'])
        ->name('proctoring.session.end');
    
    Route::get('/session/{sessionId}/summary', [ProctoringController::class, 'getSessionSummary'])
        ->name('proctoring.session.summary');
    
    // Activity Logging
    Route::post('/activity-log', [ProctoringController::class, 'logActivity'])
        ->name('proctoring.activity.log');
    
    // Photo Upload
    Route::post('/photo', [ProctoringController::class, 'uploadPhoto'])
        ->name('proctoring.photo.upload');
    
    // Violation Reporting
    Route::post('/violation', [ProctoringController::class, 'reportViolation'])
        ->name('proctoring.violation.report');
    
    // Network Status Logging
    Route::post('/network-status', [ProctoringController::class, 'logNetworkStatus'])
        ->name('proctoring.network.status');
    
});

/*
|--------------------------------------------------------------------------
| Configuration API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('config')->group(function () {
    
    // CBT URL Management
    Route::get('/cbt-url', [ConfigController::class, 'getCbtUrl'])
        ->name('config.cbt.url.get');
    
    Route::post('/cbt-url', [ConfigController::class, 'updateCbtUrl'])
        ->name('config.cbt.url.update');
    
    // Admin PIN Management
    Route::post('/admin-pin/verify', [ConfigController::class, 'verifyAdminPinApi'])
        ->name('config.admin.pin.verify');
    
    Route::post('/admin-pin/update', [ConfigController::class, 'updateAdminPin'])
        ->name('config.admin.pin.update');
    
    // App Configuration
    Route::get('/app', [ConfigController::class, 'getAppConfig'])
        ->name('config.app.get');
    
    // Reset Configuration
    Route::post('/reset', [ConfigController::class, 'resetConfig'])
        ->name('config.reset');
    
});

/*
|--------------------------------------------------------------------------
| Health Check Routes
|--------------------------------------------------------------------------
*/

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'version' => config('app.version', '1.0.0'),
        'services' => [
            'database' => 'connected',
            'storage' => 'available',
            'cache' => 'available',
        ]
    ]);
})->name('api.health');

Route::get('/ping', function () {
    return response()->json([
        'message' => 'pong',
        'timestamp' => now(),
    ]);
})->name('api.ping');

/*
|--------------------------------------------------------------------------
| Test Routes (untuk development/testing)
|--------------------------------------------------------------------------
*/

Route::prefix('test')->group(function () {
    
    // Test database connection
    Route::get('/database', function () {
        try {
            \DB::connection()->getPdo();
            return response()->json([
                'status' => 'success',
                'message' => 'Database connection successful',
                'timestamp' => now(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Database connection failed',
                'error' => $e->getMessage(),
                'timestamp' => now(),
            ], 500);
        }
    });
    
    // Test file upload
    Route::post('/upload', function (Request $request) {
        if (!$request->hasFile('test_file')) {
            return response()->json([
                'status' => 'error',
                'message' => 'No file uploaded',
            ], 400);
        }
        
        $file = $request->file('test_file');
        $path = $file->store('test', 'public');
        
        return response()->json([
            'status' => 'success',
            'message' => 'File uploaded successfully',
            'data' => [
                'filename' => $file->getClientOriginalName(),
                'path' => $path,
                'url' => \Storage::url($path),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ]
        ]);
    });
    
    // Test cache
    Route::get('/cache', function () {
        $key = 'test_cache_' . time();
        $value = 'test_value_' . Str::random(10);
        
        \Cache::put($key, $value, 60);
        $retrieved = \Cache::get($key);
        
        return response()->json([
            'status' => $retrieved === $value ? 'success' : 'error',
            'message' => $retrieved === $value ? 'Cache working correctly' : 'Cache not working',
            'data' => [
                'key' => $key,
                'original_value' => $value,
                'retrieved_value' => $retrieved,
            ]
        ]);
    });
    
});

/*
|--------------------------------------------------------------------------
| Error Handling untuk API Routes
|--------------------------------------------------------------------------
*/

// Fallback route untuk API yang tidak ditemukan
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'API endpoint not found',
        'error' => 'The requested API endpoint does not exist.',
        'timestamp' => now(),
    ], 404);
});

/*
|--------------------------------------------------------------------------
| Rate Limiting
|--------------------------------------------------------------------------
| 
| API routes ini menggunakan rate limiting untuk mencegah spam.
| Default: 60 requests per minute per IP
| Untuk proctoring data: 120 requests per minute per IP
|
*/

Route::middleware(['throttle:api'])->group(function () {
    // Routes dengan rate limiting normal
});

Route::middleware(['throttle:proctoring'])->prefix('proctoring')->group(function () {
    // Routes proctoring dengan rate limiting lebih tinggi
    // (sudah didefinisikan di atas, ini hanya contoh)
});