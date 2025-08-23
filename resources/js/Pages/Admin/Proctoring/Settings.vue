<template>
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
            <div class="d-block mb-4 mb-md-0">
                <h1 class="h3">Pengaturan Proctoring</h1>
                <p class="text-muted">Konfigurasi sistem proctoring dan keamanan</p>
            </div>
        </div>

        <div class="row">
            <!-- General Settings -->
            <div class="col-12 col-lg-8 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-header">
                        <h5 class="mb-0">Pengaturan Umum</h5>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="saveGeneralSettings">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">CBT URL</label>
                                    <input type="url" class="form-control" v-model="settings.cbtUrl" placeholder="https://cbt.edupus.id">
                                    <small class="text-muted">URL default untuk aplikasi CBT</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Interval Foto (detik)</label>
                                    <input type="number" class="form-control" v-model="settings.photoInterval" min="10" max="300">
                                    <small class="text-muted">Interval pengambilan foto proctoring</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Maksimal Pelanggaran</label>
                                    <input type="number" class="form-control" v-model="settings.maxViolations" min="1" max="10">
                                    <small class="text-muted">Jumlah maksimal pelanggaran sebelum auto-submit</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Interval Cek Jaringan (detik)</label>
                                    <input type="number" class="form-control" v-model="settings.networkCheckInterval" min="5" max="60">
                                    <small class="text-muted">Interval pengecekan status jaringan</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Threshold Pelanggaran</label>
                                    <input type="number" class="form-control" v-model="settings.violationThreshold" min="1" max="20">
                                    <small class="text-muted">Batas pelanggaran untuk tindakan otomatis</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Admin PIN Default</label>
                                    <input type="text" class="form-control" v-model="settings.defaultAdminPin" maxlength="8">
                                    <small class="text-muted">PIN default untuk akses admin</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" v-model="settings.autoSubmitEnabled" id="autoSubmit">
                                        <label class="form-check-label" for="autoSubmit">
                                            Aktifkan Auto-Submit
                                        </label>
                                        <small class="text-muted d-block">Otomatis submit ujian jika pelanggaran melebihi batas</small>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle me-1" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm3.857-9.809a.5.5 0 0 0-.714-.79L7.5 7.793 6.354 6.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l3.5-3.5z"/>
                                    </svg>
                                    Simpan Pengaturan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="col-12 col-lg-4 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-header">
                        <h5 class="mb-0">Pengaturan Keamanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" v-model="settings.flagSecure" id="flagSecure">
                                <label class="form-check-label" for="flagSecure">
                                    FLAG_SECURE
                                </label>
                                <small class="text-muted d-block">Blokir screenshot dan screen recording</small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" v-model="settings.lockTaskMode" id="lockTaskMode">
                                <label class="form-check-label" for="lockTaskMode">
                                    Lock Task Mode
                                </label>
                                <small class="text-muted d-block">Mode kiosk untuk mencegah keluar aplikasi</small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" v-model="settings.antiRootCheck" id="antiRootCheck">
                                <label class="form-check-label" for="antiRootCheck">
                                    Anti-Root Check
                                </label>
                                <small class="text-muted d-block">Deteksi device yang sudah di-root</small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" v-model="settings.antiEmulatorCheck" id="antiEmulatorCheck">
                                <label class="form-check-label" for="antiEmulatorCheck">
                                    Anti-Emulator Check
                                </label>
                                <small class="text-muted d-block">Deteksi emulator Android</small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" v-model="settings.screenshotDetection" id="screenshotDetection">
                                <label class="form-check-label" for="screenshotDetection">
                                    Screenshot Detection
                                </label>
                                <small class="text-muted d-block">Deteksi percobaan screenshot</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Proctoring Features -->
                <div class="card border-0 shadow mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Fitur Proctoring</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" v-model="settings.cameraMonitoring" id="cameraMonitoring">
                                <label class="form-check-label" for="cameraMonitoring">
                                    Camera Monitoring
                                </label>
                                <small class="text-muted d-block">Monitoring kamera depan</small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" v-model="settings.networkMonitoring" id="networkMonitoring">
                                <label class="form-check-label" for="networkMonitoring">
                                    Network Monitoring
                                </label>
                                <small class="text-muted d-block">Monitoring koneksi jaringan</small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" v-model="settings.activityLogging" id="activityLogging">
                                <label class="form-check-label" for="activityLogging">
                                    Activity Logging
                                </label>
                                <small class="text-muted d-block">Log semua aktivitas peserta</small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" v-model="settings.violationReporting" id="violationReporting">
                                <label class="form-check-label" for="violationReporting">
                                    Violation Reporting
                                </label>
                                <small class="text-muted d-block">Laporkan pelanggaran secara real-time</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin PIN Management -->
        <div class="row">
            <div class="col-12 col-lg-6 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-header">
                        <h5 class="mb-0">Kelola Admin PIN</h5>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="updateAdminPin">
                            <div class="mb-3">
                                <label class="form-label">PIN Saat Ini</label>
                                <input type="password" class="form-control" v-model="pinForm.currentPin" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">PIN Baru</label>
                                <input type="password" class="form-control" v-model="pinForm.newPin" minlength="4" maxlength="8" required>
                                <small class="text-muted">Minimal 4 karakter, maksimal 8 karakter</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Konfirmasi PIN Baru</label>
                                <input type="password" class="form-control" v-model="pinForm.confirmPin" required>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-warning">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-key me-1" viewBox="0 0 16 16">
                                        <path d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5z"/>
                                        <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                    </svg>
                                    Update PIN
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Reset Configuration -->
            <div class="col-12 col-lg-6 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-header">
                        <h5 class="mb-0">Reset Konfigurasi</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle me-2" viewBox="0 0 16 16">
                                <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/>
                                <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/>
                            </svg>
                            <strong>Peringatan!</strong> Tindakan ini akan mengembalikan semua pengaturan ke nilai default.
                        </div>
                        <form @submit.prevent="resetConfiguration">
                            <div class="mb-3">
                                <label class="form-label">Admin PIN</label>
                                <input type="password" class="form-control" v-model="resetForm.adminPin" required>
                                <small class="text-muted">Masukkan PIN admin untuk konfirmasi</small>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" v-model="resetForm.confirmReset" id="confirmReset" required>
                                    <label class="form-check-label" for="confirmReset">
                                        Saya yakin ingin mereset semua pengaturan
                                    </label>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise me-1" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                                        <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                                    </svg>
                                    Reset Konfigurasi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import AdminLayout from '@/Layouts/Admin.vue'

