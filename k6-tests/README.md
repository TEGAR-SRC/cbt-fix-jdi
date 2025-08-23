# K6 Performance Tests untuk CBT Application

Koleksi test performa menggunakan K6 untuk menguji aplikasi CBT Laravel + Vue.js.

## 📋 Daftar Isi

- [Instalasi](#instalasi)
- [Jenis Test](#jenis-test)
- [Cara Menjalankan](#cara-menjalankan)
- [Konfigurasi](#konfigurasi)
- [Hasil Test](#hasil-test)
- [Troubleshooting](#troubleshooting)

## 🚀 Instalasi

### 1. Install K6

**Windows (dengan Chocolatey):**
```bash
npm run install-k6
```

**macOS (dengan Homebrew):**
```bash
npm run install-k6-mac
```

**Linux (Ubuntu/Debian):**
```bash
npm run install-k6-linux
```

**Manual Installation:**
- Download dari [https://k6.io/docs/getting-started/installation/](https://k6.io/docs/getting-started/installation/)

### 2. Verifikasi Instalasi
```bash
k6 version
```

## 🧪 Jenis Test

### 1. **Load Test** (`load-test.js`)
- **Tujuan**: Menguji performa normal aplikasi
- **Durasi**: ~16 menit
- **Max Users**: 20 concurrent users
- **Thresholds**: 
  - Response time < 2s (95%)
  - Error rate < 10%

### 2. **Stress Test** (`stress-test.js`)
- **Tujuan**: Mencari breaking point aplikasi
- **Durasi**: ~21 menit
- **Max Users**: 150 concurrent users
- **Thresholds**:
  - Response time < 5s (95%)
  - Error rate < 20%
  - Success rate > 80%

### 3. **Spike Test** (`spike-test.js`)
- **Tujuan**: Menguji handling traffic spike mendadak
- **Durasi**: ~9 menit
- **Normal Load**: 10 users
- **Spike Load**: 100 users (10x increase)
- **Thresholds**:
  - Response time < 3s (95%)
  - Error rate < 15%
  - Recovery rate > 80%

### 4. **API Test** (`api-test.js`)
- **Tujuan**: Fokus pada performa API endpoints
- **Durasi**: ~9 menit
- **Max Users**: 10 concurrent users
- **Thresholds**:
  - Response time < 2s (95%)
  - Error rate < 10%
  - API success rate > 90%

## ▶️ Cara Menjalankan

### Prerequisites
1. Pastikan aplikasi Laravel berjalan di `http://localhost:8000`
2. Pastikan database sudah terisi dengan data test
3. Pastikan user test sudah ada:
   - `admin@gmail.com` / `password`
   - `dev@tegar-aja.xyz` / `xxkenxyz`

### Quick Test (1 menit)
```bash
npm run test:quick
```

### Load Test
```bash
npm run test:load
```

### Stress Test
```bash
npm run test:stress
```

### Spike Test
```bash
npm run test:spike
```

### API Test
```bash
npm run test:api
```

### Semua Test (Sequential)
```bash
npm run test:all
```

### Cloud Testing (K6 Cloud)
```bash
npm run test:cloud
```

### Monitoring dengan InfluxDB
```bash
npm run test:monitor
```

## ⚙️ Konfigurasi

### Mengubah Base URL
Edit file test dan ubah `BASE_URL`:
```javascript
const BASE_URL = 'http://your-domain.com';
```

### Mengubah Test Data
Edit array `testUsers` di setiap file test:
```javascript
const testUsers = [
  { email: 'your-email@domain.com', password: 'your-password' },
];
```

### Mengubah Thresholds
Edit `thresholds` di `options`:
```javascript
thresholds: {
  http_req_duration: ['p(95)<2000'], // Response time
  http_req_failed: ['rate<0.1'],     // Error rate
  // Custom thresholds
},
```

## 📊 Hasil Test

### Metrics yang Diukur
- **HTTP Request Duration**: Waktu response
- **HTTP Request Rate**: Jumlah request per detik
- **HTTP Request Failed**: Rate error
- **Virtual Users**: Jumlah user concurrent
- **Data Received/Sent**: Bandwidth usage

### Output Format
```
     █ setup

     █ teardown

     checks.........................: 100.00% ✓ 1500 ✗ 0
     data_received..................: 2.1 MB  23 kB/s
     data_sent......................: 1.1 MB  12 kB/s
     http_req_blocked..............: avg=1.2ms   min=0s      med=1ms     max=15ms    p(90)=2ms     p(95)=3ms
     http_req_connecting............: avg=0.5ms   min=0s      med=0s      max=8ms     p(90)=1ms     p(95)=2ms
     http_req_duration..............: avg=245.6ms min=120ms   med=230ms   max=1.2s    p(90)=320ms   p(95)=450ms
     http_req_failed................: 0.00%   ✓ 0         ✗ 1500
     http_req_receiving............: avg=2.1ms   min=1ms     med=2ms     max=15ms    p(90)=3ms     p(95)=4ms
     http_req_sending..............: avg=0.8ms   min=0s      med=1ms     max=10ms    p(90)=1ms     p(95)=2ms
     http_req_tls_handshaking......: avg=0s      min=0s      med=0s      max=0s      p(90)=0s      p(95)=0s
     http_req_waiting..............: avg=242.7ms min=115ms   med=227ms   max=1.2s    p(90)=317ms   p(95)=447ms
     http_reqs......................: 1500    16.666667/s
     iteration_duration............: avg=1.25s   min=1.1s    med=1.23s   max=2.3s    p(90)=1.32s   p(95)=1.45s
     iterations....................: 1500    16.666667/s
     vus............................: 20      min=20       max=20
     vus_max........................: 20      min=20       max=20
```

### Interpretasi Hasil
- **✓ Checks**: Semua assertions berhasil
- **HTTP Request Duration**: Waktu response rata-rata dan percentiles
- **HTTP Request Failed**: Rate error (harus < threshold)
- **Iterations**: Total test yang dijalankan

## 🔧 Troubleshooting

### Error: "Connection refused"
- Pastikan aplikasi Laravel berjalan
- Cek URL di `BASE_URL`
- Cek firewall/port

### Error: "Authentication failed"
- Pastikan user test ada di database
- Cek credentials di `testUsers`
- Pastikan login endpoint berfungsi

### Error: "CSRF token mismatch"
- Pastikan CSRF protection dikonfigurasi dengan benar
- Cek helper function `getCSRFToken()`

### Performance Issues
- Monitor server resources (CPU, RAM, Database)
- Cek database connection pool
- Optimize database queries
- Enable caching

### K6 Installation Issues
- Cek PATH environment variable
- Restart terminal setelah instalasi
- Cek versi K6: `k6 version`

## 📈 Best Practices

### Sebelum Testing
1. **Backup Database**: Backup data production
2. **Test Environment**: Gunakan environment test/development
3. **Monitor Resources**: Monitor server resources
4. **Clean Data**: Bersihkan data test sebelumnya

### Selama Testing
1. **Start Small**: Mulai dengan test kecil
2. **Monitor Real-time**: Monitor metrics real-time
3. **Document Issues**: Catat masalah yang ditemukan
4. **Stop if Critical**: Stop jika ada error kritis

### Setelah Testing
1. **Analyze Results**: Analisis hasil test
2. **Optimize**: Optimize berdasarkan hasil
3. **Document**: Dokumentasikan findings
4. **Plan Next**: Rencanakan test berikutnya

## 🛠️ Customization

### Menambah Test Case
1. Copy template dari file test yang ada
2. Modifikasi `options` sesuai kebutuhan
3. Tambahkan test case di function utama
4. Update thresholds

### Menambah Custom Metrics
```javascript
import { Rate, Trend } from 'k6/metrics';

const customMetric = new Rate('custom_metric');
const customTrend = new Trend('custom_trend');

// Di dalam test
customMetric.add(true);
customTrend.add(response.timings.duration);
```

### Menambah Custom Checks
```javascript
check(response, {
  'custom check': (r) => r.status === 200 && r.body.includes('expected'),
});
```

## 📞 Support

Jika ada masalah atau pertanyaan:
1. Cek dokumentasi K6: [https://k6.io/docs/](https://k6.io/docs/)
2. Cek troubleshooting section di atas
3. Review log aplikasi Laravel
4. Monitor server resources

---

**Note**: Test ini dirancang untuk environment development/testing. Jangan jalankan di production tanpa persiapan yang matang. 