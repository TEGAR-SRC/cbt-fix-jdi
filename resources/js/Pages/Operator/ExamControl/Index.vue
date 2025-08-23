<template>
  <Head>
    <title>Kontrol Ujian - Operator</title>
  </Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row">
      <div class="col-md-12">
        <div v-if="$page.props.session && $page.props.session.success" class="alert alert-success alert-dismissible fade show" role="alert">
          {{$page.props.session.success}}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div v-if="$page.props.session && $page.props.session.error" class="alert alert-danger alert-dismissible fade show" role="alert">
          {{$page.props.session.error}}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div class="card border-0 shadow mb-3">
          <div class="card-body">
            <div class="row g-3 align-items-end">
              <div class="col-md-6">
                <label class="form-label">Sesi Ujian</label>
                <select v-model="selectedSession" class="form-select">
                  <option :value="''">Semua Sesi</option>
                  <option v-for="s in sessions" :key="s.id" :value="s.id">{{ s.title }} — {{ s.exam?.lesson?.title }} / {{ s.exam?.title }}</option>
                </select>
              </div>
              <div class="col-md-6 text-end">
                <div class="text-muted">Kontrol cepat tanpa tampilan monitor.</div>
              </div>
            </div>
          </div>
        </div>

        <div class="card border-0 shadow">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                <thead class="thead-dark">
                  <tr class="border-0">
                    <th class="border-0 rounded-start">Siswa</th>
                    <th class="border-0">Kelas</th>
                    <th class="border-0">Ujian</th>
                    <th class="border-0">Status</th>
                    <th class="border-0">Sisa Waktu</th>
                    <th class="border-0">Aksi</th>
                    <th class="border-0 rounded-end" style="width:220px">Tambah Waktu (menit)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="g in grades.data" :key="g.id">
                    <td>{{ g.student?.name }}</td>
                    <td>{{ g.student?.classroom?.title }}</td>
                    <td>{{ g.exam?.lesson?.title }} / {{ g.exam?.title }}</td>
                    <td>
                      <span v-if="!g.start_time" class="badge bg-warning text-dark">Belum Mulai</span>
                      <span v-else-if="g.start_time && !g.end_time" class="badge bg-info">Sedang Ujian</span>
                      <span v-else class="badge bg-success">Selesai</span>
                      <span v-if="g.status === 'exited'" class="badge bg-danger ms-2">Keluar</span>
                    </td>
                    <td>{{ formatMs(g.duration, g.end_time) }}</td>
                    <td class="d-flex gap-2">
                      <button type="button" class="btn btn-sm btn-danger" @click="confirmStop(g.id)">Stop</button>
                      <button v-if="g.status === 'exited'" type="button" class="btn btn-sm btn-warning" @click="confirmUnlock(g.id)">Buka</button>
                      <button v-if="g.end_time" type="button" class="btn btn-sm btn-primary" @click="confirmReopen(g.id)">Reopen</button>
                    </td>
                    <td>
                      <div class="input-group input-group-sm">
                        <input type="number" min="1" max="180" class="form-control" v-model.number="extraMinutes[g.id]" placeholder="menit" />
                        <button class="btn btn-outline-primary" type="button" @click="confirmAddTime(g.id)">Tambah</button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <Pagination :links="grades.links" align="end" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import OperatorLayout from '../../../Layouts/Operator.vue';
import Pagination from '../../../Components/Pagination.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import Swal from 'sweetalert2';

