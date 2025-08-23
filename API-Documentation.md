# CBT Proctoring API Documentation

Dokumentasi lengkap untuk API Backend Laravel yang digunakan oleh aplikasi Android CBT Proctoring.

## Base URL
```
http://localhost:8000/api
```

## Authentication

API menggunakan beberapa metode autentikasi:

### 1. API Key (Header)
```
X-API-Key: your_api_key_here
```

### 2. Admin PIN (Header)
```
X-Admin-Pin: 1234
```

### 3. Session ID (Body/Query)
```json
{
  "session_id": "session_abc123_1234567890"
}
```

## Content Type
Semua request harus menggunakan:
```
Content-Type: application/json
Accept: application/json
```

---

## Proctoring Endpoints

### 1. Start Proctoring Session
**POST** `/proctoring/session/start`

Memulai sesi proctoring baru untuk peserta.

**Request Body:**
```json
{
  "participant_id": "student_123",
  "device_id": "android_device_abc123",
  "device_model": "Samsung Galaxy S21",
  "android_version": "12",
  "app_version": "1.0.0",
  "device_info": {
    "manufacturer": "Samsung",
    "model": "SM-G991B",
    "android_version": "12",
    "api_level": 31,
    "screen_resolution": "1080x2400",
    "memory_total": "8GB",
    "storage_available": "45GB"
  }
}
```

**Success Response (201):**
```json
{
  "success": true,
  "message": "Proctoring session started successfully",
  "data": {
    "session_id": "session_abc123_1234567890",
    "participant_id": "student_123",
    "started_at": "2024-01-01T10:00:00Z",
    "status": "active"
  }
}
```

---

### 2. End Proctoring Session
**POST** `/proctoring/session/{sessionId}/end`

Mengakhiri sesi proctoring.

**Request Body:**
```json
{
  "status": "completed",
  "notes": "Exam completed successfully"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Proctoring session ended successfully",
  "data": {
    "session_id": "session_abc123_1234567890",
    "ended_at": "2024-01-01T11:30:00Z",
    "status": "completed",
    "total_violations": 2,
    "total_photos": 15
  }
}
```

---

### 3. Log Activity
**POST** `/proctoring/activity-log`

Mencatat aktivitas peserta selama ujian.

**Request Body:**
```json
{
  "session_id": "session_abc123_1234567890",
  "activity_type": "app_launch",
  "activity_subtype": "normal_launch",
  "timestamp": "2024-01-01T10:00:00Z",
  "description": "CBT app launched by participant",
  "severity": "info",
  "is_violation": false,
  "metadata": {
    "app_version": "1.0.0",
    "launch_time_ms": 2500,
    "device_orientation": "portrait"
  }
}
```

**Activity Types:**
- `app_launch` - Aplikasi dibuka
- `app_exit` - Aplikasi ditutup
- `app_pause` - Aplikasi di-pause
- `app_resume` - Aplikasi di-resume
- `webview_load` - WebView memuat halaman
- `webview_error` - Error di WebView
- `security_violation` - Pelanggaran keamanan
- `network_change` - Perubahan jaringan
- `photo_capture` - Foto diambil
- `admin_access` - Akses admin

**Severity Levels:**
- `info` - Informasi normal
- `warning` - Peringatan
- `error` - Error
- `critical` - Kritis

**Success Response (201):**
```json
{
  "success": true,
  "message": "Activity logged successfully",
  "data": {
    "log_id": 123,
    "activity_type": "app_launch",
    "timestamp": "2024-01-01T10:00:00Z",
    "is_violation": false
  }
}
```

---

### 4. Upload Proctoring Photo
**POST** `/proctoring/photo`

Upload foto dari kamera depan untuk proctoring.

**Request (Multipart Form Data):**
```
session_id: session_abc123_1234567890
photo: [FILE] (JPEG/PNG, max 2MB)
captured_at: 2024-01-01T10:05:00Z
camera_metadata: {"camera_facing":"front","resolution":"1920x1080"}
notes: Periodic proctoring photo
```

**Success Response (201):**
```json
{
  "success": true,
  "message": "Photo uploaded successfully",
  "data": {
    "photo_id": 456,
    "filename": "proctoring_session_abc123_2024-01-01_10-05-00.jpg",
    "captured_at": "2024-01-01T10:05:00Z",
    "file_size": 245760,
    "url": "http://localhost:8000/storage/proctoring/photos/student_123/proctoring_session_abc123_2024-01-01_10-05-00.jpg"
  }
}
```

