#!/bin/bash

# CBT Application - K6 Test Runner
# For Linux/macOS

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_header() {
    echo -e "${BLUE}========================================${NC}"
    echo -e "${BLUE}   CBT Application - K6 Test Runner${NC}"
    echo -e "${BLUE}========================================${NC}"
    echo
}

print_success() {
    echo -e "${GREEN}$1${NC}"
}

print_warning() {
    echo -e "${YELLOW}$1${NC}"
}

print_error() {
    echo -e "${RED}$1${NC}"
}

# Function to check if k6 is installed
check_k6() {
    if ! command -v k6 &> /dev/null; then
        print_error "K6 tidak ditemukan. Silakan install K6 terlebih dahulu."
        echo "Pilihan instalasi:"
        echo "1. macOS: brew install k6"
        echo "2. Linux: sudo apt-get install k6"
        echo "3. Manual: https://k6.io/docs/getting-started/installation/"
        return 1
    fi
    return 0
}

# Function to show menu
show_menu() {
    print_header
    echo "Pilih jenis test yang ingin dijalankan:"
    echo
    echo "1. Quick Test (1 menit)"
    echo "2. Load Test"
    echo "3. Stress Test"
    echo "4. Spike Test"
    echo "5. API Test"
    echo "6. Semua Test (Sequential)"
    echo "7. Install K6"
    echo "8. Check K6 Version"
    echo "9. Exit"
    echo
}

# Function to run quick test
run_quick_test() {
    echo
    print_header
    echo "Menjalankan Quick Test..."
    echo
    k6 run --vus 5 --duration 1m load-test.js
    echo
    print_success "Quick test selesai!"
    read -p "Tekan Enter untuk melanjutkan..."
}

# Function to run load test
run_load_test() {
    echo
    print_header
    echo "Menjalankan Load Test..."
    echo
    echo "Durasi: ~16 menit"
    echo "Max Users: 20"
    echo
    k6 run load-test.js
    echo
    print_success "Load test selesai!"
    read -p "Tekan Enter untuk melanjutkan..."
}

# Function to run stress test
run_stress_test() {
    echo
    print_header
    echo "Menjalankan Stress Test..."
    echo
    echo "Durasi: ~21 menit"
    echo "Max Users: 150"
    echo
    print_warning "PERINGATAN: Test ini akan mendorong aplikasi ke batas maksimal!"
    echo "Pastikan aplikasi Laravel berjalan dan database siap."
    echo
    read -p "Lanjutkan? (y/n): " confirm
    if [[ $confirm == [yY] || $confirm == [yY][eE][sS] ]]; then
        k6 run stress-test.js
        echo
        print_success "Stress test selesai!"
    else
        echo "Test dibatalkan."
    fi
    read -p "Tekan Enter untuk melanjutkan..."
}

# Function to run spike test
run_spike_test() {
    echo
    print_header
    echo "Menjalankan Spike Test..."
    echo
    echo "Durasi: ~9 menit"
    echo "Normal Load: 10 users"
    echo "Spike Load: 100 users"
    echo
    k6 run spike-test.js
    echo
    print_success "Spike test selesai!"
    read -p "Tekan Enter untuk melanjutkan..."
}

# Function to run API test
run_api_test() {
    echo
    print_header
    echo "Menjalankan API Test..."
    echo
    echo "Durasi: ~9 menit"
    echo "Max Users: 10"
    echo
    k6 run api-test.js
    echo
    print_success "API test selesai!"
    read -p "Tekan Enter untuk melanjutkan..."
}

# Function to run all tests
run_all_tests() {
    echo
    print_header
    echo "Menjalankan Semua Test..."
    echo
    echo "Ini akan menjalankan semua test secara berurutan."
    echo "Total waktu: ~55 menit"
    echo
    read -p "Lanjutkan? (y/n): " confirm
    if [[ $confirm == [yY] || $confirm == [yY][eE][sS] ]]; then
        echo
        echo "1. Load Test..."
        k6 run load-test.js
        echo
        echo "2. Stress Test..."
        k6 run stress-test.js
        echo
        echo "3. Spike Test..."
        k6 run spike-test.js
        echo
        echo "4. API Test..."
        k6 run api-test.js
        echo
        print_success "Semua test selesai!"
    else
        echo "Test dibatalkan."
    fi
    read -p "Tekan Enter untuk melanjutkan..."
}

# Function to install k6
install_k6() {
    echo
    print_header
    echo "Installing K6..."
    echo
    echo "Pilih sistem operasi:"
    echo "1. macOS (Homebrew)"
    echo "2. Ubuntu/Debian"
    echo "3. CentOS/RHEL"
    echo "4. Manual installation"
    echo
    read -p "Pilihan (1-4): " os_choice
    
    case $os_choice in
        1)
            echo "Installing K6 on macOS..."
            brew install k6
            ;;
        2)
            echo "Installing K6 on Ubuntu/Debian..."
            sudo gpg -k
            sudo gpg --no-default-keyring --keyring /usr/share/keyrings/k6-archive-keyring.gpg --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys C5AD17C747E3415A3642D57D77C6C491D6AC1D69
            echo "deb [signed-by=/usr/share/keyrings/k6-archive-keyring.gpg] https://dl.k6.io/deb stable main" | sudo tee /etc/apt/sources.list.d/k6.list
            sudo apt-get update
            sudo apt-get install k6
            ;;
        3)
            echo "Installing K6 on CentOS/RHEL..."
            sudo yum install https://github.com/grafana/k6/releases/download/v0.45.0/k6-v0.45.0-linux-amd64.rpm
            ;;
        4)
            echo "Manual installation:"
            echo "Visit: https://k6.io/docs/getting-started/installation/"
            ;;
        *)
            echo "Pilihan tidak valid."
            ;;
    esac
    echo
    print_success "Instalasi selesai! Silakan restart terminal."
    read -p "Tekan Enter untuk melanjutkan..."
}

# Function to check k6 version
check_k6_version() {
    echo
    print_header
    echo "Checking K6 Version..."
    echo
    k6 version
    echo
    read -p "Tekan Enter untuk melanjutkan..."
}

# Main menu loop
while true; do
    show_menu
    read -p "Masukkan pilihan (1-9): " choice
    
    case $choice in
        1)
            if check_k6; then
                run_quick_test
            else
                read -p "Tekan Enter untuk melanjutkan..."
            fi
            ;;
        2)
            if check_k6; then
                run_load_test
            else
                read -p "Tekan Enter untuk melanjutkan..."
            fi
            ;;
        3)
            if check_k6; then
                run_stress_test
            else
                read -p "Tekan Enter untuk melanjutkan..."
            fi
            ;;
        4)
            if check_k6; then
                run_spike_test
            else
                read -p "Tekan Enter untuk melanjutkan..."
            fi
            ;;
        5)
            if check_k6; then
                run_api_test
            else
                read -p "Tekan Enter untuk melanjutkan..."
            fi
            ;;
        6)
            if check_k6; then
                run_all_tests
            else
                read -p "Tekan Enter untuk melanjutkan..."
            fi
            ;;
        7)
            install_k6
            ;;
        8)
            check_k6_version
            ;;
        9)
            echo
            print_success "Terima kasih telah menggunakan K6 Test Runner!"
            echo
            exit 0
            ;;
        *)
            print_error "Pilihan tidak valid. Silakan pilih 1-9."
            read -p "Tekan Enter untuk melanjutkan..."
            ;;
    esac
done 