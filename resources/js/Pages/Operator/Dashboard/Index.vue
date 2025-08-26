<template>
  <OperatorLayout>
    <div class="container-fluid py-4">
      <div class="d-flex align-items-center mb-4 flex-wrap gap-2">
        <h4 class="mb-0 fw-bold">Dashboard Operator</h4>
        <span class="text-muted small">Ringkasan operasional & monitoring ujian</span>
      </div>

      <!-- Stats Cards -->
      <div class="row g-3 mb-3">
        <div class="col-6 col-xl-2" v-for="card in statCards" :key="card.key">
          <div class="card shadow-sm border-0 h-100 stat-tile">
            <div class="card-body py-3">
              <div class="d-flex align-items-center justify-content-between">
                <div>
                  <div class="text-muted small mb-1">{{ card.label }}</div>
                  <div class="h4 fw-bold mb-0">{{ card.value }}</div>
                </div>
                <div class="icon-wrap" v-html="card.icon"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="row g-3 mb-4">
        <div class="col-md-3" v-for="qa in quickActions" :key="qa.key">
          <Link :href="qa.href" class="text-decoration-none">
            <div class="card h-100 border-0 shadow-sm action-tile">
              <div class="card-body py-3 d-flex align-items-center">
                <div class="action-icon me-3" v-html="qa.icon"></div>
                <div>
                  <div class="fw-semibold small">{{ qa.label }}</div>
                  <div class="text-muted tiny-text">{{ qa.desc }}</div>
                </div>
              </div>
            </div>
          </Link>
        </div>
      </div>

      <div class="row g-3 mb-4">
        <!-- Activity Line Chart -->
        <div class="col-xl-8">
          <div class="card shadow-sm h-100 border-0">
            <div class="card-header bg-white border-0 pb-0 d-flex justify-content-between align-items-center">
              <h6 class="mb-0">Aktivitas 7 Hari</h6>
            </div>
            <div class="card-body">
              <canvas id="activityChart" height="120"></canvas>
            </div>
          </div>
        </div>
        <!-- Session Status Doughnut -->
        <div class="col-xl-4">
          <div class="card shadow-sm h-100 border-0">
            <div class="card-header bg-white border-0 pb-0 d-flex justify-content-between align-items-center">
              <h6 class="mb-0">Status Sesi</h6>
            </div>
            <div class="card-body">
              <canvas id="sessionStatusChart" height="160"></canvas>
              <div class="mt-3 small">
                <span class="badge bg-primary me-1">Akan Datang {{ session_status.upcoming }}</span>
                <span class="badge bg-success me-1">Aktif {{ session_status.ongoing }}</span>
                <span class="badge bg-secondary">Selesai {{ session_status.ended }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-3 mb-4">
        <!-- Upcoming Exams -->
        <div class="col-xl-8">
          <div class="card shadow-sm h-100 border-0">
            <div class="card-header bg-white border-0 pb-0 d-flex justify-content-between align-items-center">
              <h6 class="mb-0">Jadwal Ujian Terdekat</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                  <thead class="small text-muted">
                    <tr><th>Judul</th><th>Mapel</th><th>Status</th><th>Peserta</th><th>Durasi</th></tr>
                  </thead>
                  <tbody>
                    <tr v-for="u in upcoming_exams" :key="u.id">
                      <td class="fw-semibold">{{ u.title }}</td>
                      <td>{{ u.exam?.lesson || '-' }}</td>
                      <td><span :class="statusBadge(u.status)">{{ u.status }}</span></td>
                      <td>{{ u.participants }}</td>
                      <td>{{ u.duration_min }}m</td>
                    </tr>
                    <tr v-if="!upcoming_exams || upcoming_exams.length===0">
                      <td colspan="5" class="text-center text-muted small py-4">Tidak ada jadwal</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- System Status -->
        <div class="col-xl-4">
          <div class="card shadow-sm h-100 border-0">
            <div class="card-header bg-white border-0 pb-0 d-flex justify-content-between align-items-center">
              <h6 class="mb-0">Status Sistem</h6>
            </div>
            <div class="card-body small">
              <ul class="list-unstyled mb-0">
                <li class="d-flex justify-content-between mb-2"><span>Database</span><span class="badge" :class="system_status.database?'bg-success':'bg-danger'">{{ system_status.database?'OK':'DOWN' }}</span></li>
                <li class="d-flex justify-content-between mb-2"><span>Websocket</span><span class="badge" :class="system_status.websocket?'bg-success':'bg-danger'">{{ system_status.websocket?'OK':'ISSUE' }}</span></li>
                <li class="d-flex justify-content-between mb-2"><span>Anti Cheat</span><span class="badge" :class="system_status.anti_cheat?'bg-success':'bg-danger'">{{ system_status.anti_cheat?'AKTIF':'OFF' }}</span></li>
                <li class="d-flex justify-content-between mb-2"><span>Storage</span><span>{{ Math.round(system_status.storage_used*100) }}%</span></li>
                <li class="d-flex justify-content-between mb-2"><span>Backup Terakhir</span><span>{{ system_status.last_backup }}</span></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </OperatorLayout>
</template>

<script>
import { onMounted, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import OperatorLayout from '../../../Layouts/Operator.vue';
import Chart from 'chart.js/auto';

export default {
  components: { OperatorLayout, Link },
  props: {
    students: Number, exams: Number, exam_sessions: Number, classrooms: Number, assignments: Number, tryouts: Number,
    questions: Number, active_sessions: Number, ended_sessions_today: Number, upcoming_exams: Array, system_status: Object,
    session_status: Object, exam_activity: Array
  },
  setup(props){
    // Inline SVG icons (independen dari bootstrap-icons CSS)
    const svg = {
      students: '<svg width="28" height="28" fill="none" stroke="#0d6efd" stroke-width="1.5" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2c0-1.886-.786-3.597-2.05-4.85M15 3.46a4.002 4.002 0 0 1 0 7.08" stroke-linecap="round" stroke-linejoin="round"/></svg>',
      exams: '<svg width="28" height="28" stroke="#0dcaf0" fill="none" stroke-width="1.5" viewBox="0 0 24 24"><path d="M8 4h10a2 2 0 0 1 2 2v11" stroke-linecap="round"/><path d="M6 20h10a2 2 0 0 0 2-2v-9l-4-4H6a2 2 0 0 0-2 2v11c0 1.1.9 2 2 2Z"/><path d="M9 13h6M9 9h2" stroke-linecap="round"/></svg>',
      sessions: '<svg width="28" height="28" stroke="#ffc107" fill="none" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3" stroke-linecap="round" stroke-linejoin="round"/></svg>',
      questions: '<svg width="28" height="28" stroke="#6c757d" fill="none" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="14" rx="2"/><path d="M7 8h10M7 12h6" stroke-linecap="round"/></svg>',
      assignments: '<svg width="28" height="28" stroke="#198754" fill="none" stroke-width="1.5" viewBox="0 0 24 24"><path d="M9 3h6l1 2h3a1 1 0 0 1 1 1v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a1 1 0 0 1 1-1h3l1-2Z"/><path d="M9 11h6M9 15h4" stroke-linecap="round"/></svg>',
      tryouts: '<svg width="28" height="28" stroke="#dc3545" fill="none" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="14" rx="2"/><path d="m9 12 2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/></svg>',
      add: '<svg width="22" height="22" stroke="#4f46e5" fill="none" stroke-width="1.7" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>',
      monitor: '<svg width="22" height="22" stroke="#4f46e5" fill="none" stroke-width="1.5" viewBox="0 0 24 24"><path d="M3 4h18v10H3z"/><path d="M8 21h8M12 14v7" stroke-linecap="round"/><path d="M7 9l2 2 3-3 2 2 3-3" stroke-linecap="round" stroke-linejoin="round"/></svg>',
      studentsSm: '<svg width="22" height="22" fill="none" stroke="#4f46e5" stroke-width="1.5" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="9" cy="7" r="4"/></svg>',
      sessionsSm: '<svg width="22" height="22" stroke="#4f46e5" fill="none" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3" stroke-linecap="round" stroke-linejoin="round"/></svg>'
    };

    const statCards = computed(()=> [
      { key:'students', label:'Siswa', value:props.students, icon:svg.students },
      { key:'exams', label:'Ujian', value:props.exams, icon:svg.exams },
      { key:'sessions', label:'Sesi', value:props.exam_sessions, icon:svg.sessions },
      { key:'questions', label:'Bank Soal', value:props.questions, icon:svg.questions },
      { key:'assignments', label:'Tugas', value:props.assignments, icon:svg.assignments },
      { key:'tryouts', label:'Tryout', value:props.tryouts, icon:svg.tryouts },
    ]);
    const quickActions = computed(()=> [
      { key:'new-exam', label:'Buat Ujian', desc:'Tambah ujian baru', href:'/operator/exams/create', icon:svg.add },
      { key:'sessions', label:'Sesi Ujian', desc:'Kelola sesi', href:'/operator/exam-sessions', icon:svg.sessionsSm },
      { key:'monitor', label:'Monitoring', desc:'Pantau berlangsung', href:'/operator/monitor', icon:svg.monitor },
      { key:'students', label:'Data Siswa', desc:'Kelola siswa', href:'/operator/students', icon:svg.studentsSm },
    ]);

    function statusBadge(st){
      const map = { 'Active':'badge bg-success','Ended':'badge bg-secondary','Upcoming':'badge bg-warning text-dark' };
      return map[st] || 'badge bg-light text-dark';
    }

    onMounted(()=> {
      // Activity line chart
      const ctxA = document.getElementById('activityChart');
      if(ctxA){
        new Chart(ctxA, {
          type:'line',
            data:{
              labels: props.exam_activity.map(a=> a.date.slice(5)),
              datasets:[{
                label:'Sesi Selesai',
                data: props.exam_activity.map(a=> a.exams_finished),
                borderColor:'#0d6efd', tension:.3, fill:false, borderWidth:2, pointRadius:4, pointBackgroundColor:'#fff'
              }]
            },
            options:{ responsive:true, scales:{ y:{ beginAtZero:true, ticks:{ precision:0 } } }, plugins:{ legend:{ display:false } } }
        });
      }
      // Session status doughnut
      const ctxS = document.getElementById('sessionStatusChart');
      if(ctxS){
        new Chart(ctxS, {
          type:'doughnut',
          data:{
            labels:['Akan','Aktif','Selesai'],
            datasets:[{ data:[props.session_status.upcoming, props.session_status.ongoing, props.session_status.ended], backgroundColor:['#ffc107','#198754','#6c757d'] }]
          }, options:{ plugins:{ legend:{ position:'bottom' } }, cutout:'55%' }
        });
      }
    });

    return { statCards, quickActions, statusBadge };
  }
}
</script>

<style scoped>
.stat-tile .icon-wrap{ width:42px; height:42px; background:#f1f5f9; border-radius:12px; display:flex; align-items:center; justify-content:center; }
.action-tile{ transition:.25s; border-radius:14px; }
.action-tile:hover{ transform:translateY(-2px); box-shadow:0 6px 18px -6px rgba(0,0,0,.15); }
.action-icon{ width:40px; height:40px; background:#eef2ff; display:flex; align-items:center; justify-content:center; border-radius:10px; color:#4f46e5; font-size:20px; }
.tiny-text{ font-size:11px; }
table tbody tr:last-child td { border-bottom:0; }
</style>