---

### 5. Report Violation
**POST** `/proctoring/violation`

Melaporkan pelanggaran keamanan yang terdeteksi.

**Request Body:**
```json
{
  "session_id": "session_abc123_1234567890",
  "violation_type": "app_exit_attempt",
  "violation_category": "behavior",
  "severity": "high",
  "description": "User attempted to exit application during exam",
  "evidence": {
    "detection_method": "system_api",
    "detection_time": "2024-01-01T10:15:00Z",
    "device_state": "active",
    "additional_info": "Back button pressed multiple times"
  },
  "action_taken": "Warning displayed to user"
}
```

**Violation Types:**
- `app_exit_attempt` - Percobaan keluar aplikasi
- `screenshot_attempt` - Percobaan screenshot
- `recording_attempt` - Percobaan screen recording
- `multi_window_detected` - Multi-window terdeteksi
- `root_detected` - Device root terdeteksi
- `emulator_detected` - Emulator terdeteksi
- `network_disconnected` - Jaringan terputus
- `camera_blocked` - Kamera diblokir
- `unauthorized_access` - Akses tidak sah
- `suspicious_activity` - Aktivitas mencurigakan

**Severity Levels:**
- `low` - Rendah
- `medium` - Sedang
- `high` - Tinggi
- `critical` - Kritis

**Success Response (201):**
```json
{
  "success": true,
  "message": "Violation reported successfully",
  "data": {
    "violation_id": 789,
    "violation_type": "app_exit_attempt",
    "severity": "high",
    "detected_at": "2024-01-01T10:15:00Z",
    "total_violations": 3
  }
}
```

---

### 6. Log Network Status
**POST** `/proctoring/network-status`

Mencatat status koneksi jaringan.

**Request Body:**
```json
{
  "session_id": "session_abc123_1234567890",
  "connection_status": "connected",
  "network_type": "WiFi",
  "duration_seconds": 120,
  "additional_info": "Signal strength: -45 dBm",
  "exam_paused": false
}
```

**Connection Status:**
- `connected` - Terhubung
- `disconnected` - Terputus
- `poor` - Koneksi buruk
- `excellent` - Koneksi sangat baik

**Network Types:**
- `WiFi` - WiFi
- `Mobile` - Data seluler
- `Ethernet` - Kabel LAN
- `Unknown` - Tidak diketahui

**Success Response (201):**
```json
{
  "success": true,
  "message": "Network status logged successfully",
  "data": {
    "log_id": 101,
    "connection_status": "connected",
    "network_type": "WiFi",
    "exam_paused": false
  }
}
```

---

### 7. Get Session Summary
**GET** `/proctoring/session/{sessionId}/summary`

Mendapatkan ringkasan sesi proctoring.

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "session": {
      "session_id": "session_abc123_1234567890",
      "participant_id": "student_123",
      "device_model": "Samsung Galaxy S21",
      "android_version": "12",
      "started_at": "2024-01-01T10:00:00Z",
      "ended_at": "2024-01-01T11:30:00Z",
      "status": "completed",
      "violation_count": 3,
      "photo_count": 15
    },
    "statistics": {
      "total_activities": 47,
      "total_violations": 3,
      "total_photos": 15,
      "network_disconnections": 1,
      "critical_violations": 0
    }
  }
}
```

---

## Configuration Endpoints

### 1. Get CBT URL
**GET** `/config/cbt-url`

Mendapatkan URL CBT yang harus diakses.

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "cbt_url": "https://cbt.edupus.id",
    "updated_at": "2024-01-01T09:00:00Z"
  }
}
```

---

### 2. Update CBT URL
**POST** `/config/cbt-url`

Mengubah URL CBT (memerlukan admin PIN).

**Request Body:**
```json
{
  "cbt_url": "https://new-cbt.edupus.id",
  "admin_pin": "1234"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "CBT URL updated successfully",
  "data": {
    "cbt_url": "https://new-cbt.edupus.id",
    "updated_at": "2024-01-01T12:00:00Z"
  }
}
```

---

### 3. Verify Admin PIN
**POST** `/config/admin-pin/verify`

Memverifikasi PIN admin.

**Request Body:**
```json
{
  "admin_pin": "1234"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "is_valid": true,
    "verified_at": "2024-01-01T12:00:00Z"
  }
}
```

---

