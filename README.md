# CBT Ujian Online (Laravel 12 + Inertia + Vue 3)

Aplikasi CBT dengan fitur:

- CRUD Admin, Siswa, Ujian, Soal
- Monitoring real-time, kontrol ujian (unlock/stop/reopen, tambah waktu)
- Timer sinkron server + notifikasi tambah waktu ke siswa
- Soal dengan media: gambar, audio, video
- Import soal via Excel
- Import soal via AI (OpenAI-compatible) dengan editor manual & preview

## Menjalankan secara lokal

1) Salin .env dan isi koneksi DB, kunci TinyMCE, dan AI jika diperlukan
2) composer install; npm install
3) php artisan key:generate
4) php artisan migrate --seed
5) php artisan storage:link
6) npm run dev (atau npm run build)
7) php artisan serve

Admin default lihat seeder atau buat akun admin dari UI.

## Konfigurasi AI
Atur variabel ini di .env:

```
SUMOPOD_AI_KEY=sk-...
SUMOPOD_AI_BASE_URL=https://ai.sumopod.com/v1
```

## Catatan
- Jangan commit file .env dan direktori vendor/node_modules (sudah di .gitignore)
- Jika build Vite lama, jalankan npm run build ulang
