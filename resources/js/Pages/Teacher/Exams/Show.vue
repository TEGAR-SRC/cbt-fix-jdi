<template>
  <TeacherLayout>
    <div class="container-fluid py-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0">{{ exam.title }}</h4>
        <div>
          <Link :href="`/teacher/exams/${exam.id}/edit`" class="btn btn-outline-primary me-2">Edit</Link>
          <Link :href="`/teacher/exams/${exam.id}/questions/import`" class="btn btn-outline-secondary me-2">Import Soal (Excel)</Link>
          <Link :href="`/teacher/exams/${exam.id}`" method="delete" as="button" class="btn btn-outline-danger" preserve-scroll>Hapus</Link>
        </div>
      </div>
      <div class="mb-3 text-muted">Mapel: {{ exam.lesson?.title }} • Kelas: {{ exam.classroom?.title }} • Durasi: {{ exam.duration }} menit</div>

      <div class="card mb-4">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="fw-semibold">Daftar Soal</div>
              <div class="small text-muted">Kelola soal untuk ujian ini</div>
            </div>
            <Link :href="`/teacher/exams/${exam.id}/questions/create`" class="btn btn-primary">Tambah Soal</Link>
          </div>
          <div class="table-responsive mt-3">
            <table class="table table-hover">
              <thead><tr><th>Pertanyaan</th><th>Jawaban Benar</th><th class="text-end">Aksi</th></tr></thead>
              <tbody>
                <tr v-for="q in exam.questions.data" :key="q.id">
                  <td class="w-75">{{ q.question }}</td>
                  <td>{{ q.answer }}</td>
                  <td class="text-end">
                    <Link :href="`/teacher/exams/${exam.id}/questions/${q.id}/edit`" class="btn btn-sm btn-outline-primary me-2">Edit</Link>
                    <Link :href="`/teacher/exams/${exam.id}/questions/${q.id}`" method="delete" as="button" class="btn btn-sm btn-outline-danger">Hapus</Link>
                  </td>
                </tr>
                <tr v-if="exam.questions.data.length === 0">
                  <td colspan="3" class="text-center text-muted py-4">Belum ada soal</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="d-flex justify-content-end">
            <Pagination :links="exam.questions.links" align="end" />
          </div>
        </div>
      </div>
    </div>
  </TeacherLayout>
</template>
<script>
import { Link } from '@inertiajs/vue3'
import TeacherLayout from '../../../Layouts/Teacher.vue'
import Pagination from '../../../Components/Pagination.vue'
export default { components: { TeacherLayout, Link, Pagination }, props: { exam: Object } }
</script>
