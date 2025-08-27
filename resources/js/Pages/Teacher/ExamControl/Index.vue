<template>
  <Head>
    <title>Kontrol Ujian - Guru</title>
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
import StaffLayout from '../../../Layouts/Staff.vue';
import Pagination from '../../../Components/Pagination.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { useExamControl } from '../../../Composables/useExamControl';

export default {
  layout: StaffLayout,
  components: { Head, Link, Pagination },
  props: { sessions: Array, grades: Object, filters: Object },
  setup(props) {
    const basePath = '/teacher';
    return useExamControl(props, basePath);
  }
}
</script>

<style></style>
