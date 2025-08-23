<template>
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
            <div class="d-block mb-4 mb-md-0">
                <h1 class="h3">Status Jaringan</h1>
                <p class="text-muted">Monitor koneksi jaringan peserta secara real-time</p>
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
                                <div class="icon-shape icon-shape-success rounded me-4 me-sm-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-wifi" viewBox="0 0 16 16">
                                        <path d="M15.384 6.115a.485.485 0 0 0-.047-.736A12.444 12.444 0 0 0 8 3C5.259 3 2.723 3.882.663 5.379a.485.485 0 0 0-.047.736.525.525 0 0 0 .668.05A11.448 11.448 0 0 1 8 4c2.507 0 4.827.892 6.716 2.164a.525.525 0 0 0 .668-.05z"/>
                                        <path d="M13.229 8.271a.482.482 0 0 0-.063-.745A9.455 9.455 0 0 0 8 6c-1.905 0-3.68.56-5.166 1.526a.48.48 0 0 0-.063.745.525.525 0 0 0 .652.065A8.46 8.46 0 0 1 8 7a8.46 8.46 0 0 1 4.576 1.336c.206.132.48.108.653-.065zm-2.183 2.183c.226-.226.185-.605-.1-.75A6.473 6.473 0 0 0 8 9c-1.06 0-2.062.254-2.946.704-.285.145-.326.524-.1.75l.015.015c.16.16.407.19.611.09A5.478 5.478 0 0 1 8 10c.868 0 1.69.201 2.42.56.203.1.45.07.61-.091l.016-.015zM9.06 12.44c.196-.196.198-.52-.04-.66A1.99 1.99 0 0 0 8 11.5a1.99 1.99 0 0 0-1.02.28c-.238.14-.236.464-.04.66l.706.706a.5.5 0 0 0 .708 0l.707-.707z"/>
                                    </svg>
                                </div>
                                <div class="d-sm-none">
                                    <h2 class="fw-extrabold h5">Terhubung</h2>
                                    <h3 class="mb-1">{{ stats.connected }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h6 text-gray-400 mb-0">Terhubung</h2>
                                    <h3 class="fw-extrabold mb-2">{{ stats.connected }}</h3>
                                </div>
                                <small class="d-flex align-items-center text-success">
                                    <span class="fas fa-arrow-up me-1"></span>
                                    <span>Online</span>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-wifi-off" viewBox="0 0 16 16">
                                        <path d="M10.706 3.294A12.545 12.545 0 0 0 8 3C5.259 3 2.723 3.882.663 5.379a.485.485 0 0 0-.047.736.525.525 0 0 0 .668.05A11.448 11.448 0 0 1 8 4c.63 0 1.249.05 1.882.145a.5.5 0 0 0 0-.98zM8 6a6.473 6.473 0 0 0-2.946.704a.48.48 0 0 0-.063.745.525.525 0 0 0 .652.065A8.46 8.46 0 0 1 8 7a8.46 8.46 0 0 1 4.576 1.336.206.206 0 0 0 .48.108.525.525 0 0 0 .653-.065a.48.48 0 0 0-.063-.745A6.473 6.473 0 0 0 8 6zm0 3a1.99 1.99 0 0 0-1.02.28a.5.5 0 0 0-.04.66l.706.706a.5.5 0 0 0 .708 0l.707-.707a.5.5 0 0 0-.04-.66A1.99 1.99 0 0 0 8 9zm0 2.5a.5.5 0 1 0 0 1 .5.5 0 0 0 0-1z"/>
                                        <path d="M8 1a8 0 0 1 8 8c0 .702-.09 1.383-.25 2.044l-.765-.765A7.048 7.048 0 0 0 15 9a7 7 0 0 0-7-7c-.702 0-1.383.09-2.044.25l-.765-.765A8.048 8.048 0 0 1 8 1zm0 2a6 6 0 0 0-6 6c0 .35.04.69.11 1.022l.806-.806A5.048 5.048 0 0 1 7 9c0-.35-.04-.69-.11-1.022l-.806.806A6.048 6.048 0 0 0 2 9a6 6 0 0 0 6-6z"/>
                                    </svg>
                                </div>
                                <div class="d-sm-none">
                                    <h2 class="fw-extrabold h5">Terputus</h2>
                                    <h3 class="mb-1">{{ stats.disconnected }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h6 text-gray-400 mb-0">Terputus</h2>
                                    <h3 class="fw-extrabold mb-2">{{ stats.disconnected }}</h3>
                                </div>
                                <small class="d-flex align-items-center text-danger">
                                    <span class="fas fa-arrow-down me-1"></span>
                                    <span>Offline</span>
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
                                    <h2 class="fw-extrabold h5">Instabil</h2>
                                    <h3 class="mb-1">{{ stats.unstable }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h6 text-gray-400 mb-0">Instabil</h2>
                                    <h3 class="fw-extrabold mb-2">{{ stats.unstable }}</h3>
                                </div>
                                <small class="d-flex align-items-center text-warning">
                                    <span class="fas fa-arrow-up me-1"></span>
                                    <span>Fluktuatif</span>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-speedometer2" viewBox="0 0 16 16">
                                        <path d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4zM3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 9.31a.91.91 0 1 0 1.302 1.258l3.434-4.297a.389.389 0 0 0-.029-.518z"/>
                                        <path fill-rule="evenodd" d="M0 10a8 8 0 1 1 15.547 2.661c-.442 1.253-1.845 1.602-2.932 1.25C11.309 13.488 9.475 13 8 13c-1.474 0-3.31.488-4.615.911-1.087.352-2.49.003-2.932-1.25A7.988 7.988 0 0 1 0 10zm8-7a7 7 0 0 0-6.603 9.329c.203.575.923.876 1.68.63C4.397 12.533 6.358 12 8 12s3.604.532 4.923.96c.757.245 1.477-.056 1.68-.631A7 7 0 0 0 8 3z"/>
                                    </svg>
                                </div>
                                <div class="d-sm-none">
                                    <h2 class="fw-extrabold h5">Rata-rata Latency</h2>
                                    <h3 class="mb-1">{{ stats.avgLatency }}ms</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h6 text-gray-400 mb-0">Rata-rata Latency</h2>
                                    <h3 class="fw-extrabold mb-2">{{ stats.avgLatency }}ms</h3>
                                </div>
                                <small class="d-flex align-items-center text-info">
                                    <span class="fas fa-arrow-down me-1"></span>
                                    <span>Baik</span>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Network Status Table -->
        <div class="card border-0 shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="fs-5 fw-bold mb-0">Status Jaringan Peserta</h2>
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
                            <th class="border-bottom" scope="col">Peserta</th>
                            <th class="border-bottom" scope="col">Session ID</th>
                            <th class="border-bottom" scope="col">Status</th>
                            <th class="border-bottom" scope="col">Tipe Jaringan</th>
                            <th class="border-bottom" scope="col">Latency</th>
                            <th class="border-bottom" scope="col">Signal Strength</th>
                            <th class="border-bottom" scope="col">IP Address</th>
                            <th class="border-bottom" scope="col">Last Update</th>
                            <th class="border-bottom" scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="network in networks" :key="network.id">
                            <td class="border-0">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm rounded-circle me-2">
                                        <img alt="Avatar" src="https://ui-avatars.com/api/?name={{ network.participant_id }}&background=random" />
                                    </div>
                                    <div>
                                        <span class="fw-normal">{{ network.participant_id }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="border-0">
                                <span class="fw-normal text-muted">{{ network.session_id }}</span>
                            </td>
                            <td class="border-0">
                                <span :class="getStatusBadgeClass(network.status)">
                                    {{ getStatusText(network.status) }}
                                </span>
                            </td>
                            <td class="border-0">
                                <div class="d-flex align-items-center">
                                    <svg v-if="network.network_type === 'wifi'" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-wifi me-1" viewBox="0 0 16 16">
                                        <path d="M15.384 6.115a.485.485 0 0 0-.047-.736A12.444 12.444 0 0 0 8 3C5.259 3 2.723 3.882.663 5.379a.485.485 0 0 0-.047.736.525.525 0 0 0 .668.05A11.448 11.448 0 0 1 8 4c2.507 0 4.827.892 6.716 2.164a.525.525 0 0 0 .668-.05z"/>
                                        <path d="M13.229 8.271a.482.482 0 0 0-.063-.745A9.455 9.455 0 0 0 8 6c-1.905 0-3.68.56-5.166 1.526a.48.48 0 0 0-.063.745.525.525 0 0 0 .652.065A8.46 8.46 0 0 1 8 7a8.46 8.46 0 0 1 4.576 1.336c.206.132.48.108.653-.065zm-2.183 2.183c.226-.226.185-.605-.1-.75A6.473 6.473 0 0 0 8 9c-1.06 0-2.062.254-2.946.704-.285.145-.326.524-.1.75l.015.015c.16.16.407.19.611.09A5.478 5.478 0 0 1 8 10c.868 0 1.69.201 2.42.56.203.1.45.07.61-.091l.016-.015zM9.06 12.44c.196-.196.198-.52-.04-.66A1.99 1.99 0 0 0 8 11.5a1.99 1.99 0 0 0-1.02.28c-.238.14-.236.464-.04.66l.706.706a.5.5 0 0 0 .708 0l.707-.707z"/>
                                    </svg>
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-phone me-1" viewBox="0 0 16 16">
                                        <path d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h6zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H5z"/>
                                        <path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                                    </svg>
                                    <span class="fw-normal">{{ getNetworkTypeText(network.network_type) }}</span>
                                </div>
                            </td>
                            <td class="border-0">
                                <span :class="getLatencyClass(network.latency)">
                                    {{ network.latency }}ms
                                </span>
                            </td>
                            <td class="border-0">
                                <div class="d-flex align-items-center">
                                    <div class="signal-bars me-2">
                                        <div class="signal-bar" :class="{ active: network.signal_strength >= 20 }"></div>
                                        <div class="signal-bar" :class="{ active: network.signal_strength >= 40 }"></div>
                                        <div class="signal-bar" :class="{ active: network.signal_strength >= 60 }"></div>
                                        <div class="signal-bar" :class="{ active: network.signal_strength >= 80 }"></div>
                                    </div>
                                    <span class="fw-normal">{{ network.signal_strength }}%</span>
                                </div>
                            </td>
                            <td class="border-0">
                                <span class="fw-normal text-muted">{{ network.ip_address }}</span>
                            </td>
                            <td class="border-0">
                                <span class="fw-normal">{{ formatDateTime(network.last_update) }}</span>
                            </td>
                            <td class="border-0">
                                <button class="btn btn-sm btn-outline-primary me-1" @click="viewDetails(network)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 1 0 5a2.5 2.5 0 0 1 0-5z"/>
                                    </svg>
                                </button>
                                <button class="btn btn-sm btn-outline-success" @click="testConnection(network)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-repeat" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                                        <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        <tr v-if="networks.length === 0">
                            <td colspan="9" class="text-center py-4">
                                <div class="text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-wifi-off mb-3" viewBox="0 0 16 16">
                                        <path d="M10.706 3.294A12.545 12.545 0 0 0 8 3C5.259 3 2.723 3.882.663 5.379a.485.485 0 0 0-.047.736.525.525 0 0 0 .668.05A11.448 11.448 0 0 1 8 4c.63 0 1.249.05 1.882.145a.5.5 0 0 0 0-.98zM8 6a6.473 6.473 0 0 0-2.946.704a.48.48 0 0 0-.063.745.525.525 0 0 0 .652.065A8.46 8.46 0 0 1 8 7a8.46 8.46 0 0 1 4.576 1.336.206.206 0 0 0 .48.108.525.525 0 0 0 .653-.065a.48.48 0 0 0-.063-.745A6.473 6.473 0 0 0 8 6zm0 3a1.99 1.99 0 0 0-1.02.28a.5.5 0 0 0-.04.66l.706.706a.5.5 0 0 0 .708 0l.707-.707a.5.5 0 0 0-.04-.66A1.99 1.99 0 0 0 8 9zm0 2.5a.5.5 0 1 0 0 1 .5.5 0 0 0 0-1z"/>
                                        <path d="M8 1a8 0 0 1 8 8c0 .702-.09 1.383-.25 2.044l-.765-.765A7.048 7.048 0 0 0 15 9a7 7 0 0 0-7-7c-.702 0-1.383.09-2.044.25l-.765-.765A8.048 8.048 0 0 1 8 1zm0 2a6 6 0 0 0-6 6c0 .35.04.69.11 1.022l.806-.806A5.048 5.048 0 0 1 7 9c0-.35-.04-.69-.11-1.022l-.806.806A6.048 6.048 0 0 0 2 9a6 6 0 0 0 6-6z"/>
                                    </svg>
                                    <p class="mb-0">Tidak ada data jaringan yang ditemukan</p>
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
    name: 'ProctoringNetwork',
    layout: AdminLayout,
    setup() {
        const networks = ref([])
        const stats = ref({
            connected: 0,
            disconnected: 0,
            unstable: 0,
            avgLatency: 0
        })

        const loadData = async () => {
            try {
                // Simulasi data untuk demo
                networks.value = [
                    {
                        id: 1,
                        participant_id: 'Peserta A',
                        session_id: 'SES-001',
                        status: 'connected',
                        network_type: 'wifi',
                        latency: 45,
                        signal_strength: 85,
                        ip_address: '192.168.1.100',
                        last_update: '2024-01-15 12:30:00'
                    },
                    {
                        id: 2,
                        participant_id: 'Peserta B',
                        session_id: 'SES-002',
                        status: 'disconnected',
                        network_type: 'mobile',
                        latency: 0,
                        signal_strength: 0,
                        ip_address: '10.0.0.50',
                        last_update: '2024-01-15 12:25:00'
                    },
                    {
                        id: 3,
                        participant_id: 'Peserta C',
                        session_id: 'SES-003',
                        status: 'unstable',
                        network_type: 'wifi',
                        latency: 120,
                        signal_strength: 45,
                        ip_address: '192.168.1.101',
                        last_update: '2024-01-15 12:28:00'
                    }
                ]

                // Update stats
                stats.value = {
                    connected: networks.value.filter(n => n.status === 'connected').length,
                    disconnected: networks.value.filter(n => n.status === 'disconnected').length,
                    unstable: networks.value.filter(n => n.status === 'unstable').length,
                    avgLatency: Math.round(networks.value.reduce((sum, n) => sum + n.latency, 0) / networks.value.length)
                }
            } catch (error) {
                console.error('Error loading networks:', error)
            }
        }

        const refreshData = () => {
            loadData()
        }

        const getStatusBadgeClass = (status) => {
            switch (status) {
                case 'connected':
                    return 'badge bg-success'
                case 'disconnected':
                    return 'badge bg-danger'
                case 'unstable':
                    return 'badge bg-warning'
                default:
                    return 'badge bg-secondary'
            }
        }

        const getStatusText = (status) => {
            switch (status) {
                case 'connected':
                    return 'Terhubung'
                case 'disconnected':
                    return 'Terputus'
                case 'unstable':
                    return 'Instabil'
                default:
                    return status
            }
        }

        const getNetworkTypeText = (type) => {
            switch (type) {
                case 'wifi':
                    return 'WiFi'
                case 'mobile':
                    return 'Mobile Data'
                case 'ethernet':
                    return 'Ethernet'
                default:
                    return type
            }
        }

        const getLatencyClass = (latency) => {
            if (latency <= 50) return 'text-success fw-bold'
            if (latency <= 100) return 'text-warning fw-bold'
            return 'text-danger fw-bold'
        }

        const formatDateTime = (dateTime) => {
            if (!dateTime) return '-'
            return new Date(dateTime).toLocaleString('id-ID')
        }

        const viewDetails = (network) => {
            // Implementasi untuk melihat detail jaringan
            console.log('View network details:', network)
        }

        const testConnection = (network) => {
            // Implementasi untuk test koneksi
            console.log('Test connection for:', network)
        }

        onMounted(() => {
            loadData()
        })

        return {
            networks,
            stats,
            refreshData,
            getStatusBadgeClass,
            getStatusText,
            getNetworkTypeText,
            getLatencyClass,
            formatDateTime,
            viewDetails,
            testConnection
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

.icon-shape-success {
    background: linear-gradient(87.4deg, rgb(255, 75, 75) 1.9%, rgb(255, 154, 154) 49.7%, rgb(255, 75, 75) 100.5%);
    color: white;
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

.signal-bars {
    display: flex;
    align-items: end;
    gap: 1px;
    height: 16px;
}

.signal-bar {
    width: 3px;
    background-color: #e9ecef;
    border-radius: 1px;
    transition: background-color 0.2s;
}

.signal-bar:nth-child(1) { height: 4px; }
.signal-bar:nth-child(2) { height: 8px; }
.signal-bar:nth-child(3) { height: 12px; }
.signal-bar:nth-child(4) { height: 16px; }

.signal-bar.active {
    background-color: #28a745;
}
</style> 