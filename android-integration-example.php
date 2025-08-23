<?php

/*
|--------------------------------------------------------------------------
| CBT Proctoring Android Integration Example
|--------------------------------------------------------------------------
|
| Contoh implementasi untuk integrasi aplikasi Android CBT dengan backend Laravel.
| File ini menunjukkan bagaimana aplikasi Android dapat berkomunikasi 
| dengan API Laravel untuk proctoring.
|
*/

class AndroidIntegrationExample
{
    private $baseUrl;
    private $apiKey;
    
    public function __construct($baseUrl = 'http://localhost:8000/api', $apiKey = 'default_cbt_api_key')
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
    }
    
    /**
     * Example: Start Proctoring Session
     * Dipanggil ketika aplikasi Android dimulai
     */
    public function startProctoringSession()
    {
        $data = [
            'participant_id' => 'student_123',
            'device_id' => 'android_device_abc123',
            'device_model' => 'Samsung Galaxy S21',
            'android_version' => '12',
            'app_version' => '1.0.0',
            'device_info' => [
                'manufacturer' => 'Samsung',
                'model' => 'SM-G991B',
                'android_version' => '12',
                'api_level' => 31,
                'screen_resolution' => '1080x2400',
                'memory_total' => '8GB',
                'storage_available' => '45GB'
            ]
        ];
        
        return $this->postRequest('/proctoring/session/start', $data);
    }
    
    /**
     * Example: Log Activity
     * Dipanggil untuk mencatat aktivitas peserta
     */
    public function logActivity($sessionId, $activityType, $description = null, $isViolation = false)
    {
        $data = [
            'session_id' => $sessionId,
            'activity_type' => $activityType,
            'timestamp' => date('c'), // ISO 8601 format
            'description' => $description,
            'is_violation' => $isViolation,
            'metadata' => [
                'app_version' => '1.0.0',
                'timestamp_local' => time(),
                'timezone' => date_default_timezone_get()
            ]
        ];
        
        return $this->postRequest('/proctoring/activity-log', $data);
    }
    
    /**
     * Example: Upload Proctoring Photo
     * Dipanggil untuk upload foto dari kamera depan
     */
    public function uploadProctoringPhoto($sessionId, $photoPath, $capturedAt = null)
    {
        $data = [
            'session_id' => $sessionId,
            'captured_at' => $capturedAt ?: date('c'),
            'camera_metadata' => [
                'camera_facing' => 'front',
                'resolution' => '1920x1080',
                'flash_used' => false,
                'focus_mode' => 'auto'
            ],
            'notes' => 'Periodic proctoring photo'
        ];
        
        return $this->postFileRequest('/proctoring/photo', $data, 'photo', $photoPath);
    }
    
    /**
     * Example: Report Security Violation
     * Dipanggil ketika terdeteksi pelanggaran keamanan
     */
    public function reportViolation($sessionId, $violationType, $severity = 'medium', $description = '')
    {
        $data = [
            'session_id' => $sessionId,
            'violation_type' => $violationType,
            'severity' => $severity,
            'description' => $description,
            'evidence' => [
                'detection_method' => 'system_api',
                'detection_time' => date('c'),
                'device_state' => 'active'
            ]
        ];
        
        return $this->postRequest('/proctoring/violation', $data);
    }
    
    /**
     * Example: Log Network Status
     * Dipanggil ketika status jaringan berubah
     */
    public function logNetworkStatus($sessionId, $connectionStatus, $networkType = 'WiFi', $examPaused = false)
    {
        $data = [
            'session_id' => $sessionId,
            'connection_status' => $connectionStatus,
            'network_type' => $networkType,
            'exam_paused' => $examPaused,
            'additional_info' => json_encode([
                'signal_strength' => -45, // dBm
                'bandwidth_estimate' => '50 Mbps',
                'latency' => '25ms'
            ])
        ];
        
        return $this->postRequest('/proctoring/network-status', $data);
    }
    
    /**
     * Example: End Proctoring Session
     * Dipanggil ketika ujian selesai atau aplikasi ditutup
     */
    public function endProctoringSession($sessionId, $status = 'completed', $notes = null)
    {
        $data = [
            'status' => $status,
            'notes' => $notes
        ];
        
        return $this->postRequest("/proctoring/session/{$sessionId}/end", $data);
    }
    
    /**
     * Example: Get CBT URL
     * Dipanggil untuk mendapatkan URL CBT yang harus diakses
     */
    public function getCbtUrl()
    {
        return $this->getRequest('/config/cbt-url');
    }
    
    /**
     * Example: Verify Admin PIN
     * Dipanggil untuk verifikasi PIN admin
     */
    public function verifyAdminPin($pin)
    {
        $data = ['admin_pin' => $pin];
        return $this->postRequest('/config/admin-pin/verify', $data);
    }
    
    /**
     * Example: Get App Configuration
     * Dipanggil untuk mendapatkan konfigurasi aplikasi
     */
    public function getAppConfig()
    {
        return $this->getRequest('/config/app');
    }
    
    /**
     * Helper: POST Request
     */
    private function postRequest($endpoint, $data)
    {
        $url = $this->baseUrl . $endpoint;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-API-Key: ' . $this->apiKey,
            'Accept: application/json'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return [
            'http_code' => $httpCode,
            'response' => json_decode($response, true),
            'raw_response' => $response
        ];
    }
    
    /**
     * Helper: GET Request
     */
    private function getRequest($endpoint)
    {
        $url = $this->baseUrl . $endpoint;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-API-Key: ' . $this->apiKey,
            'Accept: application/json'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return [
            'http_code' => $httpCode,
            'response' => json_decode($response, true),
            'raw_response' => $response
        ];
    }
    
    /**
     * Helper: POST Request with File Upload
     */
    private function postFileRequest($endpoint, $data, $fileField, $filePath)
    {
        $url = $this->baseUrl . $endpoint;
        
        // Prepare multipart data
        $postData = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $postData[$key] = json_encode($value);
            } else {
                $postData[$key] = $value;
            }
        }
        
        // Add file
        if (file_exists($filePath)) {
            $postData[$fileField] = new \CURLFile($filePath);
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-API-Key: ' . $this->apiKey,
            'Accept: application/json'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return [
            'http_code' => $httpCode,
            'response' => json_decode($response, true),
            'raw_response' => $response
        ];
    }
}

