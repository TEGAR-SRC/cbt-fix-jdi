<template>
  <Head><title>Dashboard - Dinas</title></Head>
  <div class="container-fluid py-4">
    <div class="d-flex align-items-center mb-4 flex-wrap gap-2">
      <h4 class="fw-bold mb-0">Dashboard Dinas</h4>
      <span class="text-muted small">Ringkasan aktivitas ujian</span>
    </div>

    <!-- Stat Tiles -->
    <div class="row g-3 mb-4">
      <div class="col-6 col-md-3 col-lg-2" v-for="c in statCards" :key="c.key">
        <div class="card border-0 shadow-sm h-100 stat-tile">
          <div class="card-body py-3 d-flex justify-content-between align-items-center">
            <div>
              <div class="text-muted small mb-1">{{ c.label }}</div>
              <div class="h5 fw-bold mb-0">{{ c.value }}</div>
            </div>
            <div class="icon-wrap" v-html="c.icon" />
          </div>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Aktivitas 7 Hari (Sesi Selesai)</h6>
          </div>
          <div class="card-body">
            <canvas id="activityChart" height="140" />
          </div>
        </div>
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white border-0 py-3"><h6 class="mb-0">Sesi Terbaru</h6></div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-sm table-hover mb-0 align-middle">
                <thead class="table-light small">
                  <tr>
                    <th>Judul</th><th>Mapel</th><th>Mulai</th><th>Selesai</th><th>Status</th><th>Peserta</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="s in recent_sessions" :key="s.id">
                    <td class="fw-semibold">{{ s.title }}</td>
                    <td>{{ s.exam?.lesson }}</td>
                    <td class="small text-muted">{{ s.start_time }}</td>
                    <td class="small text-muted">{{ s.end_time }}</td>
                    <td>
                      <span :class="statusBadge(s.status)">{{ s.status }}</span>
                    </td>
                    <td>{{ s.participants }}</td>
                  </tr>
                  <tr v-if="!recent_sessions || !recent_sessions.length">
                    <td colspan="6" class="text-center small text-muted py-3">Belum ada data sesi.</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white border-0 py-3"><h6 class="mb-0">Distribusi Nilai</h6></div>
          <div class="card-body">
            <canvas id="distChart" height="160" />
            <ul class="list-unstyled small mt-3 mb-0">
              <li v-for="(v,k) in distribution" :key="k" class="d-flex justify-content-between"><span>{{ k }}</span><span class="fw-semibold">{{ v }}</span></li>
            </ul>
          </div>
        </div>
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white border-0 py-3"><h6 class="mb-0">Status Sesi</h6></div>
          <div class="card-body">
            <canvas id="sessionChart" height="160" />
            <div class="small mt-3 d-flex flex-column gap-1">
              <div><span class="legend-dot bg-primary me-1"></span> Upcoming: {{ session_status.upcoming }}</div>
              <div><span class="legend-dot bg-success me-1"></span> Ongoing: {{ session_status.ongoing }}</div>
              <div><span class="legend-dot bg-secondary me-1"></span> Ended: {{ session_status.ended }}</div>
            </div>
          </div>
        </div>
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white border-0 py-3"><h6 class="mb-0">Status Sistem (Sample)</h6></div>
          <div class="card-body small">
            <ul class="list-unstyled mb-0">
              <li class="mb-1 d-flex align-items-center"><span class="status-dot me-2" :class="system_status.database ? 'ok' : 'fail'"></span> Database</li>
              <li class="mb-1 d-flex align-items-center"><span class="status-dot me-2" :class="system_status.websocket ? 'ok' : 'fail'"></span> Websocket</li>
              <li class="mb-1 d-flex align-items-center"><span class="status-dot me-2" :class="system_status.anti_cheat ? 'ok' : 'fail'"></span> Anti Cheating</li>
            </ul>
            <div class="text-muted fst-italic mt-2">Realtime belum diaktifkan.</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { Head } from '@inertiajs/vue3';
import DinasLayout from '../../../Layouts/Dinas.vue';
import { computed, onMounted } from 'vue';
import Chart from 'chart.js/auto';

