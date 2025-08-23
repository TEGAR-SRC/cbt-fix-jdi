<template>
  <Head>
    <title>Detail Ujian - Operator</title>
  </Head>
  <div class="container-fluid py-4">
    <Link href="/operator/exams" class="btn btn-md btn-primary border-0 shadow mb-3">
      <i class="fa fa-long-arrow-alt-left me-2"></i> Kembali
    </Link>
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="fw-semibold mb-0">{{ exam.title }}</h4>
      <div>
        <Link :href="`/operator/exams/${exam.id}/edit`" class="btn btn-outline-primary me-2">Edit</Link>
        <Link :href="`/operator/exams/${exam.id}/questions/import`" class="btn btn-outline-secondary me-2">Import Soal (Excel)</Link>
        <Link :href="`/operator/exams/${exam.id}/questions/ai-import`" class="btn btn-outline-info me-2">Import Soal (AI)</Link>
        <Link :href="`/operator/exams/${exam.id}`" method="delete" as="button" class="btn btn-outline-danger" preserve-scroll>Hapus</Link>
        <a :href="`/operator/exams/${exam.id}/questions/export`" class="btn btn-sm btn-outline-success"><i class="bi bi-download"></i> Export Soal</a>
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
          <Link :href="`/operator/exams/${exam.id}/questions/create`" class="btn btn-primary">Tambah Soal</Link>
        </div>
        <div class="table-responsive mt-3">
          <table class="table table-hover">
            <thead><tr><th>Pertanyaan</th><th>Jawaban Benar</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
              <tr v-for="q in exam.questions.data" :key="q.id">
                <td class="w-75">{{ q.question }}</td>
                <td>{{ q.answer }}</td>
                <td class="text-end">
                  <Link :href="`/operator/exams/${exam.id}/questions/${q.id}/edit`" class="btn btn-sm btn-outline-primary me-2">Edit</Link>
                  <Link :href="`/operator/exams/${exam.id}/questions/${q.id}`" method="delete" as="button" class="btn btn-sm btn-outline-danger">Hapus</Link>
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
</template>

<script>
import OperatorLayout from '../../../Layouts/Operator.vue';
import { Head, Link } from '@inertiajs/vue3';
import Pagination from '../../../Components/Pagination.vue';

export default {
  layout: OperatorLayout,
  components: { Head, Link, Pagination },
  props: { exam: Object }
}
</script>

<style></style>
