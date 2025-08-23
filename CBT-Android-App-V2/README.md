# CBT Proctoring Android App

Aplikasi Android untuk Computer Based Test (CBT) dengan sistem proctoring dan keamanan tingkat tinggi.

## Fitur Utama

### 🔒 Keamanan & Anti-Cheat
- **FLAG_SECURE**: Memblokir screenshot dan screen recording
- **Lock Task Mode (Kiosk Mode)**: Mencegah keluar dari aplikasi atau membuka aplikasi lain
- **Anti-Root & Anti-Emulator**: Deteksi perangkat yang tidak aman
- **Device Admin**: Kontrol penuh terhadap perangkat selama ujian
- **Auto-Submit**: Otomatis submit jawaban jika terdeteksi percobaan curang

### 📱 Admin Control
- **Admin Login dengan PIN**: Akses aman untuk administrator
- **Konfigurasi URL CBT**: Admin dapat mengubah URL CBT yang diakses peserta
- **Keluar Aplikasi**: Hanya bisa keluar dengan PIN admin
- **Status Monitoring**: Monitor status perangkat dan izin secara real-time

### 🎥 Proctoring & Monitoring
- **Kamera Depan Aktif**: Mengambil foto peserta secara berkala (setiap 30 detik)
- **Monitor Koneksi Internet**: Cek koneksi real-time dan pause ujian jika terputus
- **Activity Logs**: Kirim log aktivitas ke backend Laravel via API:
  - Waktu masuk/keluar aplikasi
  - Percobaan cheat/keluar paksa
  - Foto peserta dari kamera depan
  - Status koneksi internet

### 🌐 WebView Browser
- **Load URL CBT**: URL dapat dikonfigurasi melalui admin panel
- **JavaScript & DOM Storage**: Aktif untuk mendukung aplikasi web CBT
- **Keamanan WebView**: 
  - Nonaktifkan zoom, copy-paste, long press, context menu
  - Inject security JavaScript untuk mencegah debugging
  - Responsif landscape & portrait

### 🎨 UI Modern & Dinamis
- **Splash Screen**: Logo dan animasi modern
- **Admin Panel**: Form modern untuk konfigurasi URL & PIN
- **Exam Interface**: UI fullscreen dengan status monitoring
- **Notifikasi Modern**: Snackbar dan dialog dengan Material Design
- **Tema Dinamis**: Dapat diganti tanpa update APK

### 📊 Monitoring Real-time
- **Network Status**: Monitor koneksi WiFi/Mobile/Ethernet
- **Proctoring Status**: Indikator aktif/tidak aktif
- **Security Violations**: Counter pelanggaran dengan batas maksimal
- **Exam Timer**: Timer ujian real-time

## Persyaratan Sistem

- **Android**: API Level 24+ (Android 7.0+)
- **Permissions**: 
  - `CAMERA` - untuk proctoring foto peserta
  - `INTERNET` - untuk WebView dan API backend
  - `ACCESS_NETWORK_STATE` - monitoring koneksi
  - Device Admin permission untuk kiosk mode

## Backend API Integration

Aplikasi terintegrasi dengan Laravel backend melalui REST API:

- `POST /api/proctoring/photo` - Upload foto proctoring
- `POST /api/proctoring/log` - Log aktivitas ujian
- `POST /api/proctoring/cheat-attempt` - Laporan percobaan curang
- `POST /api/proctoring/network-event` - Log koneksi jaringan
- `POST /api/proctoring/auto-submit` - Auto-submit karena pelanggaran

## Konfigurasi

### Default Settings
- **Admin PIN**: `123456` (dapat diubah melalui admin panel)
- **CBT URL**: `http://192.168.1.100:8000/student/exam`
- **Photo Interval**: 30 detik
- **Max Cheat Attempts**: 3 kali

### URL Backend
Default API base URL: `http://192.168.1.100:8000/api`

Untuk mengubah URL, edit file `strings.xml`:
```xml
<string name="api_base_url">http://your-server.com/api</string>
<string name="default_cbt_url">http://your-server.com/student/exam</string>
```

## Instalasi & Deployment

### Build APK
```bash
./gradlew assembleRelease
```

### Build AAB (untuk Play Store)
```bash
./gradlew bundleRelease
```

### Debug Build
```bash
./gradlew assembleDebug
```

## Play Store Compliance

### Permissions Minimal
- Hanya menggunakan `INTERNET` dan `CAMERA`
- Semua permission dijelaskan di Privacy Policy
- Compliant dengan Google Play Policy untuk proctoring apps

### Data Privacy
- Foto dan data proctoring hanya untuk keperluan ujian
- Data ditransmisikan secara aman ke server ujian
- Tidak dibagikan dengan pihak ketiga
- Data otomatis dihapus setelah ujian selesai

### Security Features
- Kode production-ready dengan obfuscation
- Anti-tampering dengan ProGuard
- Secure communication dengan HTTPS
- Device fingerprinting untuk validasi

## Arsitektur Aplikasi

### Activities
- `SplashActivity` - Splash screen dan security checks
- `AdminLoginActivity` - Login admin dengan PIN
- `AdminPanelActivity` - Panel konfigurasi admin
- `ExamActivity` - Browser ujian dengan kiosk mode
- `ErrorActivity` - Handling error dan network issues

### Services
- `ProctoringService` - Foreground service untuk capture foto
- `NetworkMonitoringService` - Monitor koneksi internet

### Security
- `SecurityChecker` - Deteksi root, emulator, debugging
- `DeviceAdminReceiver` - Device administration policies

### Networking
- `ProctoringApiService` - Retrofit interface untuk backend API
- `NetworkUtil` - Utility untuk monitoring network

## Troubleshooting

### Camera Permission
Jika permission camera tidak granted:
1. Restart aplikasi
2. Allow camera permission di settings
3. Enable device admin di Security settings

### Network Issues
Jika koneksi bermasalah:
1. Cek koneksi internet
2. Pastikan backend server accessible
3. Cek firewall/proxy settings

### Kiosk Mode Issues
Jika tidak bisa keluar dari kiosk mode:
1. Gunakan PIN admin untuk keluar
2. Atau restart perangkat
3. Disable device admin di Settings > Security

## Pengembangan

### Requirements
- Android Studio Arctic Fox+
- JDK 8+
- Gradle 8.0+
- Kotlin 1.8+

### Dependencies
- AndroidX Libraries
- Material Design Components
- Retrofit untuk networking
- CameraX untuk proctoring
- Coroutines untuk async operations

## License

Aplikasi ini dikembangkan untuk keperluan ujian CBT dengan tingkat keamanan tinggi. 
Penggunaan terbatas untuk institusi pendidikan yang memiliki lisensi.

## Support

Untuk support dan dokumentasi lebih lanjut, hubungi tim development CBT.

---

**Version**: 1.0.0  
**Build**: Production Ready  
**Target**: Google Play Store Distribution