export default {
  layout: OperatorLayout,
  components: { Head, Link, Pagination },
  props: { sessions: Array, grades: Object, filters: Object },
  setup(props) {
    const selectedSession = ref(props.filters?.exam_session_id || '');
    const extraMinutes = ref({});

    const applyFilter = () => {
      router.get('/operator/exam-control', { 
        exam_session_id: selectedSession.value || undefined 
      }, { 
        preserveState: true, 
        preserveScroll: true, 
        replace: true 
      });
    };
    
    watch(selectedSession, applyFilter);

    const formatMs = (ms, end_time) => {
      if (end_time) return '00:00:00';
      const v = Math.max(0, Number(ms || 0));
      const totalSec = Math.floor(v / 1000);
      const h = Math.floor(totalSec / 3600).toString().padStart(2,'0');
      const m = Math.floor((totalSec % 3600) / 60).toString().padStart(2,'0');
      const s = Math.floor(totalSec % 60).toString().padStart(2,'0');
      return `${h}:${m}:${s}`;
    };

    const postWithToast = (url, successMsg) => {
      router.post(url, {}, { 
        preserveScroll: true, 
        onSuccess: () => {
          Swal.fire({ 
            title: 'Berhasil', 
            text: successMsg, 
            icon: 'success', 
            timer: 1800, 
            showConfirmButton: false 
          });
          router.reload({ only: ['grades'] });
        }
      });
    };

    const confirmStop = (id) => {
      Swal.fire({ 
        title: 'Hentikan ujian?', 
        text: 'Siswa tidak dapat melanjutkan sebelum dibuka kembali.', 
        icon: 'warning', 
        showCancelButton: true, 
        confirmButtonText: 'Ya, Hentikan', 
        cancelButtonText: 'Batal' 
      }).then((r) => { 
        if (r.isConfirmed) postWithToast(`/operator/monitor/${id}/stop`, 'Ujian dihentikan.'); 
      });
    };
    
    const confirmUnlock = (id) => {
      Swal.fire({ 
        title: 'Buka ujian?', 
        text: 'Siswa dapat melanjutkan ujian.', 
        icon: 'question', 
        showCancelButton: true, 
        confirmButtonText: 'Ya, Buka', 
        cancelButtonText: 'Batal' 
      }).then((r) => { 
        if (r.isConfirmed) postWithToast(`/operator/monitor/${id}/unlock`, 'Ujian dibuka.'); 
      });
    };
    
    const confirmReopen = (id) => {
      Swal.fire({ 
        title: 'Buka kembali ujian yang sudah selesai?', 
        text: 'Ujian akan dibuka kembali untuk siswa.', 
        icon: 'question', 
        showCancelButton: true, 
        confirmButtonText: 'Ya, Reopen', 
        cancelButtonText: 'Batal' 
      }).then((r) => { 
        if (r.isConfirmed) postWithToast(`/operator/monitor/${id}/reopen`, 'Ujian selesai dibuka kembali.'); 
      });
    };
    
    const confirmAddTime = (id) => {
      const minutes = extraMinutes.value[id] || 0;
      if (!minutes || minutes < 1) {
        Swal.fire({ 
          title: 'Input tidak valid', 
          text: 'Masukkan menit tambahan (>=1).', 
          icon: 'error', 
          timer: 1500, 
          showConfirmButton: false 
        });
        return;
      }
      Swal.fire({ 
        title: `Tambah waktu ${minutes} menit?`, 
        text: 'Sisa waktu siswa akan bertambah sekarang.', 
        icon: 'question', 
        showCancelButton: true, 
        confirmButtonText: 'Ya, Tambah', 
        cancelButtonText: 'Batal' 
      }).then((r) => { 
        if (r.isConfirmed) { 
          router.post(`/operator/monitor/${id}/add-time`, { extra_minutes: minutes }, { 
            preserveScroll: true, 
            onSuccess: () => { 
              Swal.fire({ 
                title: 'Berhasil', 
                text: `Waktu +${minutes} menit.`, 
                icon: 'success', 
                timer: 1800, 
                showConfirmButton: false 
              }); 
              router.reload({ only: ['grades'] }); 
            } 
          }); 
        } 
      });
    };

    return { 
      selectedSession, 
      applyFilter, 
      formatMs, 
      confirmStop, 
      confirmUnlock, 
      confirmReopen, 
      extraMinutes, 
      confirmAddTime 
    };
  }
}
</script>

<style></style>
