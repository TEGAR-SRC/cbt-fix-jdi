<template>
  <Head>
    <title>Monitoring Ujian - Dinas</title>
  </Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row">
      <div class="col-md-12">
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
            </div>
          </div>
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-3">
            <div class="card border-0 shadow"><div class="card-body text-center"><div class="text-muted">Sedang Ujian</div><div class="display-6 fw-bold text-info">{{ stats.in_progress }}</div></div></div>
          </div>
          <div class="col-md-3">
            <div class="card border-0 shadow"><div class="card-body text-center"><div class="text-muted">Selesai</div><div class="display-6 fw-bold text-success">{{ stats.finished }}</div></div></div>
          </div>
          <div class="col-md-3">
            <div class="card border-0 shadow"><div class="card-body text-center"><div class="text-muted">Belum Mulai</div><div class="display-6 fw-bold text-warning">{{ stats.not_started }}</div></div></div>
          </div>
          <div class="col-md-3">
            <div class="card border-0 shadow"><div class="card-body text-center"><div class="text-muted">Kelas / Siswa</div><div class="display-6 fw-bold">{{ stats.classes }} / {{ stats.students }}</div></div></div>
          </div>
        </div>

        <div class="card border-0 shadow">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                <thead class="thead-dark"><tr class="border-0">
                  <th class="border-0 rounded-start">Siswa</th>
                  <th class="border-0">Kelas</th>
                  <th class="border-0">Ujian</th>
                  <th class="border-0">Status</th>
                  <th class="border-0 rounded-end">Detail</th>
                </tr></thead>
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
                    <td>
                      <Link :href="$page.url.startsWith('/admin/dinas') ? `/admin/dinas/monitor/${g.id}` : `/dinas/monitor/${g.id}`" class="btn btn-sm btn-secondary">Lihat</Link>
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
import AdminLayout from '../../../Layouts/Admin.vue';
import DinasLayout from '../../../Layouts/Dinas.vue';
import Pagination from '../../../Components/Pagination.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

// Determine if this page is being accessed via the Admin proxy routes
const isAdminProxy = typeof window !== 'undefined' && window.location.pathname.startsWith('/admin/dinas');

export default {
  layout: isAdminProxy ? AdminLayout : DinasLayout,
  components: { Head, Link, Pagination },
  props: { sessions: Array, grades: Object, stats: Object, filters: Object },
  setup(props){
    const selectedSession = ref(props.filters?.exam_session_id || '');
    watch(selectedSession, () => {
      const path = (typeof window !== 'undefined' && window.location.pathname.startsWith('/admin/dinas'))
        ? '/admin/dinas/monitor'
        : '/dinas/monitor';
      router.get(path, { exam_session_id: selectedSession.value || undefined }, { preserveState: true, preserveScroll: true, replace: true });
    });
    return { selectedSession };
  }
}
</script>
<style></style>
