<template>
  <Head>
    <title>Nilai Anak - Orang Tua</title>
  </Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row">
      <div class="col-md-12">
        <div class="card border-0 shadow mb-3">
          <div class="card-body">
            <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Nilai Anak</h5>
          </div>
        </div>
        <div class="card border-0 shadow">
          <div class="card-body">
            <div v-if="!is_parent" class="row align-items-end g-3">
              <div class="col-md-4">
                <label class="form-label">NIS Siswa</label>
                <input v-model="nisn" type="text" class="form-control" placeholder="Masukkan NIS" />
              </div>
              <div class="col-md-4">
                <label class="form-label">Nama (opsional)</label>
                <input v-model="name" type="text" class="form-control" placeholder="Nama siswa" />
              </div>
              <div class="col-md-3">
                <button class="btn btn-primary w-100" @click="filter">Tampilkan</button>
              </div>
            </div>
            <div v-else class="alert alert-info mb-0">
              Akun orang tua terdeteksi. Menampilkan nilai untuk anak yang sudah ditautkan oleh admin.
            </div>
            <div v-if="not_found" class="alert alert-warning mt-3">{{ not_found }}</div>

            <div v-if="grades && grades.length" class="table-responsive mt-4">
              <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                <thead>
                  <tr>
                    <th>Ujian</th>
                    <th>Mapel</th>
                    <th>Kelas</th>
                    <th>Nilai</th>
                    <th>Benar</th>
                    <th>Durasi</th>
                    <th>Waktu</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="g in grades" :key="g.id">
                    <td>{{ g.exam?.title }}</td>
                    <td>{{ g.exam?.lesson?.title }}</td>
                    <td>{{ g.exam?.classroom?.title }}</td>
                    <td><span class="badge bg-success">{{ g.grade }}</span></td>
                    <td>{{ g.total_correct }}</td>
                    <td>{{ fmtDuration(g.duration) }}</td>
                    <td>{{ g.start_time }} - {{ g.end_time }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div v-else class="text-muted mt-4">Belum ada data nilai.</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ParentLayout from '../../../Layouts/Parent.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

export default {
  layout: ParentLayout,
  components: { Head },
  props: { students: Array, grades: Array, selected_student: Number, not_found: String, is_parent: Boolean },
  setup(props){
    const nisn = ref('');
    const name = ref('');
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

  return { nisn, name, filter, fmtDuration };
  }
}
</script>

<style></style>
