<template>
  <Head>
    <title>Nilai Anak - Orang Tua</title>
  </Head>
  <div class="container-fluid mb-5 mt-4">
    <div class="d-flex align-items-center mb-4 flex-wrap gap-2">
      <h4 class="mb-0 fw-bold">Nilai Anak</h4>
      <span class="text-muted small">Pemantauan hasil ujian</span>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
      <div class="col-6 col-md-3" v-for="c in summaryCards" :key="c.key">
        <div class="card shadow-sm border-0 h-100 stat-tile">
          <div class="card-body py-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted small mb-1">{{ c.label }}</div>
              <div class="h5 fw-bold mb-0">{{ c.value }}</div>
            </div>
            <div class="icon-wrap" v-html="c.icon"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
          <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Daftar Nilai</h6>
            <button v-if="!is_parent" class="btn btn-sm btn-outline-primary" @click="toggleFilter">Filter NIS</button>
          </div>
          <transition name="fade">
            <div v-if="showFilter && !is_parent" class="border-bottom px-3 pb-3 pt-2 bg-light-subtle small">
              <div class="row g-2 align-items-end">
                <div class="col-md-4">
                  <label class="form-label small mb-1">NIS Siswa</label>
                  <input v-model="nisn" type="text" class="form-control form-control-sm" placeholder="Masukkan NIS" />
                </div>
                <div class="col-md-4">
                  <label class="form-label small mb-1">Nama (opsional)</label>
                  <input v-model="name" type="text" class="form-control form-control-sm" placeholder="Nama siswa" />
                </div>
                <div class="col-md-3">
                  <button class="btn btn-primary btn-sm w-100" @click="filter">Tampilkan</button>
                </div>
              </div>
            </div>
          </transition>
          <div class="card-body">
            <div v-if="is_parent" class="alert alert-info py-2 small">Menampilkan nilai untuk anak yang sudah ditautkan oleh admin.</div>
            <div v-if="not_found" class="alert alert-warning py-2 small mb-3">{{ not_found }}</div>
            <div v-if="grades && grades.length" class="table-responsive">
              <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light small">
                  <tr>
                    <th>Ujian</th>
                    <th>Mapel</th>
                    <th>Kelas</th>
                    <th class="text-center">Nilai</th>
                    <th class="text-center">Benar</th>
                    <th>Durasi</th>
                    <th>Waktu</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="g in grades" :key="g.id">
                    <td class="fw-semibold">{{ g.exam?.title }}</td>
                    <td>{{ g.exam?.lesson?.title }}</td>
                    <td>{{ g.exam?.classroom?.title }}</td>
                    <td class="text-center"><span :class="gradeBadge(g.grade)">{{ g.grade }}</span></td>
                    <td class="text-center">{{ g.total_correct }}</td>
                    <td>{{ fmtDuration(g.duration) }}</td>
                    <td class="small text-muted">{{ g.start_time }}<br/>{{ g.end_time }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div v-else class="text-muted small">Belum ada data nilai.</div>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-header bg-white border-0 py-3"><h6 class="mb-0">Distribusi Nilai</h6></div>
            <div class="card-body">
              <canvas id="distChart" height="180"></canvas>
              <ul class="list-unstyled small mt-3 mb-0">
                <li v-for="(v,k) in summary.distribution" :key="k" class="d-flex justify-content-between"><span>{{ k }}</span><span class="fw-semibold">{{ v }}</span></li>
              </ul>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ParentLayout from '../../../Layouts/Parent.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch, computed, onMounted } from 'vue';
import Chart from 'chart.js/auto';

export default {
  layout: ParentLayout,
  components: { Head },
  props: { students: Array, grades: Array, selected_student: Number, not_found: String, is_parent: Boolean, summary: Object },
  setup(props){
    const nisn = ref('');
    const name = ref('');
    const showFilter = ref(false);
    watch(() => props.selected_student, () => {});

    const filter = () => {
      if (!nisn.value) return;
      router.get('/parent/grades/filter', { nisn: nisn.value, name: name.value }, { preserveState: true });
    }

    const fmtDuration = (sec) => {
      const s = Number(sec || 0);
      const h = Math.floor(s/3600), m = Math.floor((s%3600)/60), ss = s%60;
      return [h,m,ss].map(n=>String(n).padStart(2,'0')).join(':');
    }
    const toggleFilter = () => { showFilter.value = !showFilter.value };

    const summaryCards = computed(()=> {
      const wrap = (svg,bg)=>`<div class='icon-wrap-sm ${bg}'>${svg}</div>`;
      const svg = {
        collection: '<svg width="22" height="22" fill="none" stroke="#0d6efd" stroke-width="1.6" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="14" rx="2"/><path d="M7 8h10M7 12h6" stroke-linecap="round"/></svg>',
        avg: '<svg width="22" height="22" fill="none" stroke="#198754" stroke-width="1.6" viewBox="0 0 24 24"><path d="M4 19h16" stroke-linecap="round"/><path d="M5 17l4-8 4 6 2-4 4 6" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        best: '<svg width="22" height="22" fill="none" stroke="#0dcaf0" stroke-width="1.6" viewBox="0 0 24 24"><path d="m12 3 2.4 5.4 5.6.6-4.2 3.9 1.2 5.6L12 15.8 7 18.5l1.2-5.6L4 9l5.6-.6L12 3z" stroke-linejoin="round"/></svg>',
        worst: '<svg width="22" height="22" fill="none" stroke="#dc3545" stroke-width="1.6" viewBox="0 0 24 24"><path d="M12 5v10" stroke-linecap="round"/><path d="M8 9l4-4 4 4M5 19h14" stroke-linecap="round"/></svg>'
      };
      return [
        { key:'count', label:'Total Ujian', value: props.summary?.count || 0, icon:wrap(svg.collection,'bg-primary-subtle') },
        { key:'average', label:'Rata-Rata', value: props.summary?.average || 0, icon:wrap(svg.avg,'bg-success-subtle') },
        { key:'best', label:'Terbaik', value: props.summary?.best || 0, icon:wrap(svg.best,'bg-info-subtle') },
        { key:'worst', label:'Terendah', value: props.summary?.worst || 0, icon:wrap(svg.worst,'bg-danger-subtle') },
      ];
    });

    const gradeBadge = (val)=> {
      if(val>=90) return 'badge bg-success';
      if(val>=80) return 'badge bg-primary';
      if(val>=70) return 'badge bg-info text-dark';
      if(val>=60) return 'badge bg-warning text-dark';
      return 'badge bg-danger';
    };

    onMounted(()=> {
      const ctx = document.getElementById('distChart');
      if(ctx){
        const dist = props.summary?.distribution || {};
        new Chart(ctx, {
          type:'bar',
          data:{
            labels:Object.keys(dist),
            datasets:[{ label:'Jumlah', data:Object.values(dist), backgroundColor:'#0d6efd' }]
          }, options:{ plugins:{ legend:{ display:false } }, scales:{ y:{ beginAtZero:true, ticks:{ precision:0 } } } }
        });
      }
    });

  return { nisn, name, filter, fmtDuration, summaryCards, gradeBadge, showFilter, toggleFilter };
  }
}
</script>

<style scoped>
.stat-tile .icon-wrap{ width:46px; height:46px; background:#f1f5f9; border-radius:14px; display:flex; align-items:center; justify-content:center; }
.fade-enter-active,.fade-leave-active{ transition: all .25s ease; }
.fade-enter-from,.fade-leave-to{ opacity:0; transform:translateY(-4px); }
.icon-wrap-sm{ width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; }
</style>