export default {
  layout: DinasLayout,
  components: { Head },
  props: { stats: Object, distribution: Object, session_status: Object, exam_activity: Array, recent_sessions: Array, system_status: Object },
  setup(props){
    const statCards = computed(()=> {
      const wrap = (svg,bg)=>`<div class='icon-wrap-sm ${bg}'>${svg}</div>`;
      const svg = {
        exams: '<svg width="22" height="22" fill="none" stroke="#0d6efd" stroke-width="1.6" viewBox="0 0 24 24"><path d="M4 5h16v14H4z"/><path d="M8 5V3h8v2" stroke-linecap="round"/></svg>',
        sessions: '<svg width="22" height="22" fill="none" stroke="#6f42c1" stroke-width="1.6" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M7 9h6M7 13h4" stroke-linecap="round"/></svg>',
        active: '<svg width="22" height="22" fill="none" stroke="#198754" stroke-width="1.6" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M9 12.5 11.5 15 15 9" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        upcoming: '<svg width="22" height="22" fill="none" stroke="#0dcaf0" stroke-width="1.6" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        students: '<svg width="22" height="22" fill="none" stroke="#fd7e14" stroke-width="1.6" viewBox="0 0 24 24"><path d="M5 19v-1a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5v1"/><circle cx="12" cy="8" r="4"/></svg>',
        average: '<svg width="22" height="22" fill="none" stroke="#dc3545" stroke-width="1.6" viewBox="0 0 24 24"><path d="M4 19h16"/><path d="M5 17l4-8 4 6 2-4 4 6" stroke-linecap="round" stroke-linejoin="round"/></svg>'
      };
      return [
        { key:'exams', label:'Ujian', value: props.stats.exams, icon: wrap(svg.exams,'bg-primary-subtle') },
        { key:'sessions', label:'Total Sesi', value: props.stats.sessions, icon: wrap(svg.sessions,'bg-purple-subtle') },
        { key:'active', label:'Aktif', value: props.stats.active_sessions, icon: wrap(svg.active,'bg-success-subtle') },
        { key:'upcoming', label:'Akan Datang', value: props.stats.upcoming_sessions, icon: wrap(svg.upcoming,'bg-info-subtle') },
        { key:'students', label:'Siswa', value: props.stats.students, icon: wrap(svg.students,'bg-warning-subtle') },
        { key:'avg', label:'Rata Nilai', value: props.stats.average_grade, icon: wrap(svg.average,'bg-danger-subtle') },
      ];
    });

    const statusBadge = (s)=> {
      if(s==='Active') return 'badge bg-success';
      if(s==='Ended') return 'badge bg-secondary';
      return 'badge bg-primary';
    };

    onMounted(()=> {
      const actEl = document.getElementById('activityChart');
      if(actEl){
        new Chart(actEl, { type:'line', data:{ labels: props.exam_activity.map(a=>a.date), datasets:[{ label:'Sesi Selesai', data: props.exam_activity.map(a=>a.sessions_finished), tension:.3, fill:false, borderColor:'#0d6efd', backgroundColor:'#0d6efd' }] }, options:{ plugins:{ legend:{ display:false } }, scales:{ y:{ beginAtZero:true, ticks:{ precision:0 } } } } });
      }
      const distEl = document.getElementById('distChart');
      if(distEl){
        new Chart(distEl, { type:'bar', data:{ labels:Object.keys(props.distribution), datasets:[{ label:'Jumlah', data:Object.values(props.distribution), backgroundColor:'#0d6efd' }] }, options:{ plugins:{ legend:{ display:false } }, scales:{ y:{ beginAtZero:true, ticks:{ precision:0 } } } } });
      }
      const sessEl = document.getElementById('sessionChart');
      if(sessEl){
        new Chart(sessEl, { type:'doughnut', data:{ labels:['Upcoming','Ongoing','Ended'], datasets:[{ data:[props.session_status.upcoming, props.session_status.ongoing, props.session_status.ended], backgroundColor:['#0d6efd','#198754','#6c757d'] }] }, options:{ plugins:{ legend:{ position:'bottom' } } } });
      }
    });

    return { statCards, statusBadge };
  }
};
</script>

<style scoped>
.stat-tile .icon-wrap{ width:46px; height:46px; background:#f1f5f9; border-radius:14px; display:flex; align-items:center; justify-content:center; }
.icon-wrap-sm{ width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; }
.legend-dot{ display:inline-block; width:10px; height:10px; border-radius:50%; background:#0d6efd; }
.status-dot{ width:10px; height:10px; border-radius:50%; background:#6c757d; display:inline-block; }
.status-dot.ok{ background:#198754; }
.status-dot.fail{ background:#dc3545; }
</style>
