<template>
    <Head><title>Dashboard - Aplikasi Ujian Online</title></Head>
    <div class="container-fluid mb-5 mt-4 dashboard-new">
        <div class="row g-3 mb-3">
            <div class="col-auto">
                <h5 class="fw-semibold mb-0">Selamat datang, Administrator!</h5>
                <small class="text-muted">Dashboard Administrator - CBT AI</small>
            </div>
        </div>
        <!-- Top Stats -->
    <div class="row g-3 mb-2">
            <div class="col-6 col-xl-3" v-for="card in topStats" :key="card.key">
                <div class="card stat-card border-0 shadow-sm h-100">
                    <div class="card-body d-flex">
                        <div class="icon-box rounded me-3 d-flex align-items-center justify-content-center" :class="'bg-soft-'+card.color">
                            <span v-html="card.icon"></span>
                        </div>
                        <div class="flex-grow-1">
                            <span class="label small text-muted text-uppercase">{{ card.label }}</span>
                            <div class="h4 fw-bold mb-0 mt-1">{{ card.value }}</div>
                            <div class="trend text-success small" v-if="card.trend">{{ card.trend }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-12"><span class="small fw-semibold text-muted text-uppercase">Aksi Cepat</span></div>
            <div v-for="action in quickActions" :key="action.key" class="col-6 col-md-4 col-xl-3">
                    <Link :href="action.href" class="text-decoration-none">
                        <div class="action-tile h-100 p-3 d-flex gap-3 rounded shadow-sm border-0">
                            <div class="tile-icon d-flex align-items-center justify-content-center rounded" :class="'bg-soft-'+action.color">
                                <span v-html="action.icon"></span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold small mb-1 text-dark">{{ action.title }}</div>
                                <div class="text-muted tiny lh-sm">{{ action.desc }}</div>
                            </div>
                        </div>
                    </Link>
            </div>
        </div>
        <div class="row g-3 align-items-stretch mb-3">
            <div class="col-12">
                <div class="card border-0 shadow h-100">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Aktivitas 7 Hari</h6>
                        <small class="text-muted">Ujian & Tryout Selesai</small>
                    </div>
                    <div class="card-body"><canvas id="examActivityChart" height="140"></canvas></div>
                </div>
            </div>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-xl-6">
                <div class="card border-0 shadow h-100">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Aktivitas Terbaru</h6>
                        <small class="text-muted">Log Sistem</small>
                    </div>
                    <div class="card-body pt-2 pb-0" style="max-height:350px;overflow:auto;">
                        <div v-if="paginatedRecentActivity.length" class="list-group list-group-flush small">
                            <div v-for="item in paginatedRecentActivity" :key="item.title+item.time" class="list-group-item d-flex align-items-start gap-2 px-0 py-2 border-0 border-top">
                                <div class="activity-dot mt-1" :class="'bg-'+(item.type==='session'?'warning':'primary')"></div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold text-dark">{{ item.title }}</div>
                                    <div class="text-muted tiny">{{ item.time }}</div>
                                </div>
                                <span class="badge bg-light text-dark border">{{ item.status }}</span>
                            </div>
                        </div>
                        <div v-else class="text-muted text-center py-4 tiny">Belum ada aktivitas.</div>
                    </div>
                    <div class="card-footer bg-white border-0 pt-2 pb-3" v-if="totalRecentPages>1">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="tiny text-muted">Halaman {{ recentPage }} / {{ totalRecentPages }}</div>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item" :class="{disabled: recentPage===1}"><button class="page-link" @click="recentPrev">«</button></li>
                                <li v-for="p in totalRecentPages" :key="'rpg'+p" class="page-item" :class="{active: p===recentPage}"><button class="page-link" @click="goRecent(p)">{{ p }}</button></li>
                                <li class="page-item" :class="{disabled: recentPage===totalRecentPages}"><button class="page-link" @click="recentNext">»</button></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="card border-0 shadow h-100">
                            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Agenda Sesi Mendatang</h6>
                                <small class="text-muted">5 Terdekat</small>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm mb-0 align-middle tiny">
                                    <thead class="table-light"><tr><th>Judul</th><th>Mapel</th><th>Status</th><th>Durasi</th><th>Peserta</th></tr></thead>
                                    <tbody>
                                        <tr v-for="s in upcoming_exams" :key="s.id">
                                            <td class="text-truncate" style="max-width:140px;">{{ s.exam.title }}</td>
                                            <td class="text-truncate" style="max-width:120px;">{{ s.exam.lesson }}</td>
                                            <td><span class="badge" :class="{'bg-success': s.status==='Active','bg-secondary': s.status==='Ended','bg-warning text-dark': s.status==='Upcoming'}">{{ s.status }}</span></td>
                                            <td>{{ s.duration_min }}m</td>
                                            <td>{{ s.participants }}</td>
                                        </tr>
                                        <tr v-if="!upcoming_exams?.length"><td colspan="5" class="text-center text-muted py-3">Tidak ada data</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card border-0 shadow h-100">
                            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Status Sesi</h6>
                                <small class="text-muted">Aktif vs Selesai</small>
                            </div>
                            <div class="card-body"><canvas id="sessionStatusChart" height="150"></canvas></div>
                        </div>
                    </div>
                    <div class="col-12" v-if="liveSystemStatus">
                        <div class="card border-0 shadow h-100">
                            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Status Sistem</h6>
                                <span class="badge" :class="liveSystemStatus.database ? 'bg-success' : 'bg-danger'">DB</span>
                            </div>
                            <div class="card-body small">
                                <div class="d-flex justify-content-between mb-2"><span>Database</span><span :class="liveSystemStatus.database?'text-success':'text-danger'">{{ liveSystemStatus.database? 'OK':'Gangguan' }}</span></div>
                                <div class="d-flex justify-content-between mb-2"><span>Websocket</span><span :class="liveSystemStatus.websocket?'text-success':'text-warning'">{{ liveSystemStatus.websocket? 'Online':'Offline' }}</span></div>
                                <div class="d-flex justify-content-between mb-2"><span>Anti Cheat</span><span :class="liveSystemStatus.anti_cheat?'text-success':'text-danger'">{{ liveSystemStatus.anti_cheat? 'Aktif':'Nonaktif' }}</span></div>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between"><span>Storage</span><span>{{ (liveSystemStatus.storage_used*100).toFixed(1) }}%</span></div>
                                    <div class="progress" style="height:6px;">
                                        <div class="progress-bar bg-info" :style="{width:(liveSystemStatus.storage_used*100)+'%'}"></div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mb-1"><span>Backup Terakhir</span><span>{{ liveSystemStatus.last_backup }}</span></div>
                                <div class="d-flex justify-content-between mb-1"><span>Antrian Pending</span><span>{{ liveSystemStatus.queue.pending }}</span></div>
                                <div class="d-flex justify-content-between"><span>Antrian Gagal</span><span>{{ liveSystemStatus.queue.failed }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LayoutAdmin from '../../../Layouts/Admin.vue';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref, nextTick, computed } from 'vue';
import {
    Chart,
    LineController,
    LineElement,
    PointElement,
    BarElement,
    CategoryScale,
    LinearScale,
    Filler,
    Tooltip,
    Legend,
    ArcElement
} from 'chart.js';

Chart.register(LineController, LineElement, PointElement, BarElement, CategoryScale, LinearScale, Filler, Tooltip, Legend, ArcElement);

export default {
    layout: LayoutAdmin,
    components: { Head, Link },
    props: {
        students: Number,
        exams: Number,
        exam_sessions: Number,
        classrooms: Number,
        assignments: Number,
        tryouts: Number,
        questions: Number,
        active_sessions: Number,
        ended_sessions_today: Number,
        session_status: Object,
        exam_activity: Array,
        upcoming_exams: Array,
        system_status: Object,
        recent_activity: Array,
    },
    setup(props) {
        const topStats = [
            { key:'exams', label:'Total Ujian', value: props.exams, color:'primary', trend:'+12% vs bln lalu', icon:'<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pencil-square"><path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293z"/><path d="M13.459 4.69 5 13.146V15h1.854L15.459 5.854l-2-2z"/><path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h9a.5.5 0 0 0 0-1h-9a.5.5 0 0 1-.5-.5v-9A.5.5 0 0 1 2.5 4H8a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 4.5v9z"/></svg>' },
            { key:'students', label:'Pengguna Aktif', value: props.students, color:'success', trend:'+5% vs bln lalu', icon:'<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-people-fill"><path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/><path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/><path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/></svg>' },
            { key:'questions', label:'Soal', value: props.questions, color:'warning', trend:'+15% bln ini', icon:'<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-collection"><path d="M2.5 3a.5.5 0 0 0-.5.5v8a.5.5 0 0 0 .5.5h11a.5.5 0 0 0 .5-.5v-8a.5.5 0 0 0-.5-.5h-11z"/><path d="M4.5 0A.5.5 0 0 0 4 .5V2h1V.5A.5.5 0 0 0 4.5 0zm2 0A.5.5 0 0 0 6 .5V2h1V.5A.5.5 0 0 0 6.5 0zm2 0a.5.5 0 0 0-.5.5V2h1V.5a.5.5 0 0 0-.5-.5z"/></svg>' },
            { key:'health', label:'Kesehatan Sistem', value: props.system_status ? (100 - Math.round((props.system_status.storage_used||0)*100))+'%' : '—', color:'info', trend:'Semua layanan OK', icon:'<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-activity"><path fill-rule="evenodd" d="M6 2a.5.5 0 0 1 .47.33L8.667 8.5l1.2-3a.5.5 0 0 1 .933.002L12.5 9H15a.5.5 0 0 1 0 1h-2.8a.5.5 0 0 1-.467-.324L10.8 7.5l-1.2 3a.5.5 0 0 1-.934 0L6.53 3.67 5.2 7H1a.5.5 0 0 1 0-1h3.6a.5.5 0 0 1 .467.324L6 2z"/></svg>' },
            { key:'active_sessions', label:'Sesi Aktif', value: props.active_sessions, color:'secondary', trend:'Real-time', icon:'<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-wifi"><path d="M12.02 11.293a1 1 0 0 1 1.415 1.414 4 4 0 0 0-5.657 0 1 1 0 0 1-1.415-1.414 6 6 0 0 1 8.485 0Z"/><path d="M8.464 8.464a1 1 0 0 1 1.414 1.415 2 2 0 0 0-2.828 0 1 1 0 0 1-1.414-1.415 4 4 0 0 1 5.656 0Z"/><path d="M5.636 5.636a1 1 0 0 1 1.415 1.415 6 6 0 0 0-8.486 0 1 1 0 0 1-1.414-1.415 8 8 0 0 1 11.314 0Z"/><path d="M8 15a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/></svg>' },
            { key:'ended_today', label:'Selesai Hari Ini', value: props.ended_sessions_today, color:'dark', trend:'Hari ini', icon:'<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-stopwatch"><path d="M6.5 1a.5.5 0 0 0 0 1H7v1.07A7.001 7.001 0 0 0 8 16a7 7 0 1 0 1-13.93V2h.5a.5.5 0 0 0 0-1h-3Zm2.5 7a.5.5 0 0 1-.5.5H5.707l-1.147 1.146a.5.5 0 1 1-.707-.707L5.293 7.5 3.853 6.06a.5.5 0 1 1 .707-.707L6 6.793V4.5a.5.5 0 0 1 1 0v3Z"/></svg>' },
            { key:'storage', label:'Penggunaan Storage', value: props.system_status ? Math.round((props.system_status.storage_used||0)*100)+'%' : '—', color:'warning', trend:'Kapasitas', icon:'<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-hdd-stack"><path d="M4 0h8a2 2 0 0 1 2 2v3H2V2a2 2 0 0 1 2-2Z"/><path d="M2 7h12v3a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7Z"/><path d="M5 2.5a.5.5 0 1 0 0 1 .5.5.5.5 0 0 0 0-1Zm0 7a.5.5 0 1 0 0 1 .5.5.5.5 0 0 0 0-1Z"/></svg>' },
        ];
        const quickActions = [
            { key:'exams', title:'Buat Ujian', desc:'Buat ujian baru', href:'/admin/exams', color:'primary', icon:'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square"><path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293z"/><path d="M13.459 4.69 5 13.146V15h1.854L15.459 5.854l-2-2z"/><path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h9a.5.5 0 0 0 0-1h-9a.5.5 0 0 1-.5-.5v-9A.5.5 0 0 1 2.5 4H8a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 4.5v9z"/></svg>' },
            { key:'import', title:'Import Questions', desc:'Import soal dari Excel', href:'/admin/assignments', color:'success', icon:'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-upload"><path d="M.5 9.9a.5.5 0 0 1 .5.5v2.1a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.1a.5.5 0 0 1 1 0v2.1A2 2 0 0 1 14 14.5H2A2 2 0 0 1 0 12.5v-2.1a.5.5 0 0 1 .5-.5z"/><path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V10.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/></svg>' },
            { key:'ai-import', title:'AI Import', desc:'Generate / import soal AI', href:'/admin/ai-import', color:'info', icon:'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cpu"><path d="M5 0a.5.5 0 0 0-.5.5V2H3.5A1.5 1.5 0 0 0 2 3.5V5H.5a.5.5 0 0 0 0 1H2v2H.5a.5.5 0 0 0 0 1H2v2H.5a.5.5 0 0 0 0 1H2v1.5A1.5 1.5 0 0 0 3.5 16H5v1.5a.5.5 0 0 0 1 0V16h2v1.5a.5.5 0 0 0 1 0V16h2v1.5a.5.5 0 0 0 1 0V16h1.5a1.5 1.5 0 0 0 1.5-1.5V13h1.5a.5.5 0 0 0 0-1H16v-2h1.5a.5.5 0 0 0 0-1H16V7h1.5a.5.5 0 0 0 0-1H16V3.5A1.5 1.5 0 0 0 14.5 2H13V.5a.5.5 0 0 0-1 0V2h-2V.5a.5.5 0 0 0-1 0V2H7V.5A.5.5 0 0 0 6 .5V2H5V.5A.5.5 0 0 0 5 0Zm1 5.5A1.5 1.5 0 0 1 7.5 4h1A1.5 1.5 0 0 1 10 5.5v1a1.5 1.5 0 0 1-1.5 1.5h-1A1.5 1.5 0 0 1 6 6.5v-1Z"/></svg>' },
            { key:'export', title:'Export Results', desc:'Export hasil ujian', href:'/admin/results', color:'warning', icon:'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down"><path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z"/><path d="M14 14V4.5L9.5 0h-5A1.5 1.5 0 0 0 3 1.5v13A1.5 1.5 0 0 0 4.5 16h7a1.5 1.5 0 0 0 1.5-1.5zM9.5 3A1.5 1.5 0 0 1 11 4.5V5H9.5V3z"/></svg>' },
            { key:'users', title:'Manage Users', desc:'Kelola pengguna', href:'/admin/admins', color:'secondary', icon:'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people"><path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Z"/><path d="M11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm-7 7s-1 0-1-1 1-4 5-4a5.5 5.5 0 0 1 2.5.598 5.5 5.5 0 0 0-2.5-.598c-4 0-5 3-5 4s1 1 1 1h8Z"/><path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/></svg>' },
            { key:'classes', title:'Manage Classes', desc:'Kelola kelas', href:'/admin/classrooms', color:'success', icon:'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-collection"><path d="M2.5 3a.5.5 0 0 0-.5.5v8a.5.5 0 0 0 .5.5h11a.5.5 0 0 0 .5-.5v-8a.5.5 0 0 0-.5-.5h-11z"/><path d="M4.5 0A.5.5 0 0 0 4 .5V2h1V.5A.5.5 0 0 0 4.5 0zm2 0A.5.5 0 0 0 6 .5V2h1V.5A.5.5 0 0 0 6.5 0zm2 0a.5.5 0 0 0-.5.5V2h1V.5a.5.5 0 0 0-.5-.5z"/></svg>' },
            { key:'questions-bank', title:'Question Bank', desc:'Bank soal', href:'/admin/questions', color:'dark', icon:'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-journal-text"><path d="M5 8h6v1H5V8Zm0 2h4v1H5v-1Z"/><path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2H0V2a2 2 0 0 1 2-2h1Zm0 1v14h10V1H3Z"/></svg>' },
            { key:'proctoring', title:'Proctoring', desc:'Pantau live', href:'/admin/proctoring', color:'primary', icon:'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8Z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5Z"/></svg>' },
            { key:'reports', title:'View Reports', desc:'Lihat analitik', href:'/admin/reports', color:'info', icon:'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-graph-up-arrow"><path fill-rule="evenodd" d="M0 0h1v15h15v1H0V0Zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.9l-3.613 4.417a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61L13.445 4H10.5a.5.5 0 0 1-.5-.5Z"/></svg>' },
            { key:'settings', title:'System Settings', desc:'Konfigurasi', href:'/admin/settings', color:'warning', icon:'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear"><path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/><path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319z"/></svg>' },
        ];
    // Recent activity pagination (client-side)
    const recentPage = ref(1);
    const recentPageSize = 8;
    const totalRecentPages = computed(() => Math.max(1, Math.ceil((props.recent_activity?.length || 0) / recentPageSize)));
    const paginatedRecentActivity = computed(() => (props.recent_activity || []).slice((recentPage.value - 1) * recentPageSize, recentPage.value * recentPageSize));
    function recentNext(){ if(recentPage.value < totalRecentPages.value) recentPage.value++; }
    function recentPrev(){ if(recentPage.value > 1) recentPage.value--; }
    function goRecent(p){ if(p>=1 && p<= totalRecentPages.value) recentPage.value = p; }
        let sessionChartMade = false;
        const liveSystemStatus = ref(props.system_status || null);
        async function refreshSystemStatus(){
            try {
                const res = await fetch('/admin/system-status', { headers: { 'Accept':'application/json' } });
                if(res.ok){
                    liveSystemStatus.value = await res.json();
                }
            } catch(e){ /* silent */ }
        }
    onMounted(() => {
            // Line chart for exam activity
            const days = props.exam_activity?.length ? props.exam_activity.map(d => d.date) : generateLast7Days();
            const examCounts = props.exam_activity?.length ? props.exam_activity.map(d => d.exams) : placeholderRandom(7, 5, 25);
            const tryoutCounts = props.exam_activity?.length ? props.exam_activity.map(d => d.tryouts) : placeholderRandom(7, 2, 15);

            new Chart(document.getElementById('examActivityChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: days,
                    datasets: [
                        {
                            label: 'Ujian Selesai',
                            data: examCounts,
                            borderColor: '#0d6efd',
                            backgroundColor: 'rgba(13,110,253,.15)',
                            tension: .35,
                            fill: true,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                        },
                        {
                            label: 'Tryout Selesai',
                            data: tryoutCounts,
                            borderColor: '#20c997',
                            backgroundColor: 'rgba(32,201,151,.15)',
                            tension: .35,
                            fill: true,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } },
                    scales: {
                        y: { beginAtZero: true, ticks: { precision: 0 } }
                    }
                }
            });

            initSessionChart();
            refreshSystemStatus();
            setInterval(refreshSystemStatus, 30000);
        });

        function initSessionChart(){
            if(sessionChartMade) return;
            nextTick(() => {
                const el = document.getElementById('sessionStatusChart');
                if(!el) return;
                const active = props.session_status?.active ?? placeholderRandom(1, 5, 20)[0];
                const ended = props.session_status?.ended ?? placeholderRandom(1, 5, 20)[0];
                new Chart(el.getContext('2d'), { type:'doughnut', data:{ labels:['Aktif','Selesai'], datasets:[{ data:[active, ended], backgroundColor:['#ffc107','#198754'] }] }, options:{ cutout:'65%', plugins:{ legend:{ position:'bottom'} } } });
                sessionChartMade = true;
            });
        }

        function generateLast7Days() {
            const arr = [];
            for (let i = 6; i >= 0; i--) {
                const d = new Date();
                d.setDate(d.getDate() - i);
                arr.push(d.toISOString().slice(5, 10));
            }
            return arr;
        }

        function placeholderRandom(n, min, max) {
            const out = []; for (let i = 0; i < n; i++) { out.push(Math.floor(Math.random() * (max - min + 1)) + min); } return out;
        }

    return { topStats, quickActions, paginatedRecentActivity, recentPage, totalRecentPages, recentNext, recentPrev, goRecent, liveSystemStatus };
    }
};
</script>

<style>
body .dashboard-new .stat-card { border-radius:12px; }
.stat-card .icon-box { width:46px; height:46px; color:currentColor; }
.bg-soft-primary{background:#e8f1ff!important;color:#0d6efd!important}
.bg-soft-success{background:#e9f9f0!important;color:#16a34a!important}
.bg-soft-warning{background:#fff6e4!important;color:#f59e0b!important}
.bg-soft-info{background:#e6f7fb!important;color:#0ea5e9!important}
.bg-soft-secondary{background:#f1f2f4!important;color:#6c757d!important}
.bg-soft-dark{background:#eceff3!important;color:#343a40!important}
.action-tile { background:#fff; border:1px solid #eef2f6; border-radius:14px; transition:.25s; position:relative; }
.action-tile:hover { transform:translateY(-4px); box-shadow:0 .75rem 1.5rem rgba(0,0,0,.10)!important; }
.action-tile .tile-icon { width:42px; height:42px; color:currentColor; }
.tiny { font-size:11px; }
.activity-dot { width:10px; height:10px; border-radius:50%; background:#ddd; }
.list-group-item { background:linear-gradient(90deg,#ffffff 0%,#f9fbfc 100%); }
</style>