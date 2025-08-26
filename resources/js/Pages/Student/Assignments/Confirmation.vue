<template>
  <Head><title>Konfirmasi Tugas - {{ assignment.title }}</title></Head>
  <div class="row mb-3">
    <div class="col-md-12">
      <Link href="/student/assignments" class="btn btn-sm btn-primary border-0 shadow"><i class="fa fa-long-arrow-alt-left me-1"></i> Kembali</Link>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6 mb-3">
      <div class="card border-0 shadow h-100">
        <div class="card-body">
          <h5><i class="fa fa-file"></i> Deskripsi Tugas</h5>
          <hr />
          <div v-html="assignment.description"></div>
        </div>
      </div>
    </div>
    <div class="col-md-6 mb-3">
      <div class="card border-0 shadow h-100">
        <div class="card-body">
          <h5><i class="fa fa-list-ul"></i> Detail</h5>
          <hr />
          <table class="table table-sm mb-0">
            <tbody>
              <tr><td class="fw-bold">Judul</td><td>{{ assignment.title }}</td></tr>
              <tr><td class="fw-bold">Mapel</td><td>{{ assignment.lesson?.title }}</td></tr>
              <tr><td class="fw-bold">Kelas</td><td>{{ assignment.classroom?.title }}</td></tr>
              <tr><td class="fw-bold">Jumlah Soal</td><td>{{ assignment.questions_count }}</td></tr>
              <tr v-if="assignment.due_at"><td class="fw-bold">Deadline</td><td>{{ assignment.due_at }}</td></tr>
            </tbody>
          </table>
          <div class="mt-3">
            <div v-if="submission && submission.status==='exited' && !submission.finished_at" class="alert alert-warning p-2">Sesi sebelumnya terdeteksi keluar. Hubungi operator untuk membuka kembali.</div>
            <div v-else-if="submission && submission.finished_at" class="alert alert-success p-2">Sudah dikerjakan.</div>
            <Link v-else :href="`/student/assignments/${assignment.id}/start`" method="post" as="button" class="btn btn-success w-100">Mulai / Lanjutkan</Link>
            <Link v-if="submission && submission.finished_at" :href="`/student/assignments/${assignment.id}/result`" class="btn btn-info w-100 mt-2">Lihat Hasil</Link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import LayoutStudent from '../../../Layouts/Student.vue';
import { Head, Link } from '@inertiajs/vue3';
export default { layout:LayoutStudent, components:{Head,Link}, props:{ assignment:Object, submission:Object } };
</script>
<style></style>