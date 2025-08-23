<template>
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
            <div class="d-block mb-4 mb-md-0">
                <h1 class="h3">Sesi Proctoring</h1>
                <p class="text-muted">Monitor sesi proctoring aktif dan riwayat ujian</p>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-camera-video" viewBox="0 0 16 16">
                                        <path d="M0 4a2 2 0 0 1 2-2h7.5a2 2 0 0 1 1.983 1.738l3.11-1.382A1 1 0 0 1 16 5.269v7.462a1 1 0 0 1-1.406.913l-3.111-1.382A2 2 0 0 1 9.5 13H2a2 2 0 0 1-2-2V4zm4.5 1a.5.5 0 1 0 0 1 .5.5 0 0 0 0-1zm0 2a.5.5 0 1 0 0 1 .5.5 0 0 0 0-1z"/>
                                    </svg>
                                </div>
                                <div class="d-sm-none">
                                    <h2 class="fw-extrabold h5">Sesi Aktif</h2>
                                    <h3 class="mb-1">{{ stats.activeSessions }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h6 text-gray-400 mb-0">Sesi Aktif</h2>
                                    <h3 class="fw-extrabold mb-2">{{ stats.activeSessions }}</h3>
                                </div>
                                <small class="d-flex align-items-center text-success">
                                    <span class="fas fa-arrow-up me-1"></span>
                                    <span>Sedang berlangsung</span>
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
                                <div class="icon-shape icon-shape-secondary rounded me-4 me-sm-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm3.857-9.809a.5.5 0 0 0-.714-.79L7.5 7.793 6.354 6.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l3.5-3.5z"/>
                                    </svg>
                                </div>
                                <div class="d-sm-none">
                                    <h2 class="fw-extrabold h5">Selesai</h2>
                                    <h3 class="mb-1">{{ stats.completedSessions }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h6 text-gray-400 mb-0">Sesi Selesai</h2>
                                    <h3 class="fw-extrabold mb-2">{{ stats.completedSessions }}</h3>
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
                                    <h2 class="fw-extrabold h5">Pelanggaran</h2>
                                    <h3 class="mb-1">{{ stats.totalViolations }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h6 text-gray-400 mb-0">Total Pelanggaran</h2>
                                    <h3 class="fw-extrabold mb-2">{{ stats.totalViolations }}</h3>
                                </div>
                                <small class="d-flex align-items-center text-warning">
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
                                <div class="icon-shape icon-shape-info rounded me-4 me-sm-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-camera" viewBox="0 0 16 16">
                                        <path d="M15 12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h1.172a3 3 0 0 0 2.12-.879l.83-.828A1 1 0 0 1 6.827 3h2.344a1 1 0 0 1 .707.293l.828.828A3 3 0 0 0 12.828 5H14a1 1 0 0 1 1 1v6zM2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4H2z"/>
                                        <path d="M8 11a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5zm0 1a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7zM3 6.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
                                    </svg>
                                </div>
                                <div class="d-sm-none">
                                    <h2 class="fw-extrabold h5">Foto</h2>
                                    <h3 class="mb-1">{{ stats.totalPhotos }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h6 text-gray-400 mb-0">Total Foto</h2>
                                    <h3 class="fw-extrabold mb-2">{{ stats.totalPhotos }}</h3>
                                </div>
                                <small class="d-flex align-items-center text-info">
                                    <span class="fas fa-arrow-up me-1"></span>
                                    <span>Hari ini</span>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sessions Table -->
        <div class="card border-0 shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="fs-5 fw-bold mb-0">Daftar Sesi Proctoring</h2>
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
                            <th class="border-bottom" scope="col">Session ID</th>
                            <th class="border-bottom" scope="col">Peserta</th>
                            <th class="border-bottom" scope="col">Device ID</th>
                            <th class="border-bottom" scope="col">Status</th>
                            <th class="border-bottom" scope="col">Mulai</th>
                            <th class="border-bottom" scope="col">Selesai</th>
                            <th class="border-bottom" scope="col">Durasi</th>
                            <th class="border-bottom" scope="col">Pelanggaran</th>
                            <th class="border-bottom" scope="col">Foto</th>
                            <th class="border-bottom" scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="session in sessions" :key="session.id">
                            <td class="border-0 fw-bold">
                                <span class="fw-normal">{{ session.session_id }}</span>
                            </td>
                            <td class="border-0">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm rounded-circle me-2">
                                        <img alt="Avatar" src="https://ui-avatars.com/api/?name={{ session.participant_id }}&background=random" />
                                    </div>
                                    <div>
                                        <span class="fw-normal">{{ session.participant_id }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="border-0">
                                <span class="fw-normal text-muted">{{ session.device_id }}</span>
                            </td>
                            <td class="border-0">
                                <span :class="getStatusBadgeClass(session.status)">
                                    {{ getStatusText(session.status) }}
                                </span>
                            </td>
                            <td class="border-0">
                                <span class="fw-normal">{{ formatDateTime(session.started_at) }}</span>
                            </td>
                            <td class="border-0">
                                <span class="fw-normal">{{ session.ended_at ? formatDateTime(session.ended_at) : '-' }}</span>
                            </td>
                            <td class="border-0">
                                <span class="fw-normal">{{ calculateDuration(session.started_at, session.ended_at) }}</span>
                            </td>
                            <td class="border-0">
                                <span class="fw-normal">
                                    <span class="badge bg-warning">{{ session.violation_count }}</span>
                                </span>
                            </td>
                            <td class="border-0">
                                <span class="fw-normal">
                                    <span class="badge bg-info">{{ session.photo_count }}</span>
                                </span>
                            </td>
                            <td class="border-0">
                                <button class="btn btn-sm btn-outline-primary me-1" @click="viewDetails(session)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 1 0 5a2.5 2.5 0 0 1 0-5z"/>
                                    </svg>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" @click="endSession(session)" v-if="session.status === 'active'">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-stop-circle" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="M6.5 5A1.5 1.5 0 0 1 8 6.5v3A1.5 1.5 0 0 1 6.5 11h-1A1.5 1.5 0 0 1 4 9.5v-3A1.5 1.5 0 0 1 5.5 5h1z"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        <tr v-if="sessions.length === 0">
                            <td colspan="10" class="text-center py-4">
                                <div class="text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-inbox mb-3" viewBox="0 0 16 16">
                                        <path d="M4.98 4a.5.5 0 0 0-.39.188L1.54 8H6a.5.5 0 0 1 .5.5 1.5 1.5 0 1 0 3 0A.5.5 0 0 1 10 8h4.46l-3.05-3.812A.5.5 0 0 0 11.02 4H4.98zm9.54 7H13.81a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 7.81 11H1.52a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5z"/>
                                    </svg>
                                    <p class="mb-0">Belum ada sesi proctoring</p>
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
    name: 'ProctoringSessions',
    layout: AdminLayout,
    setup() {
        const sessions = ref([])
        const stats = ref({
            activeSessions: 0,
            completedSessions: 0,
            totalViolations: 0,
            totalPhotos: 0
        })

        const loadData = async () => {
            try {
                // Simulasi data untuk demo
                sessions.value = [
                    {
                        id: 1,
                        session_id: 'SES-001',
                        participant_id: 'Peserta A',
                        device_id: 'DEV-001',
                        status: 'active',
                        started_at: '2024-01-15 09:00:00',
                        ended_at: null,
                        violation_count: 2,
                        photo_count: 15
                    },
                    {
                        id: 2,
                        session_id: 'SES-002',
                        participant_id: 'Peserta B',
                        device_id: 'DEV-002',
                        status: 'completed',
                        started_at: '2024-01-15 08:30:00',
                        ended_at: '2024-01-15 10:30:00',
                        violation_count: 0,
                        photo_count: 20
                    }
                ]

                // Update stats
                stats.value = {
                    activeSessions: sessions.value.filter(s => s.status === 'active').length,
                    completedSessions: sessions.value.filter(s => s.status === 'completed').length,
                    totalViolations: sessions.value.reduce((sum, s) => sum + s.violation_count, 0),
                    totalPhotos: sessions.value.reduce((sum, s) => sum + s.photo_count, 0)
                }
            } catch (error) {
                console.error('Error loading sessions:', error)
            }
        }

        const refreshData = () => {
            loadData()
        }

        const getStatusBadgeClass = (status) => {
            switch (status) {
                case 'active':
                    return 'badge bg-success'
                case 'completed':
                    return 'badge bg-secondary'
                case 'terminated':
                    return 'badge bg-danger'
                default:
                    return 'badge bg-light text-dark'
            }
        }

        const getStatusText = (status) => {
            switch (status) {
                case 'active':
                    return 'Aktif'
                case 'completed':
                    return 'Selesai'
                case 'terminated':
                    return 'Dihentikan'
                default:
                    return status
            }
        }

        const formatDateTime = (dateTime) => {
            if (!dateTime) return '-'
            return new Date(dateTime).toLocaleString('id-ID')
        }

        const calculateDuration = (start, end) => {
            if (!start) return '-'
            const startTime = new Date(start)
            const endTime = end ? new Date(end) : new Date()
            const diff = endTime - startTime
            const hours = Math.floor(diff / (1000 * 60 * 60))
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60))
            return `${hours}j ${minutes}m`
        }

        const viewDetails = (session) => {
            // Implementasi untuk melihat detail sesi
            console.log('View details for session:', session)
        }

        const endSession = (session) => {
            // Implementasi untuk mengakhiri sesi
            console.log('End session:', session)
        }

        onMounted(() => {
            loadData()
        })

        return {
            sessions,
            stats,
            refreshData,
            getStatusBadgeClass,
            getStatusText,
            formatDateTime,
            calculateDuration,
            viewDetails,
            endSession
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

.icon-shape-secondary {
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
</style> 