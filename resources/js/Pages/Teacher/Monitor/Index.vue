<template>
  <Head>
    <title>Monitor Ujian - Guru</title>
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
              <div class="col-md-6 text-end">
                <div class="text-muted">Statistik realtime siswa pada sesi.</div>
              </div>
            </div>
          </div>
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-4">
            <div class="card border-0 shadow">
              <div class="card-body text-center">
                <div class="text-muted">Sedang Ujian</div>
                <div class="display-6 fw-bold text-info">{{ stats.in_progress }}</div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card border-0 shadow">
              <div class="card-body text-center">
                <div class="text-muted">Selesai</div>
                <div class="display-6 fw-bold text-success">{{ stats.finished }}</div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card border-0 shadow">
              <div class="card-body text-center">
                <div class="text-muted">Belum Mulai</div>
                <div class="display-6 fw-bold text-warning">{{ stats.not_started }}</div>
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
                    <th class="border-0">Aksi</th>
                    <th class="border-0 rounded-end">Detail</th>
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
                    <td></td>
                    <td>
                      <Link :href="`${basePath}/monitor/${g.id}`" class="btn btn-sm btn-secondary">Lihat</Link>
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
import StaffLayout from '../../../Layouts/Staff.vue';
import Pagination from '../../../Components/Pagination.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

export default {
  layout: StaffLayout,
  components: { Head, Link, Pagination },
  props: { sessions: Array, grades: Object, stats: Object, filters: Object },
  setup(props) {
    const selectedSession = ref(props.filters?.exam_session_id || '');
  const basePath = `/${(window?.app?.page?.url || window.location.pathname).split('/')[1] || 'teacher'}`
  const applyFilter = () => { router.get(`${basePath}/monitor`, { exam_session_id: selectedSession.value || undefined }, { preserveState: true, preserveScroll: true, replace: true }); };
    watch(selectedSession, applyFilter);
    return { selectedSession, applyFilter };
  }
}
</script>

<style></style>
