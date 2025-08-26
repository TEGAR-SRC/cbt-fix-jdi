<template>
    <Head>
        <title>Dashboard - Aplikasi Ujian Online</title>
    </Head>
    <div class="container-fluid mb-5 mt-5">
        <div class="row">

            <div class="col-12 col-sm-6 col-xl-3 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="row d-block d-xl-flex align-items-center">
                            <div
                                class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                                <div class="icon-shape icon-shape-info rounded me-4 me-sm-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-back icon icon-md" viewBox="0 0 16 16">
                                        <path d="M0 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2h2a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2H2a2 2 0 0 1-2-2V2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H2z"/>
                                    </svg>
                                </div>
                                <div class="d-sm-none">
                                    <h2 class="h5">Kelas</h2>
                                    <h3 class="fw-extrabold mb-1">{{ classrooms }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h5">Kelas</h2>
                                    <h3 class="fw-extrabold mb-1">{{ classrooms }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="row d-block d-xl-flex align-items-center">
                            <div
                                class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                                <div class="icon-shape icon-shape-success rounded me-4 me-sm-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-people-fill icon icon-md" viewBox="0 0 16 16">
                                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                        <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/>
                                        <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                                    </svg>
                                </div>
                                <div class="d-sm-none">
                                    <h2 class="h5">Siswa</h2>
                                    <h3 class="fw-extrabold mb-1">{{ students }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h5">Siswa</h2>
                                    <h3 class="fw-extrabold mb-1">{{ students }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="row d-block d-xl-flex align-items-center">
                            <div
                                class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                                <div class="icon-shape icon-shape-tertiary rounded me-4 me-sm-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-pencil-square icon icon-md" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                    </svg>
                                </div>
                                <div class="d-sm-none">
                                    <h2 class="h5">Ujian</h2>
                                    <h3 class="fw-extrabold mb-1">{{ exams }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h5">Ujian</h2>
                                    <h3 class="fw-extrabold mb-1">{{ exams }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="row d-block d-xl-flex align-items-center">
                            <div
                                class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                                <div class="icon-shape icon-shape-danger rounded me-4 me-sm-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-stopwatch icon icon-md" viewBox="0 0 16 16">
                                        <path d="M8.5 5.6a.5.5 0 1 0-1 0v2.9h-3a.5.5 0 0 0 0 1H8a.5.5 0 0 0 .5-.5V5.6z"/>
                                        <path d="M6.5 1A.5.5 0 0 1 7 .5h2a.5.5 0 0 1 0 1v.57c1.36.196 2.594.78 3.584 1.64a.715.715 0 0 1 .012-.013l.354-.354-.354-.353a.5.5 0 0 1 .707-.708l1.414 1.415a.5.5 0 1 1-.707.707l-.353-.354-.354.354a.512.512 0 0 1-.013.012A7 7 0 1 1 7 2.071V1.5a.5.5 0 0 1-.5-.5zM8 3a6 6 0 1 0 .001 12A6 6 0 0 0 8 3z"/>
                                    </svg>
                                </div>
                                <div class="d-sm-none">
                                    <h2 class="h5">Sesi Ujian</h2>
                                    <h3 class="fw-extrabold mb-1">{{ exam_sessions }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h5">Sesi Ujian</h2>
                                    <h3 class="fw-extrabold mb-1">{{ exam_sessions }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- Additional Quick Stats -->
        <div class="row mt-2">
            <div class="col-12 col-sm-6 col-xl-3 mb-4" v-if="assignments!==undefined">
                <div class="card border-0 shadow h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h2 class="h6 text-uppercase text-muted mb-1">Tugas</h2>
                                <div class="display-6 fw-bold text-primary">{{ assignments }}</div>
                                <small class="text-muted">Total tugas</small>
                            </div>
                            <div class="icon-shape bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-journal-text"><path d="M5 8a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5A.5.5 0 0 1 5 8Zm0 2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5A.5.5 0 0 1 5 10Zm0-4a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5A.5.5 0 0 1 5 6Z"/><path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2Z"/></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3 mb-4" v-if="tryouts!==undefined">
                <div class="card border-0 shadow h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h2 class="h6 text-uppercase text-muted mb-1">Tryout</h2>
                                <div class="display-6 fw-bold text-success">{{ tryouts }}</div>
                                <small class="text-muted">Total tryout</small>
                            </div>
                            <div class="icon-shape bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-clipboard-check"><path fill-rule="evenodd" d="M10.854 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0L5.5 8.207a.5.5 0 0 1 .708-.708L7.5 8.793l2.646-2.647a.5.5 0 0 1 .708 0z"/><path d="M10 1.5v1h1a1 1 0 0 1 1 1V14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V3.5a1 1 0 0 1 1-1h1v-1A1.5 1.5 0 0 1 7.5 0h1A1.5 1.5 0 0 1 10 1.5zM6 2v1h4V2a.5.5 0 0 0-.5-.5h-3A.5.5 0 0 0 6 2z"/></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3 mb-4" v-if="active_sessions!==undefined">
                <div class="card border-0 shadow h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h2 class="h6 text-uppercase text-muted mb-1">Sesi Aktif</h2>
                                <div class="display-6 fw-bold text-warning">{{ active_sessions }}</div>
                                <small class="text-muted">Sedang berjalan</small>
                            </div>
                            <div class="icon-shape bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-stopwatch"><path d="M6.5 1A.5.5 0 0 1 7 .5h2a.5.5 0 0 1 0 1v.57c1.36.196 2.594.78 3.584 1.64a.715.715 0 0 1 .012-.013l.354-.354-.354-.353a.5.5 0 0 1 .707-.708l1.414 1.415a.5.5 0 1 1-.707.707l-.353-.354-.354.354a.512.512 0 0 1-.013.012A7 7 0 1 1 7 2.071V1.5a.5.5 0 0 1-.5-.5z"/></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3 mb-4" v-if="ended_sessions_today!==undefined">
                <div class="card border-0 shadow h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h2 class="h6 text-uppercase text-muted mb-1">Selesai (Hari Ini)</h2>
                                <div class="display-6 fw-bold text-danger">{{ ended_sessions_today }}</div>
                                <small class="text-muted">Sesi berakhir</small>
                            </div>
                            <div class="icon-shape bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-flag"><path d="M14.778.085A.5.5 0 0 1 15 .5v8a.5.5 0 0 1-.707.454L10 7.14 6.707 8.954A.5.5 0 0 1 6 8.5v-8a.5.5 0 0 1 .778-.416L10 2.86 13.293 1.046a.5.5 0 0 1 .485-.04z"/><path d="M4 0a.5.5 0 0 1 .5.5v14.5a.5.5 0 0 1-1 0V.5A.5.5 0 0 1 4 0z"/></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mt-2">
            <div class="col-lg-7 mb-4">
                <div class="card border-0 shadow h-100">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Aktivitas Ujian 7 Hari</h6>
                        <small class="text-muted">Jumlah ujian & tryout selesai</small>
                    </div>
                    <div class="card-body">
                        <canvas id="examActivityChart" height="140"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 mb-4">
                <div class="card border-0 shadow h-100">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Distribusi Status Sesi</h6>
                        <small class="text-muted">Aktif vs Selesai</small>
                    </div>
                    <div class="card-body">
                        <canvas id="sessionStatusChart" height="140"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-xl-5 mb-4" v-if="system_status">
                <div class="card border-0 shadow h-100">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Status Sistem</h6>
                        <span class="badge" :class="system_status.database ? 'bg-success' : 'bg-danger'">DB</span>
                    </div>
                    <div class="card-body small">
                        <div class="d-flex justify-content-between mb-2"><span>Database</span><span :class="system_status.database?'text-success':'text-danger'">{{ system_status.database? 'OK':'Down' }}</span></div>
                        <div class="d-flex justify-content-between mb-2"><span>Websocket</span><span :class="system_status.websocket?'text-success':'text-warning'">{{ system_status.websocket? 'Online':'Offline' }}</span></div>
                        <div class="d-flex justify-content-between mb-2"><span>Anti Cheat</span><span :class="system_status.anti_cheat?'text-success':'text-danger'">{{ system_status.anti_cheat? 'Active':'Disabled' }}</span></div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between"><span>Storage</span><span>{{ (system_status.storage_used*100).toFixed(1) }}%</span></div>
                            <div class="progress" style="height:6px;">
                                <div class="progress-bar bg-info" :style="{width:(system_status.storage_used*100)+'%'}"></div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mb-1"><span>Last Backup</span><span>{{ system_status.last_backup }}</span></div>
                        <div class="d-flex justify-content-between mb-1"><span>Queue Pending</span><span>{{ system_status.queue.pending }}</span></div>
                        <div class="d-flex justify-content-between"><span>Queue Failed</span><span>{{ system_status.queue.failed }}</span></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-7 mb-4" v-if="upcoming_exams">
                <div class="card border-0 shadow h-100">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Agenda Sesi Mendatang</h6>
                        <small class="text-muted">5 terdekat</small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Judul</th>
                                    <th>Mapel</th>
                                    <th>Status</th>
                                    <th>Durasi</th>
                                    <th>Peserta</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="s in upcoming_exams" :key="s.id">
                                    <td class="text-truncate" style="max-width:140px;">{{ s.exam.title }}</td>
                                    <td>{{ s.exam.lesson }}</td>
                                    <td>
                                        <span class="badge" :class="{
                                            'bg-success': s.status==='Active',
                                            'bg-secondary': s.status==='Ended',
                                            'bg-warning text-dark': s.status==='Upcoming'
                                        }">{{ s.status }}</span>
                                    </td>
                                    <td>{{ s.duration_min }}m</td>
                                    <td>{{ s.participants }}</td>
                                </tr>
                                <tr v-if="!upcoming_exams.length">
                                    <td colspan="5" class="text-center text-muted py-3">Tidak ada data</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LayoutAdmin from '../../../Layouts/Admin.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';
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
    components: { Head },
    props: {
        students: Number,
        exams: Number,
        exam_sessions: Number,
        classrooms: Number,
        assignments: Number,
        tryouts: Number,
        active_sessions: Number,
        ended_sessions_today: Number,
        session_status: Object,
        exam_activity: Array,
        upcoming_exams: Array,
        system_status: Object,
    },
    setup(props) {
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

            // Doughnut chart for session status
            const active = props.session_status?.active ?? placeholderRandom(1, 5, 20)[0];
            const ended = props.session_status?.ended ?? placeholderRandom(1, 5, 20)[0];
            new Chart(document.getElementById('sessionStatusChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Aktif', 'Selesai'],
                    datasets: [{
                        data: [active, ended],
                        backgroundColor: ['#ffc107', '#198754']
                    }]
                },
                options: {
                    cutout: '65%',
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        });

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

        return {};
    }
};
</script>

<style>

</style>