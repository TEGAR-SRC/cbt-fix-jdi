# Laravel Backend Integration untuk CBT Android App

Folder ini berisi contoh file-file yang diperlukan untuk integrasi backend Laravel dengan aplikasi Android CBT Proctoring.

## Struktur File

### 1. **Database Migrations**
- `database/migrations/` - File migrasi untuk tabel proctoring

### 2. **Models**
- `app/Models/` - Model Eloquent untuk data proctoring

### 3. **Controllers**
- `app/Http/Controllers/` - Controller untuk API endpoints

### 4. **API Routes**
- `routes/api.php` - Definisi route API

### 5. **Services**
- `app/Services/` - Service class untuk logika bisnis

### 6. **Middleware**
- `app/Http/Middleware/` - Middleware untuk autentikasi dan validasi

### 7. **Config**
- `config/` - File konfigurasi untuk proctoring

## Fitur yang Didukung

### 1. **Proctoring Data Collection**
- Log aktivitas peserta (masuk/keluar aplikasi)
- Foto peserta dari kamera depan
- Deteksi percobaan cheat
- Status koneksi internet
- Violation counter

### 2. **Security Features**
- API authentication dengan token
- Rate limiting untuk mencegah spam
- Validasi data input
- Logging untuk audit trail

### 3. **Real-time Monitoring**
- WebSocket support untuk real-time updates
- Dashboard monitoring untuk admin
- Alert system untuk violation

## Cara Penggunaan

1. Copy file-file ke project Laravel CBT Anda
2. Jalankan migrasi database: `php artisan migrate`
3. Setup API routes di `routes/api.php`
4. Konfigurasi environment variables
5. Test API endpoints dengan aplikasi Android

## API Endpoints

### Authentication
- `POST /api/auth/login` - Login admin
- `POST /api/auth/logout` - Logout admin

### Proctoring Data
- `POST /api/proctoring/activity-log` - Kirim log aktivitas
- `POST /api/proctoring/photo` - Upload foto peserta
- `POST /api/proctoring/violation` - Report violation
- `POST /api/proctoring/network-status` - Update status jaringan

### Configuration
- `GET /api/config/cbt-url` - Ambil URL CBT
- `POST /api/config/cbt-url` - Update URL CBT
- `GET /api/config/admin-pin` - Verifikasi PIN admin

## Environment Variables

```env
# Proctoring Configuration
PROCTORING_PHOTO_INTERVAL=30
PROCTORING_MAX_VIOLATIONS=3
PROCTORING_STORAGE_PATH=storage/app/proctoring/photos

# API Configuration
API_RATE_LIMIT=60
API_TOKEN_EXPIRY=24

# Security
SECURITY_VIOLATION_THRESHOLD=5
SECURITY_AUTO_SUBMIT=true
```

## Testing

Gunakan Postman atau curl untuk test API:

```bash
# Test activity log
curl -X POST http://localhost:8000/api/proctoring/activity-log \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "participant_id": "123",
    "activity_type": "app_launch",
    "timestamp": "2024-01-01T10:00:00Z",
    "device_info": {
      "model": "Samsung Galaxy S21",
      "android_version": "12"
    }
  }'
```

## Security Considerations

1. **API Rate Limiting** - Mencegah spam request
2. **Token Authentication** - Memastikan request valid
3. **Data Validation** - Validasi semua input data
4. **File Upload Security** - Validasi file foto
5. **Audit Logging** - Log semua aktivitas untuk audit

## Support

Untuk bantuan lebih lanjut, silakan buka issue di repository atau hubungi tim development.
