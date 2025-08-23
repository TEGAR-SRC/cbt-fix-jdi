<template>
    <Head>
        <title>Dashboard - CBT AI</title>
    </Head>
    <div class="container-fluid py-4 dashboard-root">
        <!-- Greeting & quick stats -->
        <div class="mb-4">
            <h3 class="fw-semibold mb-1">Selamat datang, Administrator!</h3>
            <div class="text-muted">Dashboard administrator - CBT AI</div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="stat-icon bg-primary-10 text-primary"><i class="fa fa-file-alt"></i></div>
                            <span class="badge bg-primary-subtle text-primary">+12%</span>
                        </div>
                        <div class="fw-semibold mb-1">Total Exams</div>
                        <div class="display-6">{{ exams }}</div>
                        <div class="text-muted small">vs. last month</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="stat-icon bg-success-10 text-success"><i class="fa fa-user-friends"></i></div>
                            <span class="badge bg-success-subtle text-success">+8%</span>
                        </div>
                        <div class="fw-semibold mb-1">Active Users</div>
                        <div class="display-6">{{ students }}</div>
                        <div class="text-muted small">vs. last month</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="stat-icon bg-info-10 text-info"><i class="fa fa-question-circle"></i></div>
                            <span class="badge bg-info-subtle text-info">+15%</span>
                        </div>
                        <div class="fw-semibold mb-1">Questions</div>
                        <div class="display-6">{{ questionCountHint }}</div>
                        <div class="text-muted small">from last month</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="stat-icon bg-warning-10 text-warning"><i class="fa fa-chart-line"></i></div>
                            <span class="badge bg-warning-subtle text-warning">OK</span>
                        </div>
                        <div class="fw-semibold mb-1">System Health</div>
                        <div class="display-6">98%</div>
                        <div class="text-muted small">All systems operational</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick actions -->
        <div class="row g-3 mb-4">
            <div class="col-md-3" v-for="qa in quickActions" :key="qa.text">
                <div class="card border-0 shadow-sm h-100 clickable" @click="go(qa.href)">
                    <div class="card-body d-flex align-items-start gap-3">
                        <div class="qa-icon" :class="qa.color"><i :class="qa.icon"></i></div>
                        <div>
                            <div class="fw-semibold">{{ qa.text }}</div>
                            <div class="text-muted small">{{ qa.sub }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <!-- Recent Activity -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="fw-semibold mb-3">Recent Activity</div>
                        <ul class="list-unstyled mb-0">
                            <li v-for="(item, idx) in recentActivity" :key="idx" class="d-flex align-items-start mb-3">
                                <span class="me-2"><span class="dot dot-success"></span></span>
                                <div>
                                    <div class="fw-semibold">{{ item.title }}</div>
                                    <div class="text-muted small">{{ item.time }}</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="fw-semibold mb-3">System Status</div>
                        <ul class="list-unstyled small mb-0">
                            <li class="d-flex justify-content-between align-items-center mb-2">
                                <span>Database</span>
                                <span class="d-flex align-items-center gap-1"><span class="dot dot-success"></span><span>Online</span></span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center mb-2">
                                <span>WebSocket</span>
                                <span class="d-flex align-items-center gap-1"><span :class="system_status.websocket ? 'dot dot-success' : 'dot dot-muted'"></span><span>{{ system_status.websocket ? 'Connected' : 'N/A' }}</span></span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center mb-2">
                                <span>Anti-Cheat</span>
                                <span class="d-flex align-items-center gap-1"><span class="dot dot-success"></span><span>Active</span></span>
                            </li>
                            <li class="d-flex justify-content-between mb-2">
                                <span>Storage</span>
                                <span>{{ Math.round(system_status.storage_used * 100) }}% used</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span>Backup</span>
                                <span class="d-flex align-items-center gap-1"><i class="fa fa-clock text-muted"></i><span>Last: {{ system_status.last_backup }}</span></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active & Upcoming Exams -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="fw-semibold">Active & Upcoming Exams</div>
                    <Link href="/admin/exams" class="btn btn-sm btn-primary rounded-pill px-3">View All</Link>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Exam Name</th>
                                <th>Status</th>
                                <th>Participants</th>
                                <th>Duration</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="s in upcoming_exams" :key="s.id">
                                <td>
                                    <div class="fw-semibold">{{ s.exam?.title || s.title }}</div>
                                    <div class="text-muted small">Bank Soal {{ s.exam?.lesson }}</div>
                                </td>
                                <td>
                                    <span :class="statusBadge(s.status)">{{ s.status }}</span>
                                </td>
                                <td>{{ s.participants }}/—</td>
                                <td>{{ s.duration_min }} min</td>
                                <td class="text-end">
                                    <Link :href="`/admin/exams/${s.exam?.id}`" class="btn btn-sm btn-outline-secondary me-1"><i class="fa fa-eye"></i></Link>
                                    <Link :href="`/admin/monitor`" class="btn btn-sm btn-outline-secondary"><i class="fa fa-tv"></i></Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
  
</template>

<script>
import LayoutAdmin from '../../../Layouts/Admin.vue';
import { Head, Link, router } from '@inertiajs/vue3';

export default {
    layout: LayoutAdmin,
    components: { Head, Link },
    props: {
        students: Number,
        exams: Number,
        exam_sessions: Number,
        classrooms: Number,
        upcoming_exams: Array,
        system_status: Object
    },
    computed: {
        questionCountHint() {
            // Optional: use exams*X as a placeholder; replace with actual count if needed
            return this.exams * 100;
        },
        quickActions() {
            return [
                { text: 'Create Exam', sub: 'Buat ujian baru', href: '/admin/exams/create', icon: 'fa fa-plus', color:'qa-blue' },
                { text: 'Import Questions', sub: 'Import soal dari Excel', href: '/admin/exams', icon: 'fa fa-file-excel', color:'qa-green' },
                { text: 'Export Results', sub: 'Export hasil ujian', href: '/admin/reports/export', icon: 'fa fa-file-export', color:'qa-orange' },
                { text: 'Manage Users', sub: 'Kelola user & role', href: '/admin/students', icon: 'fa fa-users', color:'qa-purple' },
                { text: 'View Reports', sub: 'Laporan & analisis', href: '/admin/reports', icon: 'fa fa-chart-line', color:'qa-blue' },
                { text: 'System Settings', sub: 'Konfigurasi sistem', href: '/admin/dashboard', icon: 'fa fa-cog', color:'qa-gray' },
                { text: 'Question Banks', sub: 'Kelola bank soal', href: '/admin/exams', icon: 'fa fa-database', color:'qa-teal' },
                { text: 'Proctoring', sub: 'Monitor ujian real-time', href: '/admin/monitor', icon: 'fa fa-tv', color:'qa-orange' },
                { text: 'Analytics', sub: 'Analisis performa', href: '/admin/reports', icon: 'fa fa-chart-pie', color:'qa-purple' },
                { text: 'Notifications', sub: 'Kelola notifikasi', href: '/admin/dashboard', icon: 'fa fa-bell', color:'qa-green' },
                { text: 'Backup & Restore', sub: 'Kelola backup', href: '/admin/reports', icon: 'fa fa-database', color:'qa-blue' },
                { text: 'System Logs', sub: 'Audit & log', href: '/admin/dashboard', icon: 'fa fa-clipboard-list', color:'qa-gray' },
            ];
        }
    },
    methods: {
        go(href) { router.visit(href); },
        statusBadge(status) {
            const cls = {
                'Active': 'badge bg-success-subtle text-success',
                'Upcoming': 'badge bg-info-subtle text-info',
                'Ended': 'badge bg-secondary-subtle text-secondary',
            }[status] || 'badge bg-light text-dark';
            return cls + ' badge rounded-pill px-3 py-2';
        }
    }
}
</script>

<style>
.clickable { cursor: pointer; }
.bg-primary-subtle{ background: rgba(32,119,255,.1); }
.bg-success-subtle{ background: rgba(25,135,84,.1); }
.bg-info-subtle{ background: rgba(13,202,240,.1); }
.bg-warning-subtle{ background: rgba(255,193,7,.1); }
.badge { font-weight: 600; }

/* Dashboard contrast fixes (scoped by .dashboard-root) */



/* Nickelfox dashboard card and table clone */
.dashboard-root .text-muted { color: #7b8a9a !important; }
.dashboard-root .card {
    background: #fff;
    border: none;
    border-radius: 18px;
    box-shadow: 0 2px 16px 0 rgba(32,64,122,.10);
    padding: 22px 26px;
    font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
}

.dashboard-root {
    background: rgb(248,250,252);
}
.dashboard-root .card .fw-semibold { color: #232a3b; font-weight: 600; }
.dashboard-root .display-6 { color: #232a3b; text-shadow: none; font-weight: 700; font-size: 2.2rem; }
.dashboard-root .table thead th {
    background: #f3f4f6;
    color: #232a3b;
    border-bottom: 1px solid #e0e3e8;
    font-weight: 600;
}
.dashboard-root .table tbody td { color: #232a3b; background: #fff; }
.dashboard-root .table { border-color:#e0e3e8; border-radius: 14px; overflow: hidden; font-family: 'Inter', 'Segoe UI', Arial, sans-serif; }
.dashboard-root .table-hover tbody tr:hover { background: #f3f4f6; }
.dashboard-root .clickable { transition: box-shadow .15s ease, transform .15s ease, border-color .15s ease; border-radius: 14px; }
body, .dashboard-root {
    background: rgb(248,250,252) !important;
}
.dashboard-root .clickable:hover {
    border: 1px solid #4f8cff;
    box-shadow: 0 2px 16px 0 rgba(79,140,255,.10)!important;
    transform: translateY(-1px);
    background: #f3f4f6;
}
.dashboard-root .clickable i { color: #4f8cff; }

.bg-primary-10 { background: rgba(13,110,253,.22); }
.bg-success-10 { background: rgba(25,135,84,.22); }
.bg-info-10 { background: rgba(13,202,240,.22); }
.bg-warning-10 { background: rgba(255,193,7,.22); }

/* Quick action icons */
.qa-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #4f8cff;
    background: #eaf3ff;
    box-shadow: 0 2px 8px 0 rgba(32,119,255,.08);
}
.qa-blue { background: #eaf3ff; color:#4f8cff; }
.qa-green { background: #eafaf1; color:#198754; }
.qa-orange { background: #fff4e6; color:#fd7e14; }
.qa-purple { background: #f3e8ff; color:#6f42c1; }
.qa-teal { background: #e6fcf5; color:#20c997; }
.qa-gray { background: #f1f5f9; color:#7b8a9a; }

/* Status dots */
.dot { width: 10px; height: 10px; border-radius: 50%; display:inline-block; }
.dot-success { background:#22c55e; box-shadow: 0 0 0 2px rgba(34,197,94,.08); }
.dot-muted { background:#94a3b8; }
</style>