export default {
    name: 'ProctoringSettings',
    layout: AdminLayout,
    setup() {
        const settings = ref({
            cbtUrl: 'https://cbt.edupus.id',
            photoInterval: 30,
            maxViolations: 3,
            networkCheckInterval: 10,
            violationThreshold: 5,
            defaultAdminPin: '1234',
            autoSubmitEnabled: true,
            flagSecure: true,
            lockTaskMode: true,
            antiRootCheck: true,
            antiEmulatorCheck: true,
            screenshotDetection: true,
            cameraMonitoring: true,
            networkMonitoring: true,
            activityLogging: true,
            violationReporting: true
        })

        const pinForm = ref({
            currentPin: '',
            newPin: '',
            confirmPin: ''
        })

        const resetForm = ref({
            adminPin: '',
            confirmReset: false
        })

        const loadSettings = async () => {
            try {
                // Simulasi loading settings dari API
                console.log('Loading settings...')
            } catch (error) {
                console.error('Error loading settings:', error)
            }
        }

        const saveGeneralSettings = async () => {
            try {
                // Simulasi save settings ke API
                console.log('Saving general settings:', settings.value)
                alert('Pengaturan berhasil disimpan!')
            } catch (error) {
                console.error('Error saving settings:', error)
                alert('Gagal menyimpan pengaturan!')
            }
        }

        const updateAdminPin = async () => {
            try {
                if (pinForm.value.newPin !== pinForm.value.confirmPin) {
                    alert('PIN baru dan konfirmasi PIN tidak cocok!')
                    return
                }

                if (pinForm.value.newPin.length < 4) {
                    alert('PIN baru minimal 4 karakter!')
                    return
                }

                // Simulasi update PIN ke API
                console.log('Updating admin PIN:', pinForm.value)
                alert('PIN admin berhasil diupdate!')
                
                // Reset form
                pinForm.value = {
                    currentPin: '',
                    newPin: '',
                    confirmPin: ''
                }
            } catch (error) {
                console.error('Error updating PIN:', error)
                alert('Gagal mengupdate PIN!')
            }
        }

        const resetConfiguration = async () => {
            try {
                if (!resetForm.value.confirmReset) {
                    alert('Harap centang konfirmasi reset!')
                    return
                }

                // Simulasi reset configuration ke API
                console.log('Resetting configuration:', resetForm.value)
                alert('Konfigurasi berhasil direset ke default!')
                
                // Reset form
                resetForm.value = {
                    adminPin: '',
                    confirmReset: false
                }
                
                // Reload settings
                loadSettings()
            } catch (error) {
                console.error('Error resetting configuration:', error)
                alert('Gagal mereset konfigurasi!')
            }
        }

        onMounted(() => {
            loadSettings()
        })

        return {
            settings,
            pinForm,
            resetForm,
            saveGeneralSettings,
            updateAdminPin,
            resetConfiguration
        }
    }
}
</script>

<style scoped>
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.form-check-input:checked {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}
</style> 