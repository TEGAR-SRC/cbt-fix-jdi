<template>
    <Head><title>Dashboard Guru - CBT AI</title></Head>
    <TeacherLayout>
        <div class="container-fluid mb-5 mt-4 dashboard-new">
            <div class="row g-3 mb-3">
                <div class="col-auto">
                    <h5 class="fw-semibold mb-0">Selamat datang, Guru!</h5>
                    <small class="text-muted">Dashboard Guru - CBT AI</small>
                </div>
            </div>
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
                                        <thead class="table-light"><tr><th>Judul</th><th>Mapel</th><th>Mulai</th></tr></thead>
                                        <tbody>
                                            <tr v-for="s in upcoming_exams" :key="s.id">
                                                <td class="text-truncate" style="max-width:140px;">{{ s.exam?.title || s.title }}</td>
                                                <td class="text-truncate" style="max-width:120px;">{{ s.exam?.lesson || s.lesson }}</td>
                                                <td>{{ s.start_time }}</td>
                                            </tr>
                                            <tr v-if="!upcoming_exams?.length"><td colspan="3" class="text-center text-muted py-3">Tidak ada data</td></tr>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </TeacherLayout>
</template>
<script>
import TeacherLayout from '../../../Layouts/Teacher.vue';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref, nextTick, computed } from 'vue';
import { Chart, LineController, LineElement, PointElement, BarElement, CategoryScale, LinearScale, Filler, Tooltip, Legend, ArcElement } from 'chart.js';
Chart.register(LineController, LineElement, PointElement, BarElement, CategoryScale, LinearScale, Filler, Tooltip, Legend, ArcElement);
export default {
    layout: TeacherLayout,
    components:{ Head, Link },
    props: {
        subject: String,
        lessonExists: Boolean,
        students: Number,
        exams: Number,
        questions: Number,
        assignments: Number,
        tryouts: Number,
        active_sessions: Number,
        ended_sessions_today: Number,
        session_status: Object,
        exam_activity: Array,
        upcoming_exams: Array,
        system_status: Object,
        recent_activity: Array,
    },
    setup(props){
        const topStats = [
            { key:'exams', label:'Total Ujian', value: props.exams, color:'primary', trend:null, icon:'<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pencil-square"><path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293z"/><path d="M13.459 4.69 5 13.146V15h1.854L15.459 5.854l-2-2z"/><path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h9a.5.5 0 0 0 0-1h-9A.5.5 0 0 1 2.5 13V4.5A.5.5 0 0 1 2.5 4H8a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 4.5v9z"/></svg>' },
            { key:'questions', label:'Soal', value: props.questions, color:'warning', trend:null, icon:'<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-collection"><path d="M2.5 3a.5.5 0 0 0-.5.5v8a.5.5 0 0 0 .5.5h11a.5.5 0 0 0 .5-.5v-8a.5.5 0 0 0-.5-.5h-11z"/><path d="M4.5 0A.5.5 0 0 0 4 .5V2h1V.5A.5.5 0 0 0 4.5 0zm2 0A.5.5 0 0 0 6 .5V2h1V.5A.5.5 0 0 0 6.5 0zm2 0a.5.5 0 0 0-.5.5V2h1V.5a.5.5 0 0 0-.5-.5z"/></svg>' },
            { key:'assignments', label:'Tugas', value: props.assignments, color:'success', trend:null, icon:'<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-journal-text"><path d="M5 8h6v1H5V8Zm0 2h4v1H5v-1Z"/><path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2H0V2a2 2 0 0 1 2-2h1Zm0 1v14h10V1H3Z"/></svg>' },
            { key:'active_sessions', label:'Sesi Aktif', value: props.active_sessions, color:'secondary', trend:'', icon:'<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-wifi"><path d="M12.02 11.293a1 1 0 0 1 1.415 1.414 4 4 0 0 0-5.657 0 1 1 0 0 1-1.415-1.414 6 6 0 0 1 8.485 0Z"/><path d="M8.464 8.464a1 1 0 0 1 1.414 1.415 2 2 0 0 0-2.828 0 1 1 0 0 1-1.414-1.415 4 4 0 0 1 5.656 0Z"/><path d="M5.636 5.636a1 1 0 0 1 1.415 1.415 6 6 0 0 0-8.486 0 1 1 0 0 1-1.414-1.415 8 8 0 0 1 11.314 0Z"/><path d="M8 15a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/></svg>' },
        ];
        const quickActions = [
            { key:'exams', title:'Buat Ujian', desc:'Buat ujian baru', href:'/teacher/exams/create', color:'primary', icon:'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square"><path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293z"/><path d="M13.459 4.69 5 13.146V15h1.854L15.459 5.854l-2-2z"/><path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h9a.5.5 0 0 0 0-1h-9A.5.5 0 0 1 2.5 13V4.5A.5.5 0 0 1 2.5 4H8a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 4.5v9z"/></svg>' },
            { key:'ai-import', title:'AI Import', desc:'Generate soal AI', href:'/teacher/exams', color:'info', icon:'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cpu"><path d="M5 0a.5.5 0 0 0-.5.5V2H3.5A1.5 1.5 0 0 0 2 3.5V5H.5a.5.5 0 0 0 0 1H2v2H.5a.5.5 0 0 0 0 1H2v2H.5a.5.5 0 0 0 0 1H2v1.5A1.5 1.5 0 0 0 3.5 16H5v1.5a.5.5 0 0 0 1 0V16h2v1.5a.5.5 0 0 0 1 0V16h2v1.5a.5.5 0 0 0 1 0V16h1.5a1.5 1.5 0 0 0 1.5-1.5V13h1.5a.5.5 0 0 0 0-1H16v-2h1.5a.5.5 0 0 0 0-1H16V7h1.5a.5.5 0 0 0 0-1H16V3.5A1.5 1.5 0 0 0 14.5 2H13V.5a.5.5 0 0 0-1 0V2h-2V.5a.5.5 0 0 0-1 0V2H7V.5A.5.5 0 0 0 6 .5V2H5V.5A.5.5 0 0 0 5 0Zm1 5.5A1.5 1.5 0 0 1 7.5 4h1A1.5 1.5 0 0 1 10 5.5v1a1.5 1.5 0 0 1-1.5 1.5h-1A1.5 1.5 0 0 1 6 6.5v-1Z"/></svg>' },
            { key:'bank', title:'Bank Soal', desc:'Kelola bank soal', href:'/teacher/questions', color:'dark', icon:'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-journal-text"><path d="M5 8h6v1H5V8Zm0 2h4v1H5v-1Z"/><path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2H0V2a2 2 0 0 1 2-2h1Zm0 1v14h10V1H3Z"/></svg>' },
            { key:'sessions', title:'Kelola Sesi', desc:'Buat & atur sesi', href:'/teacher/exam-sessions', color:'secondary', icon:'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-stopwatch"><path d="M6.5 1a.5.5 0 0 0 0 1H7v1.07A7.001 7.001 0 0 0 8 16a7 7 0 1 0 1-13.93V2h.5a.5.5 0 0 0 0-1h-3Zm2.5 7a.5.5 0 0 1-.5.5H5.707l-1.147 1.146a.5.5 0 1 1-.707-.707L5.293 7.5 3.853 6.06a.5.5 0 1 1 .707-.707L6 6.793V4.5a.5.5 0 0 1 1 0v3Z"/></svg>' },
            { key:'monitor', title:'Monitoring', desc:'Pantau ujian', href:'/teacher/monitor', color:'info', icon:'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8Z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5Z"/></svg>' },
            { key:'reports', title:'Laporan', desc:'Lihat nilai', href:'/teacher/reports', color:'warning', icon:'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-graph-up-arrow"><path fill-rule="evenodd" d="M0 0h1v15h15v1H0V0Zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.9l-3.613 4.417a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61L13.445 4H10.5a.5.5 0 0 1-.5-.5Z"/></svg>' },
        ];
        // recent activity pagination
        const recentPage = ref(1); const pageSize = 8;
        const totalRecentPages = computed(()=> Math.max(1, Math.ceil((props.recent_activity?.length || 0)/pageSize)));
        const paginatedRecentActivity = computed(()=> (props.recent_activity || []).slice((recentPage.value-1)*pageSize, recentPage.value*pageSize));
        function recentNext(){ if(recentPage.value < totalRecentPages.value) recentPage.value++; }
        function recentPrev(){ if(recentPage.value > 1) recentPage.value--; }
        function goRecent(p){ if(p>=1 && p<= totalRecentPages.value) recentPage.value = p; }
        const liveSystemStatus = ref(props.system_status || null);
        async function refreshSystemStatus(){ try { const res = await fetch('/admin/system-status'); if(res.ok) liveSystemStatus.value = await res.json(); } catch(e){} }
        onMounted(()=>{ buildLine(); initSessionChart(); refreshSystemStatus(); setInterval(refreshSystemStatus,30000); });
        function buildLine(){
            const days = props.exam_activity?.length ? props.exam_activity.map(d=>d.date) : generateLast7Days();
            const examsFinished = props.exam_activity?.length ? props.exam_activity.map(d=>d.exams) : placeholderRandom(7,2,15);
            const tryoutFinished = props.exam_activity?.length ? props.exam_activity.map(d=>d.tryouts) : placeholderRandom(7,1,10);
            new Chart(document.getElementById('examActivityChart').getContext('2d'), { type:'line', data:{ labels:days, datasets:[ {label:'Ujian Selesai', data:examsFinished, borderColor:'#0d6efd', backgroundColor:'rgba(13,110,253,.15)', tension:.35, fill:true, pointRadius:4 }, {label:'Tryout Selesai', data:tryoutFinished, borderColor:'#20c997', backgroundColor:'rgba(32,201,151,.15)', tension:.35, fill:true, pointRadius:4 } ] }, options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom'} }, scales:{ y:{ beginAtZero:true, ticks:{ precision:0 } } } } });
        }
        function initSessionChart(){ nextTick(()=>{ const el = document.getElementById('sessionStatusChart'); if(!el) return; const active = props.session_status?.active ?? 0; const ended = props.session_status?.ended ?? 0; new Chart(el.getContext('2d'), { type:'doughnut', data:{ labels:['Aktif','Selesai'], datasets:[{ data:[active, ended], backgroundColor:['#ffc107','#198754'] }] }, options:{ cutout:'65%', plugins:{ legend:{ position:'bottom'} } } }); }); }
        function generateLast7Days(){ const arr=[]; for(let i=6;i>=0;i--){ const d=new Date(); d.setDate(d.getDate()-i); arr.push(d.toISOString().slice(5,10)); } return arr; }
        function placeholderRandom(n,min,max){ const out=[]; for(let i=0;i<n;i++){ out.push(Math.floor(Math.random()*(max-min+1))+min); } return out; }
        return { topStats, quickActions, recentPage, totalRecentPages, paginatedRecentActivity, recentNext, recentPrev, goRecent, liveSystemStatus };
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
