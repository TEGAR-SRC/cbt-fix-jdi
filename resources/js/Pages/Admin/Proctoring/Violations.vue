<template>
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
            <div class="d-block mb-4 mb-md-0">
                <h1 class="h3">Pelanggaran Proctoring</h1>
                <p class="text-muted">Monitor dan kelola pelanggaran yang terdeteksi selama ujian</p>
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
                                <div class="icon-shape icon-shape-danger rounded me-4 me-sm-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                                        <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/>
                                        <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/>
                                    </svg>
                                </div>
                                <div class="d-sm-none">
                                    <h2 class="fw-extrabold h5">Total Pelanggaran</h2>
                                    <h3 class="mb-1">{{ stats.totalViolations }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h6 text-gray-400 mb-0">Total Pelanggaran</h2>
                                    <h3 class="fw-extrabold mb-2">{{ stats.totalViolations }}</h3>
                                </div>
                                <small class="d-flex align-items-center text-danger">
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
                                <div class="icon-shape icon-shape-warning rounded me-4 me-sm-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-circle" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                                    </svg>
                                </div>
                                <div class="d-sm-none">
                                    <h2 class="fw-extrabold h5">Kritis</h2>
                                    <h3 class="mb-1">{{ stats.criticalViolations }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h6 text-gray-400 mb-0">Pelanggaran Kritis</h2>
                                    <h3 class="fw-extrabold mb-2">{{ stats.criticalViolations }}</h3>
                                </div>
                                <small class="d-flex align-items-center text-warning">
                                    <span class="fas fa-arrow-up me-1"></span>
                                    <span>Perlu tindakan</span>
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
                                <div class="icon-shape icon-shape-info rounded me-4 me-sm-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                                        <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.718 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.012.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm-7 7s-1 0-1-1 1-4 5-4a5.5 5.5 0 0 1 2.5.598 5.7 5.7 0 0 0-1.22.713C7.31 10.629 6.34 11.259 4.622 11.259 2.904 11.259 1.935 10.629 1.382 9.982.79 9.292.625 8.525.623 8.262l.008-.002.012-.002H4.978c-.001.264-.167 1.03-.76 1.72C3.688 11.371 2.718 12 1 12c-1.718 0-2.687-.63-3.24-1.276C-.834 9.034-.999 8.267-1 8.004l.008-.002.012-.002H-1.022Z"/>
                                    </svg>
                                </div>
                                <div class="d-sm-none">
                                    <h2 class="fw-extrabold h5">Peserta Terlibat</h2>
                                    <h3 class="mb-1">{{ stats.participantsInvolved }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h6 text-gray-400 mb-0">Peserta Terlibat</h2>
                                    <h3 class="fw-extrabold mb-2">{{ stats.participantsInvolved }}</h3>
                                </div>
                                <small class="d-flex align-items-center text-info">
                                    <span class="fas fa-arrow-up me-1"></span>
                                    <span>Unik</span>
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
                                    <h2 class="fw-extrabold h5">Ditangani</h2>
                                    <h3 class="mb-1">{{ stats.resolvedViolations }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h6 text-gray-400 mb-0">Sudah Ditangani</h2>
                                    <h3 class="fw-extrabold mb-2">{{ stats.resolvedViolations }}</h3>
                                </div>
                                <small class="d-flex align-items-center text-success">
                                    <span class="fas fa-arrow-up me-1"></span>
                                    <span>Hari ini</span>
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
                        <label class="form-label">Jenis Pelanggaran</label>
                        <select class="form-select" v-model="filters.violationType">
                            <option value="">Semua</option>
                            <option value="screenshot_attempt">Percobaan Screenshot</option>
                            <option value="app_switch">Pergantian Aplikasi</option>
                            <option value="network_disconnect">Putus Jaringan</option>
                            <option value="camera_blocked">Kamera Diblokir</option>
                            <option value="root_detected">Root Terdeteksi</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" v-model="filters.status">
                            <option value="">Semua</option>
                            <option value="pending">Menunggu</option>
                            <option value="investigating">Sedang Diteliti</option>
                            <option value="resolved">Selesai</option>
                            <option value="dismissed">Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" v-model="filters.startDate">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" v-model="filters.endDate">
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

        <!-- Violations Table -->
        <div class="card border-0 shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="fs-5 fw-bold mb-0">Daftar Pelanggaran</h2>
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
            <div class="table-responsive">
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th class="border-bottom" scope="col">ID</th>
                            <th class="border-bottom" scope="col">Peserta</th>
                            <th class="border-bottom" scope="col">Jenis Pelanggaran</th>
                            <th class="border-bottom" scope="col">Severity</th>
                            <th class="border-bottom" scope="col">Waktu</th>
                            <th class="border-bottom" scope="col">Status</th>
                            <th class="border-bottom" scope="col">Deskripsi</th>
                            <th class="border-bottom" scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="violation in violations" :key="violation.id">
                            <td class="border-0 fw-bold">
                                <span class="fw-normal">#{{ violation.id }}</span>
                            </td>
                            <td class="border-0">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm rounded-circle me-2">
                                        <img alt="Avatar" src="https://ui-avatars.com/api/?name={{ violation.participant_id }}&background=random" />
                                    </div>
                                    <div>
                                        <span class="fw-normal">{{ violation.participant_id }}</span>
                                        <br>
                                        <small class="text-muted">{{ violation.session_id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="border-0">
                                <span class="fw-normal">{{ getViolationTypeText(violation.violation_type) }}</span>
                            </td>
                            <td class="border-0">
                                <span :class="getSeverityBadgeClass(violation.severity)">
                                    {{ getSeverityText(violation.severity) }}
                                </span>
                            </td>
                            <td class="border-0">
                                <span class="fw-normal">{{ formatDateTime(violation.timestamp) }}</span>
                            </td>
                            <td class="border-0">
                                <span :class="getStatusBadgeClass(violation.status)">
                                    {{ getStatusText(violation.status) }}
                                </span>
                            </td>
                            <td class="border-0">
                                <span class="fw-normal text-truncate d-inline-block" style="max-width: 200px;" :title="violation.description">
                                    {{ violation.description }}
                                </span>
                            </td>
                            <td class="border-0">
                                <button class="btn btn-sm btn-outline-primary me-1" @click="viewDetails(violation)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 1 0 5a2.5 2.5 0 0 1 0-5z"/>
                                    </svg>
                                </button>
                                <button class="btn btn-sm btn-outline-success me-1" @click="resolveViolation(violation)" v-if="violation.status !== 'resolved'">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm3.857-9.809a.5.5 0 0 0-.714-.79L7.5 7.793 6.354 6.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l3.5-3.5z"/>
                                    </svg>
                                </button>
                                <button class="btn btn-sm btn-outline-warning" @click="dismissViolation(violation)" v-if="violation.status !== 'dismissed'">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        <tr v-if="violations.length === 0">
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-shield-check mb-3" viewBox="0 0 16 16">
                                        <path d="M5.338 1.59a61.44 61.44 0 0 0-2.837.856.481.481 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.725 10.725 0 0 0 2.287 2.233c.346.244.652.42.893.533.12.057.218.095.293.118a.55.55 0 0 0 .101.025.615.615 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.726 10.726 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.456 62.456 0 0 1 5.072.56z"/>
                                        <path d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                    </svg>
                                    <p class="mb-0">Tidak ada pelanggaran yang ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import AdminLayout from '@/Layouts/Admin.vue'

export default {
    name: 'ProctoringViolations',
    layout: AdminLayout,
    setup() {
        const violations = ref([])
        const stats = ref({
            totalViolations: 0,
            criticalViolations: 0,
            participantsInvolved: 0,
            resolvedViolations: 0
        })
        const filters = ref({
            violationType: '',
            status: '',
            startDate: '',
            endDate: ''
        })

        const loadData = async () => {
            try {
                // Simulasi data untuk demo
                violations.value = [
                    {
                        id: 1,
                        participant_id: 'Peserta A',
                        session_id: 'SES-001',
                        violation_type: 'screenshot_attempt',
                        severity: 'high',
                        timestamp: '2024-01-15 09:30:00',
                        status: 'pending',
                        description: 'Percobaan screenshot terdeteksi pada aplikasi CBT'
                    },
                    {
                        id: 2,
                        participant_id: 'Peserta B',
                        session_id: 'SES-002',
                        violation_type: 'app_switch',
                        severity: 'medium',
                        timestamp: '2024-01-15 10:15:00',
                        status: 'investigating',
                        description: 'Pergantian aplikasi terdeteksi selama ujian'
                    },
                    {
                        id: 3,
                        participant_id: 'Peserta C',
                        session_id: 'SES-003',
                        violation_type: 'network_disconnect',
                        severity: 'low',
                        timestamp: '2024-01-15 11:00:00',
                        status: 'resolved',
                        description: 'Koneksi jaringan terputus sementara'
                    }
                ]

                // Update stats
                stats.value = {
                    totalViolations: violations.value.length,
                    criticalViolations: violations.value.filter(v => v.severity === 'high').length,
                    participantsInvolved: new Set(violations.value.map(v => v.participant_id)).size,
                    resolvedViolations: violations.value.filter(v => v.status === 'resolved').length
                }
            } catch (error) {
                console.error('Error loading violations:', error)
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
                violationType: '',
                status: '',
                startDate: '',
                endDate: ''
            }
            loadData()
        }

        const getViolationTypeText = (type) => {
            const types = {
                'screenshot_attempt': 'Percobaan Screenshot',
                'app_switch': 'Pergantian Aplikasi',
                'network_disconnect': 'Putus Jaringan',
                'camera_blocked': 'Kamera Diblokir',
                'root_detected': 'Root Terdeteksi'
            }
            return types[type] || type
        }

        const getSeverityBadgeClass = (severity) => {
            switch (severity) {
                case 'high':
                    return 'badge bg-danger'
                case 'medium':
                    return 'badge bg-warning'
                case 'low':
                    return 'badge bg-info'
                default:
                    return 'badge bg-light text-dark'
            }
        }

        const getSeverityText = (severity) => {
            switch (severity) {
                case 'high':
                    return 'Tinggi'
                case 'medium':
                    return 'Sedang'
                case 'low':
                    return 'Rendah'
                default:
                    return severity
            }
        }

        const getStatusBadgeClass = (status) => {
            switch (status) {
                case 'pending':
                    return 'badge bg-warning'
                case 'investigating':
                    return 'badge bg-info'
                case 'resolved':
                    return 'badge bg-success'
                case 'dismissed':
                    return 'badge bg-secondary'
                default:
                    return 'badge bg-light text-dark'
            }
        }

        const getStatusText = (status) => {
            switch (status) {
                case 'pending':
                    return 'Menunggu'
                case 'investigating':
                    return 'Ditelaah'
                case 'resolved':
                    return 'Selesai'
                case 'dismissed':
                    return 'Ditolak'
                default:
                    return status
            }
        }

        const formatDateTime = (dateTime) => {
            if (!dateTime) return '-'
            return new Date(dateTime).toLocaleString('id-ID')
        }

        const viewDetails = (violation) => {
            // Implementasi untuk melihat detail pelanggaran
            console.log('View details for violation:', violation)
        }

        const resolveViolation = (violation) => {
            // Implementasi untuk menyelesaikan pelanggaran
            console.log('Resolve violation:', violation)
        }

        const dismissViolation = (violation) => {
            // Implementasi untuk menolak pelanggaran
            console.log('Dismiss violation:', violation)
        }

        onMounted(() => {
            loadData()
        })

        return {
            violations,
            stats,
            filters,
            refreshData,
            applyFilters,
            clearFilters,
            getViolationTypeText,
            getSeverityBadgeClass,
            getSeverityText,
            getStatusBadgeClass,
            getStatusText,
            formatDateTime,
            viewDetails,
            resolveViolation,
            dismissViolation
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

.icon-shape-danger {
    background: linear-gradient(87.4deg, rgb(255, 75, 75) 1.9%, rgb(255, 154, 154) 49.7%, rgb(255, 75, 75) 100.5%);
    color: white;
}

.icon-shape-warning {
    background: linear-gradient(87.4deg, rgb(255, 75, 75) 1.9%, rgb(255, 154, 154) 49.7%, rgb(255, 75, 75) 100.5%);
    color: white;
}

.icon-shape-info {
    background: linear-gradient(87.4deg, rgb(255, 75, 75) 1.9%, rgb(255, 154, 154) 49.7%, rgb(255, 75, 75) 100.5%);
    color: white;
}

.icon-shape-success {
    background: linear-gradient(87.4deg, rgb(255, 75, 75) 1.9%, rgb(255, 154, 154) 49.7%, rgb(255, 75, 75) 100.5%);
    color: white;
}
</style> 