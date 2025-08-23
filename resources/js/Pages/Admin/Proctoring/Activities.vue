<template>
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
            <div class="d-block mb-4 mb-md-0">
                <h1 class="h3">Log Aktivitas Proctoring</h1>
                <p class="text-muted">Monitor aktivitas dan log sistem proctoring secara real-time</p>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" @click="refreshData">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise me-1" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                            <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                        </svg>
                        Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-12 col-sm-6 col-xl-3 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="row d-block d-xl-flex align-items-center">
                            <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                                <div class="icon-shape icon-shape-primary rounded me-4 me-sm-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-activity" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M6 2a.5.5 0 0 1 .47.33L8.667 8.5l1.2-3a.5.5 0 0 1 .933.002L12.5 9H15a.5.5 0 0 1 0 1h-2.8a.5.5 0 0 1-.467-.324L10.8 7.5l-1.2 3a.5.5 0 0 1-.934 0L6.53 3.67 5.2 7H1a.5.5 0 0 1 0-1h3.6a.5.5 0 0 1 .467.324L6 2z"/>
                                    </svg>
                                </div>
                                <div class="d-sm-none">
                                    <h2 class="fw-extrabold h5">Total Aktivitas</h2>
                                    <h3 class="mb-1">{{ stats.totalActivities }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h6 text-gray-400 mb-0">Total Aktivitas</h2>
                                    <h3 class="fw-extrabold mb-2">{{ stats.totalActivities }}</h3>
                                </div>
                                <small class="d-flex align-items-center text-primary">
                                    <span class="fas fa-arrow-up me-1"></span>
                                    <span>Hari ini</span>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="row d-block d-xl-flex align-items-center">
                            <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                                <div class="icon-shape icon-shape-success rounded me-4 me-sm-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm3.857-9.809a.5.5 0 0 0-.714-.79L7.5 7.793 6.354 6.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l3.5-3.5z"/>
                                    </svg>
                                </div>
                                <div class="d-sm-none">
                                    <h2 class="fw-extrabold h5">Normal</h2>
                                    <h3 class="mb-1">{{ stats.normalActivities }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h6 text-gray-400 mb-0">Aktivitas Normal</h2>
                                    <h3 class="fw-extrabold mb-2">{{ stats.normalActivities }}</h3>
                                </div>
                                <small class="d-flex align-items-center text-success">
                                    <span class="fas fa-arrow-up me-1"></span>
                                    <span>Berjalan baik</span>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="row d-block d-xl-flex align-items-center">
                            <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                                <div class="icon-shape icon-shape-warning rounded me-4 me-sm-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                                        <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/>
                                        <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/>
                                    </svg>
                                </div>
                                <div class="d-sm-none">
                                    <h2 class="fw-extrabold h5">Peringatan</h2>
                                    <h3 class="mb-1">{{ stats.warningActivities }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h6 text-gray-400 mb-0">Aktivitas Peringatan</h2>
                                    <h3 class="fw-extrabold mb-2">{{ stats.warningActivities }}</h3>
                                </div>
                                <small class="d-flex align-items-center text-warning">
                                    <span class="fas fa-arrow-up me-1"></span>
                                    <span>Perlu perhatian</span>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="row d-block d-xl-flex align-items-center">
                            <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                                <div class="icon-shape icon-shape-danger rounded me-4 me-sm-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                    </svg>
                                </div>
                                <div class="d-sm-none">
                                    <h2 class="fw-extrabold h5">Error</h2>
                                    <h3 class="mb-1">{{ stats.errorActivities }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h6 text-gray-400 mb-0">Aktivitas Error</h2>
                                    <h3 class="fw-extrabold mb-2">{{ stats.errorActivities }}</h3>
                                </div>
                                <small class="d-flex align-items-center text-danger">
                                    <span class="fas fa-arrow-up me-1"></span>
                                    <span>Kritis</span>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Jenis Aktivitas</label>
                        <select class="form-select" v-model="filters.activityType">
                            <option value="">Semua</option>
                            <option value="session_start">Mulai Sesi</option>
                            <option value="session_end">Akhir Sesi</option>
                            <option value="photo_capture">Ambil Foto</option>
                            <option value="violation_detected">Pelanggaran</option>
                            <option value="network_status">Status Jaringan</option>
                            <option value="app_launch">Luncurkan App</option>
                            <option value="app_exit">Keluar App</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Severity</label>
                        <select class="form-select" v-model="filters.severity">
                            <option value="">Semua</option>
                            <option value="info">Info</option>
                            <option value="warning">Peringatan</option>
                            <option value="error">Error</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Peserta</label>
                        <select class="form-select" v-model="filters.participant">
                            <option value="">Semua</option>
                            <option v-for="participant in participants" :key="participant" :value="participant">
                                {{ participant }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" class="form-control" v-model="filters.date">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button class="btn btn-primary me-2" @click="applyFilters">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel me-1" viewBox="0 0 16 16">
                                <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z"/>
                            </svg>
                            Terapkan Filter
                        </button>
                        <button class="btn btn-outline-secondary" @click="clearFilters">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle me-1" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                            </svg>
                            Bersihkan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Timeline -->
        <div class="card border-0 shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="fs-5 fw-bold mb-0">Timeline Aktivitas</h2>
                    </div>
                    <div class="col text-end">
                        <button class="btn btn-sm btn-primary" @click="refreshData">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise me-1" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                                <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                            </svg>
                            Refresh
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div v-for="activity in activities" :key="activity.id" class="timeline-item">
                        <div class="timeline-marker" :class="getSeverityClass(activity.severity)"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ getActivityTypeText(activity.activity_type) }}</h6>
                                    <p class="text-muted mb-1">{{ activity.description }}</p>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-light text-dark me-2">{{ activity.participant_id }}</span>
                                        <span class="badge bg-light text-dark me-2">{{ activity.session_id }}</span>
                                        <small class="text-muted">{{ formatDateTime(activity.timestamp) }}</small>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <span :class="getSeverityBadgeClass(activity.severity)">
                                        {{ getSeverityText(activity.severity) }}
                                    </span>
                                </div>
                            </div>
                            <div v-if="activity.metadata" class="mt-2">
                                <small class="text-muted">
                                    <strong>Metadata:</strong> {{ JSON.stringify(activity.metadata) }}
                                </small>
                            </div>
                        </div>
                    </div>
                    <div v-if="activities.length === 0" class="text-center py-4">
                        <div class="text-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-clock-history mb-3" viewBox="0 0 16 16">
                                <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.979a7.003 7.003 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.327.083-.656.12-.985zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z"/>
                                <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z"/>
                                <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z"/>
                            </svg>
                            <p class="mb-0">Tidak ada aktivitas yang ditemukan</p>
                        </div>
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
    name: 'ProctoringActivities',
    layout: AdminLayout,
    setup() {
        const activities = ref([])
        const stats = ref({
            totalActivities: 0,
            normalActivities: 0,
            warningActivities: 0,
            errorActivities: 0
        })
        const filters = ref({
            activityType: '',
            severity: '',
            participant: '',
            date: ''
        })
        const participants = ref([])

        const loadData = async () => {
            try {
                // Simulasi data untuk demo
                activities.value = [
                    {
                        id: 1,
                        activity_type: 'session_start',
                        description: 'Sesi proctoring dimulai untuk Peserta A',
                        participant_id: 'Peserta A',
                        session_id: 'SES-001',
                        timestamp: '2024-01-15 09:00:00',
                        severity: 'info',
                        metadata: { device_id: 'DEV-001', ip_address: '192.168.1.100' }
                    },
                    {
                        id: 2,
                        activity_type: 'photo_capture',
                        description: 'Foto proctoring berhasil diambil',
                        participant_id: 'Peserta A',
                        session_id: 'SES-001',
                        timestamp: '2024-01-15 09:30:00',
                        severity: 'info',
                        metadata: { photo_id: 'PHOTO-001', file_size: '245KB' }
                    },
                    {
                        id: 3,
                        activity_type: 'violation_detected',
                        description: 'Percobaan screenshot terdeteksi',
                        participant_id: 'Peserta B',
                        session_id: 'SES-002',
                        timestamp: '2024-01-15 10:15:00',
                        severity: 'warning',
                        metadata: { violation_type: 'screenshot_attempt', action: 'blocked' }
                    },
                    {
                        id: 4,
                        activity_type: 'network_status',
                        description: 'Koneksi jaringan terputus',
                        participant_id: 'Peserta C',
                        session_id: 'SES-003',
                        timestamp: '2024-01-15 11:00:00',
                        severity: 'error',
                        metadata: { network_type: 'wifi', duration: '30s' }
                    },
                    {
                        id: 5,
                        activity_type: 'session_end',
                        description: 'Sesi proctoring berakhir',
                        participant_id: 'Peserta A',
                        session_id: 'SES-001',
                        timestamp: '2024-01-15 12:00:00',
                        severity: 'info',
                        metadata: { duration: '3h', photos_taken: 15 }
                    }
                ]

                // Update stats
                stats.value = {
                    totalActivities: activities.value.length,
                    normalActivities: activities.value.filter(a => a.severity === 'info').length,
                    warningActivities: activities.value.filter(a => a.severity === 'warning').length,
                    errorActivities: activities.value.filter(a => a.severity === 'error').length
                }

                // Update participants list
                participants.value = [...new Set(activities.value.map(a => a.participant_id))]
            } catch (error) {
                console.error('Error loading activities:', error)
            }
        }

        const refreshData = () => {
            loadData()
        }

        const applyFilters = () => {
            // Implementasi filter
            console.log('Applying filters:', filters.value)
            loadData()
        }

        const clearFilters = () => {
            filters.value = {
                activityType: '',
                severity: '',
                participant: '',
                date: ''
            }
            loadData()
        }

        const getActivityTypeText = (type) => {
            const types = {
                'session_start': 'Mulai Sesi',
                'session_end': 'Akhir Sesi',
                'photo_capture': 'Ambil Foto',
                'violation_detected': 'Pelanggaran Terdeteksi',
                'network_status': 'Status Jaringan',
                'app_launch': 'Luncurkan App',
                'app_exit': 'Keluar App'
            }
            return types[type] || type
        }

        const getSeverityClass = (severity) => {
            switch (severity) {
                case 'info':
                    return 'bg-primary'
                case 'warning':
                    return 'bg-warning'
                case 'error':
                    return 'bg-danger'
                default:
                    return 'bg-secondary'
            }
        }

        const getSeverityBadgeClass = (severity) => {
            switch (severity) {
                case 'info':
                    return 'badge bg-primary'
                case 'warning':
                    return 'badge bg-warning'
                case 'error':
                    return 'badge bg-danger'
                default:
                    return 'badge bg-secondary'
            }
        }

        const getSeverityText = (severity) => {
            switch (severity) {
                case 'info':
                    return 'Info'
                case 'warning':
                    return 'Peringatan'
                case 'error':
                    return 'Error'
                default:
                    return severity
            }
        }

        const formatDateTime = (dateTime) => {
            if (!dateTime) return '-'
            return new Date(dateTime).toLocaleString('id-ID')
        }

        onMounted(() => {
            loadData()
        })

        return {
            activities,
            stats,
            filters,
            participants,
            refreshData,
            applyFilters,
            clearFilters,
            getActivityTypeText,
            getSeverityClass,
            getSeverityBadgeClass,
            getSeverityText,
            formatDateTime
        }
    }
}
</script>

<style scoped>
.icon-shape {
    width: 3rem;
    height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.icon-shape-primary {
    background: linear-gradient(87.4deg, rgb(255, 75, 75) 1.9%, rgb(255, 154, 154) 49.7%, rgb(255, 75, 75) 100.5%);
    color: white;
}

.icon-shape-success {
    background: linear-gradient(87.4deg, rgb(255, 75, 75) 1.9%, rgb(255, 154, 154) 49.7%, rgb(255, 75, 75) 100.5%);
    color: white;
}

.icon-shape-warning {
    background: linear-gradient(87.4deg, rgb(255, 75, 75) 1.9%, rgb(255, 154, 154) 49.7%, rgb(255, 75, 75) 100.5%);
    color: white;
}

.icon-shape-danger {
    background: linear-gradient(87.4deg, rgb(255, 75, 75) 1.9%, rgb(255, 154, 154) 49.7%, rgb(255, 75, 75) 100.5%);
    color: white;
}

.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0.75rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
}

.timeline-marker {
    position: absolute;
    left: -1.5rem;
    top: 0.25rem;
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 3px #e9ecef;
}

.timeline-content {
    background: #fff;
    padding: 1rem;
    border-radius: 0.5rem;
    border: 1px solid #e9ecef;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
</style> 