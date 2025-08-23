@echo off
echo ========================================
echo    CBT Application - K6 Test Runner
echo ========================================
echo.

:menu
echo Pilih jenis test yang ingin dijalankan:
echo.
echo 1. Quick Test (1 menit)
echo 2. Load Test
echo 3. Stress Test
echo 4. Spike Test
echo 5. API Test
echo 6. Semua Test (Sequential)
echo 7. Install K6
echo 8. Check K6 Version
echo 9. Exit
echo.
set /p choice="Masukkan pilihan (1-9): "

if "%choice%"=="1" goto quick
if "%choice%"=="2" goto load
if "%choice%"=="3" goto stress
if "%choice%"=="4" goto spike
if "%choice%"=="5" goto api
if "%choice%"=="6" goto all
if "%choice%"=="7" goto install
if "%choice%"=="8" goto version
if "%choice%"=="9" goto exit
goto menu

:quick
echo.
echo ========================================
echo    Menjalankan Quick Test...
echo ========================================
echo.
k6 run --vus 5 --duration 1m load-test.js
echo.
echo Quick test selesai!
pause
goto menu

:load
echo.
echo ========================================
echo    Menjalankan Load Test...
echo ========================================
echo.
echo Durasi: ~16 menit
echo Max Users: 20
echo.
k6 run load-test.js
echo.
echo Load test selesai!
pause
goto menu

:stress
echo.
echo ========================================
echo    Menjalankan Stress Test...
echo ========================================
echo.
echo Durasi: ~21 menit
echo Max Users: 150
echo.
echo PERINGATAN: Test ini akan mendorong aplikasi ke batas maksimal!
echo Pastikan aplikasi Laravel berjalan dan database siap.
echo.
set /p confirm="Lanjutkan? (y/n): "
if /i "%confirm%"=="y" (
    k6 run stress-test.js
    echo.
    echo Stress test selesai!
) else (
    echo Test dibatalkan.
)
pause
goto menu

:spike
echo.
echo ========================================
echo    Menjalankan Spike Test...
echo ========================================
echo.
echo Durasi: ~9 menit
echo Normal Load: 10 users
echo Spike Load: 100 users
echo.
k6 run spike-test.js
echo.
echo Spike test selesai!
pause
goto menu

:api
echo.
echo ========================================
echo    Menjalankan API Test...
echo ========================================
echo.
echo Durasi: ~9 menit
echo Max Users: 10
echo.
k6 run api-test.js
echo.
echo API test selesai!
pause
goto menu

:all
echo.
echo ========================================
echo    Menjalankan Semua Test...
echo ========================================
echo.
echo Ini akan menjalankan semua test secara berurutan.
echo Total waktu: ~55 menit
echo.
set /p confirm="Lanjutkan? (y/n): "
if /i "%confirm%"=="y" (
    echo.
    echo 1. Load Test...
    k6 run load-test.js
    echo.
    echo 2. Stress Test...
    k6 run stress-test.js
    echo.
    echo 3. Spike Test...
    k6 run spike-test.js
    echo.
    echo 4. API Test...
    k6 run api-test.js
    echo.
    echo Semua test selesai!
) else (
    echo Test dibatalkan.
)
pause
goto menu

:install
echo.
echo ========================================
echo    Installing K6...
echo ========================================
echo.
echo Menginstall K6 menggunakan Chocolatey...
choco install k6
echo.
echo Instalasi selesai! Silakan restart terminal.
pause
goto menu

:version
echo.
echo ========================================
echo    Checking K6 Version...
echo ========================================
echo.
k6 version
echo.
pause
goto menu

:exit
echo.
echo Terima kasih telah menggunakan K6 Test Runner!
echo.
exit 