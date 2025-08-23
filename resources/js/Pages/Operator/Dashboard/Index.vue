<template>
  <OperatorLayout>
    <div class="container-fluid py-4">
      <div class="row g-3 mb-3">
        <div class="col-md-3">
          <div class="card shadow-sm p-3"><div class="text-muted">Siswa</div><div class="fs-3 fw-bold">{{ students }}</div></div>
        </div>
        <div class="col-md-3">
          <div class="card shadow-sm p-3"><div class="text-muted">Ujian</div><div class="fs-3 fw-bold">{{ exams }}</div></div>
        </div>
        <div class="col-md-3">
          <div class="card shadow-sm p-3"><div class="text-muted">Sesi Ujian</div><div class="fs-3 fw-bold">{{ exam_sessions }}</div></div>
        </div>
        <div class="col-md-3">
          <div class="card shadow-sm p-3"><div class="text-muted">Kelas</div><div class="fs-3 fw-bold">{{ classrooms }}</div></div>
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="mb-3">Jadwal Ujian Terdekat</h5>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead><tr><th>Judul</th><th>Mapel</th><th>Status</th><th>Peserta</th><th>Durasi</th></tr></thead>
              <tbody>
                <tr v-for="u in upcoming_exams" :key="u.id">
                  <td>{{ u.title }}</td>
                  <td>{{ u.exam?.lesson || '-' }}</td>
                  <td><span class="badge bg-secondary">{{ u.status }}</span></td>
                  <td>{{ u.participants }}</td>
                  <td>{{ u.duration_min }} menit</td>
                </tr>
                <tr v-if="!upcoming_exams || upcoming_exams.length === 0"><td colspan="5" class="text-center text-muted">Tidak ada jadwal</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </OperatorLayout>
</template>

<script>
import OperatorLayout from '../../../Layouts/Operator.vue'
export default { components: { OperatorLayout }, props: { students: Number, exams: Number, exam_sessions: Number, classrooms: Number, upcoming_exams: Array } }
</script>

<style></style>