/*
|--------------------------------------------------------------------------
| Usage Examples
|--------------------------------------------------------------------------
*/

// Initialize integration class
$integration = new AndroidIntegrationExample('http://localhost:8000/api', 'your_api_key');

// Example 1: Complete proctoring session workflow
function exampleProctoringWorkflow($integration)
{
    echo "=== Starting Proctoring Session ===\n";
    
    // 1. Start session
    $startResult = $integration->startProctoringSession();
    if ($startResult['http_code'] == 201) {
        $sessionId = $startResult['response']['data']['session_id'];
        echo "Session started: {$sessionId}\n";
        
        // 2. Log app launch activity
        $integration->logActivity($sessionId, 'app_launch', 'CBT app launched by participant');
        
        // 3. Get CBT URL
        $urlResult = $integration->getCbtUrl();
        if ($urlResult['http_code'] == 200) {
            $cbtUrl = $urlResult['response']['data']['cbt_url'];
            echo "CBT URL: {$cbtUrl}\n";
        }
        
        // 4. Simulate some activities during exam
        sleep(2);
        $integration->logActivity($sessionId, 'webview_load', 'CBT page loaded in WebView');
        
        // 5. Upload a proctoring photo (simulate)
        // $integration->uploadProctoringPhoto($sessionId, '/path/to/photo.jpg');
        
        // 6. Log network status
        $integration->logNetworkStatus($sessionId, 'connected', 'WiFi', false);
        
        // 7. Report a violation (simulate)
        $integration->reportViolation($sessionId, 'app_exit_attempt', 'medium', 'User attempted to exit application');
        
        // 8. End session
        $integration->endProctoringSession($sessionId, 'completed', 'Exam completed successfully');
        echo "Session ended\n";
    } else {
        echo "Failed to start session: " . $startResult['raw_response'] . "\n";
    }
}