### 4. Update Admin PIN
**POST** `/config/admin-pin/update`

Mengubah PIN admin.

**Request Body:**
```json
{
  "current_pin": "1234",
  "new_pin": "5678",
  "confirm_pin": "5678"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Admin PIN updated successfully",
  "data": {
    "updated_at": "2024-01-01T12:00:00Z"
  }
}
```

---

### 5. Get App Configuration
**GET** `/config/app`

Mendapatkan konfigurasi aplikasi.

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "app_name": "CBT Proctoring",
    "app_version": "1.0.0",
    "photo_interval": 30,
    "max_violations": 3,
    "network_check_interval": 10,
    "violation_threshold": 5,
    "auto_submit_enabled": true,
    "cbt_url": "https://cbt.edupus.id",
    "security_features": {
      "flag_secure": true,
      "lock_task_mode": true,
      "anti_root_check": true,
      "anti_emulator_check": true,
      "screenshot_detection": true
    },
    "proctoring_features": {
      "camera_monitoring": true,
      "network_monitoring": true,
      "activity_logging": true,
      "violation_reporting": true
    }
  }
}
```

---

## Utility Endpoints

### 1. Health Check
**GET** `/health`

Cek status API dan service.

**Success Response (200):**
```json
{
  "status": "ok",
  "timestamp": "2024-01-01T12:00:00Z",
  "version": "1.0.0",
  "services": {
    "database": "connected",
    "storage": "available",
    "cache": "available"
  }
}
```

---

### 2. Ping
**GET** `/ping`

Test konektivitas sederhana.

**Success Response (200):**
```json
{
  "message": "pong",
  "timestamp": "2024-01-01T12:00:00Z"
}
```

---

## Error Responses

### Format Error Response
```json
{
  "success": false,
  "message": "Error message",
  "error": "Detailed error description",
  "errors": {
    "field_name": ["Validation error message"]
  }
}
```

### HTTP Status Codes
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `429` - Too Many Requests
- `500` - Internal Server Error

### Common Error Examples

**401 Unauthorized:**
```json
{
  "success": false,
  "message": "Unauthorized access",
  "error": "Valid API key, admin PIN, or session ID required"
}
```

**422 Validation Error:**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "session_id": ["The session id field is required."],
    "activity_type": ["The activity type field is required."]
  }
}
```

**404 Not Found:**
```json
{
  "success": false,
  "message": "Session not found",
  "error": "No session found with the provided session ID"
}
```

---

## Rate Limiting

API menggunakan rate limiting untuk mencegah spam:

- **Normal endpoints**: 60 requests per minute per IP
- **Proctoring endpoints**: 120 requests per minute per IP

**Rate Limit Headers:**
```
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1704110400
```

**Rate Limit Exceeded (429):**
```json
{
  "success": false,
  "message": "Too many requests",
  "error": "Rate limit exceeded. Try again later."
}
```

---

## Testing

Gunakan endpoint test untuk memverifikasi koneksi:

### Test Database
**GET** `/test/database`

### Test File Upload
**POST** `/test/upload`
- Body: `multipart/form-data`
- Field: `test_file` (any file)

### Test Cache
**GET** `/test/cache`

---

## SDK Android Integration

Untuk memudahkan integrasi, gunakan contoh implementasi di file `android-integration-example.php` yang menyediakan:

1. Class `AndroidIntegrationExample` dengan semua method API
2. Helper functions untuk HTTP requests
3. Error handling dan response parsing
4. Contoh workflow lengkap proctoring session

---

## Environment Variables

Tambahkan ke file `.env` Laravel:

```env
# Proctoring Configuration
CBT_DEFAULT_URL=https://cbt.edupus.id
CBT_LOCAL_URL=http://localhost:8000
CBT_ADMIN_PIN=1234

# API Configuration  
API_RATE_LIMIT=60
API_PROCTORING_RATE_LIMIT=120
API_TOKEN_EXPIRY=24

# Security
PROCTORING_ANTI_ROOT=true
PROCTORING_ANTI_EMULATOR=true
PROCTORING_AUTO_SUBMIT=true

# Storage
PROCTORING_PHOTO_PATH=proctoring/photos
PROCTORING_MAX_PHOTO_SIZE=2048

# Intervals (seconds)
PROCTORING_PHOTO_INTERVAL=30
PROCTORING_NETWORK_INTERVAL=10
PROCTORING_MAX_VIOLATIONS=3
```