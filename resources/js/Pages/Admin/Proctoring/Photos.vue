<template>
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
            <div class="d-block mb-4 mb-md-0">
                <h1 class="h3">Foto Proctoring</h1>
                <p class="text-muted">Monitor dan kelola foto peserta yang diambil selama ujian</p>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-camera" viewBox="0 0 16 16">
                                        <path d="M15 12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h1.172a3 3 0 0 0 2.12-.879l.83-.828A1 1 0 0 1 6.827 3h2.344a1 1 0 0 1 .707.293l.828.828A3 3 0 0 0 12.828 5H14a1 1 0 0 1 1 1v6zM2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4H2z"/>
                                        <path d="M8 11a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5zm0 1a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7zM3 6.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
                                    </svg>
                                </div>
                                <div class="d-sm-none">
                                    <h2 class="fw-extrabold h5">Total Foto</h2>
                                    <h3 class="mb-1">{{ stats.totalPhotos }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h6 text-gray-400 mb-0">Total Foto</h2>
                                    <h3 class="fw-extrabold mb-2">{{ stats.totalPhotos }}</h3>
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
                                    <h3 class="mb-1">{{ stats.normalPhotos }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h6 text-gray-400 mb-0">Foto Normal</h2>
                                    <h3 class="fw-extrabold mb-2">{{ stats.normalPhotos }}</h3>
                                </div>
                                <small class="d-flex align-items-center text-success">
                                    <span class="fas fa-arrow-up me-1"></span>
                                    <span>Peserta hadir</span>
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
                                    <h2 class="fw-extrabold h5">Terdeteksi</h2>
                                    <h3 class="mb-1">{{ stats.flaggedPhotos }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h6 text-gray-400 mb-0">Foto Terdeteksi</h2>
                                    <h3 class="fw-extrabold mb-2">{{ stats.flaggedPhotos }}</h3>
                                </div>
                                <small class="d-flex align-items-center text-warning">
                                    <span class="fas fa-arrow-up me-1"></span>
                                    <span>Perlu review</span>
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
                                    <h2 class="fw-extrabold h5">Peserta</h2>
                                    <h3 class="mb-1">{{ stats.participants }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h6 text-gray-400 mb-0">Peserta Aktif</h2>
                                    <h3 class="fw-extrabold mb-2">{{ stats.participants }}</h3>
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
        </div>

        <!-- Filters -->
        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Peserta</label>
                        <select class="form-select" v-model="filters.participant">
                            <option value="">Semua Peserta</option>
                            <option v-for="participant in participants" :key="participant" :value="participant">
                                {{ participant }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" v-model="filters.status">
                            <option value="">Semua</option>
                            <option value="normal">Normal</option>
                            <option value="flagged">Terdeteksi</option>
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

        <!-- View Toggle -->
        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Galeri Foto</h5>
                        <small class="text-muted">Pilih tampilan yang diinginkan</small>
                    </div>
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="viewMode" id="gridView" value="grid" v-model="viewMode" autocomplete="off">
                        <label class="btn btn-outline-primary" for="gridView">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-grid-3x3-gap" viewBox="0 0 16 16">
                                <path d="M4 2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h2a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-2v2a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2V2zM6 0a1 1 0 0 0-1 1v2H2a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h3v2a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-2h3a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1h-3V1a1 1 0 0 0-1-1H6z"/>
                            </svg>
                            Grid
                        </label>
                        <input type="radio" class="btn-check" name="viewMode" id="listView" value="list" v-model="viewMode" autocomplete="off">
                        <label class="btn btn-outline-primary" for="listView">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                            </svg>
                            List
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid View -->
        <div v-if="viewMode === 'grid'" class="row">
            <div v-for="photo in photos" :key="photo.id" class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="card border-0 shadow h-100">
                    <div class="position-relative">
                        <img :src="photo.photo_url" class="card-img-top" :alt="photo.participant_id" style="height: 200px; object-fit: cover;">
                        <div class="position-absolute top-0 end-0 m-2">
                            <span :class="getStatusBadgeClass(photo.status)">
                                {{ getStatusText(photo.status) }}
                            </span>
                        </div>
                        <div class="position-absolute bottom-0 start-0 w-100 p-2" style="background: rgba(0,0,0,0.7);">
                            <small class="text-white">{{ formatDateTime(photo.captured_at) }}</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="card-title">{{ photo.participant_id }}</h6>
                        <p class="card-text small text-muted">{{ photo.session_id }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">{{ formatFileSize(photo.file_size) }}</small>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" @click="viewPhoto(photo)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 1 0 5a2.5 2.5 0 0 1 0-5z"/>
                                    </svg>
                                </button>
                                <button class="btn btn-outline-success" @click="downloadPhoto(photo)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                        <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- List View -->
        <div v-else class="card border-0 shadow">
            <div class="table-responsive">
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th class="border-bottom" scope="col">Foto</th>
                            <th class="border-bottom" scope="col">Peserta</th>
                            <th class="border-bottom" scope="col">Session ID</th>
                            <th class="border-bottom" scope="col">Waktu</th>
                            <th class="border-bottom" scope="col">Status</th>
                            <th class="border-bottom" scope="col">Ukuran</th>
                            <th class="border-bottom" scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="photo in photos" :key="photo.id">
                            <td class="border-0">
                                <img :src="photo.photo_url" class="rounded" style="width: 60px; height: 60px; object-fit: cover;" :alt="photo.participant_id">
                            </td>
                            <td class="border-0">
                                <span class="fw-normal">{{ photo.participant_id }}</span>
                            </td>
                            <td class="border-0">
                                <span class="fw-normal text-muted">{{ photo.session_id }}</span>
                            </td>
                            <td class="border-0">
                                <span class="fw-normal">{{ formatDateTime(photo.captured_at) }}</span>
                            </td>
                            <td class="border-0">
                                <span :class="getStatusBadgeClass(photo.status)">
                                    {{ getStatusText(photo.status) }}
                                </span>
                            </td>
                            <td class="border-0">
                                <span class="fw-normal text-muted">{{ formatFileSize(photo.file_size) }}</span>
                            </td>
                            <td class="border-0">
                                <button class="btn btn-sm btn-outline-primary me-1" @click="viewPhoto(photo)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 1 0 5a2.5 2.5 0 0 1 0-5z"/>
                                    </svg>
                                </button>
                                <button class="btn btn-sm btn-outline-success" @click="downloadPhoto(photo)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                        <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        <tr v-if="photos.length === 0">
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-images mb-3" viewBox="0 0 16 16">
                                        <path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                                        <path d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2zm-10-1a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v7z"/>
                                    </svg>
                                    <p class="mb-0">Tidak ada foto yang ditemukan</p>
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
    name: 'ProctoringPhotos',
    layout: AdminLayout,
    setup() {
        const photos = ref([])
        const stats = ref({
            totalPhotos: 0,
            normalPhotos: 0,
            flaggedPhotos: 0,
            participants: 0
        })
        const filters = ref({
            participant: '',
            status: '',
            startDate: '',
            endDate: ''
        })
        const viewMode = ref('grid')
        const participants = ref([])

        const loadData = async () => {
            try {
                // Simulasi data untuk demo
                photos.value = [
                    {
                        id: 1,
                        participant_id: 'Peserta A',
                        session_id: 'SES-001',
                        photo_url: 'https://via.placeholder.com/300x200/FF6B6B/FFFFFF?text=Peserta+A',
                        captured_at: '2024-01-15 09:30:00',
                        status: 'normal',
                        file_size: 245760,
                        mime_type: 'image/jpeg'
                    },
                    {
                        id: 2,
                        participant_id: 'Peserta B',
                        session_id: 'SES-002',
                        photo_url: 'https://via.placeholder.com/300x200/4ECDC4/FFFFFF?text=Peserta+B',
                        captured_at: '2024-01-15 10:15:00',
                        status: 'flagged',
                        file_size: 198432,
                        mime_type: 'image/jpeg'
                    },
                    {
                        id: 3,
                        participant_id: 'Peserta C',
                        session_id: 'SES-003',
                        photo_url: 'https://via.placeholder.com/300x200/45B7D1/FFFFFF?text=Peserta+C',
                        captured_at: '2024-01-15 11:00:00',
                        status: 'normal',
                        file_size: 312456,
                        mime_type: 'image/jpeg'
                    }
                ]

                // Update stats
                stats.value = {
                    totalPhotos: photos.value.length,
                    normalPhotos: photos.value.filter(p => p.status === 'normal').length,
                    flaggedPhotos: photos.value.filter(p => p.status === 'flagged').length,
                    participants: new Set(photos.value.map(p => p.participant_id)).size
                }

                // Update participants list
                participants.value = [...new Set(photos.value.map(p => p.participant_id))]
            } catch (error) {
                console.error('Error loading photos:', error)
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
                participant: '',
                status: '',
                startDate: '',
                endDate: ''
            }
            loadData()
        }

        const getStatusBadgeClass = (status) => {
            switch (status) {
                case 'normal':
                    return 'badge bg-success'
                case 'flagged':
                    return 'badge bg-warning'
                default:
                    return 'badge bg-light text-dark'
            }
        }

        const getStatusText = (status) => {
            switch (status) {
                case 'normal':
                    return 'Normal'
                case 'flagged':
                    return 'Terdeteksi'
                default:
                    return status
            }
        }

        const formatDateTime = (dateTime) => {
            if (!dateTime) return '-'
            return new Date(dateTime).toLocaleString('id-ID')
        }

        const formatFileSize = (bytes) => {
            if (bytes === 0) return '0 Bytes'
            const k = 1024
            const sizes = ['Bytes', 'KB', 'MB', 'GB']
            const i = Math.floor(Math.log(bytes) / Math.log(k))
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
        }

        const viewPhoto = (photo) => {
            // Implementasi untuk melihat foto dalam modal
            console.log('View photo:', photo)
        }

        const downloadPhoto = (photo) => {
            // Implementasi untuk download foto
            console.log('Download photo:', photo)
        }

        onMounted(() => {
            loadData()
        })

        return {
            photos,
            stats,
            filters,
            viewMode,
            participants,
            refreshData,
            applyFilters,
            clearFilters,
            getStatusBadgeClass,
            getStatusText,
            formatDateTime,
            formatFileSize,
            viewPhoto,
            downloadPhoto
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

.icon-shape-info {
    background: linear-gradient(87.4deg, rgb(255, 75, 75) 1.9%, rgb(255, 154, 154) 49.7%, rgb(255, 75, 75) 100.5%);
    color: white;
}
</style> 