// Example 2: Admin PIN verification
function exampleAdminVerification($integration)
{
    echo "=== Admin PIN Verification ===\n";
    
    $pinResult = $integration->verifyAdminPin('1234');
    if ($pinResult['http_code'] == 200) {
        $isValid = $pinResult['response']['data']['is_valid'];
        echo "PIN valid: " . ($isValid ? 'Yes' : 'No') . "\n";
    }
}

// Example 3: Get app configuration
function exampleGetConfig($integration)
{
    echo "=== App Configuration ===\n";
    
    $configResult = $integration->getAppConfig();
    if ($configResult['http_code'] == 200) {
        $config = $configResult['response']['data'];
        echo "App Name: " . $config['app_name'] . "\n";
        echo "Photo Interval: " . $config['photo_interval'] . " seconds\n";
        echo "Max Violations: " . $config['max_violations'] . "\n";
    }
}

// Uncomment to run examples:
// exampleProctoringWorkflow($integration);
// exampleAdminVerification($integration);
// exampleGetConfig($integration);

/*
|--------------------------------------------------------------------------
| Kotlin Android Implementation Reference
|--------------------------------------------------------------------------
|
| Berikut contoh implementasi di sisi Android (Kotlin):
|
| class ProctoringApiClient(private val baseUrl: String, private val apiKey: String) {
|     private val client = OkHttpClient()
|     private val gson = Gson()
|     
|     suspend fun startSession(sessionData: SessionStartData): ApiResponse<SessionResponse> {
|         val json = gson.toJson(sessionData)
|         val body = json.toRequestBody("application/json".toMediaType())
|         
|         val request = Request.Builder()
|             .url("$baseUrl/proctoring/session/start")
|             .post(body)
|             .addHeader("X-API-Key", apiKey)
|             .addHeader("Content-Type", "application/json")
|             .build()
|         
|         return withContext(Dispatchers.IO) {
|             val response = client.newCall(request).execute()
|             val responseBody = response.body?.string()
|             
|             if (response.isSuccessful) {
|                 val sessionResponse = gson.fromJson(responseBody, SessionResponse::class.java)
|                 ApiResponse.success(sessionResponse)
|             } else {
|                 ApiResponse.error("HTTP ${response.code}: ${responseBody}")
|             }
|         }
|     }
|     
|     suspend fun uploadPhoto(sessionId: String, photoFile: File): ApiResponse<PhotoUploadResponse> {
|         val requestBody = MultipartBody.Builder()
|             .setType(MultipartBody.FORM)
|             .addFormDataPart("session_id", sessionId)
|             .addFormDataPart("captured_at", Instant.now().toString())
|             .addFormDataPart(
|                 "photo", 
|                 photoFile.name,
|                 photoFile.asRequestBody("image/jpeg".toMediaType())
|             )
|             .build()
|         
|         val request = Request.Builder()
|             .url("$baseUrl/proctoring/photo")
|             .post(requestBody)
|             .addHeader("X-API-Key", apiKey)
|             .build()
|         
|         return withContext(Dispatchers.IO) {
|             val response = client.newCall(request).execute()
|             val responseBody = response.body?.string()
|             
|             if (response.isSuccessful) {
|                 val photoResponse = gson.fromJson(responseBody, PhotoUploadResponse::class.java)
|                 ApiResponse.success(photoResponse)
|             } else {
|                 ApiResponse.error("HTTP ${response.code}: ${responseBody}")
|             }
|         }
|     }
| }
|
*/