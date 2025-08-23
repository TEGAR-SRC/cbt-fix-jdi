<template>
  <Head>
    <title>Detail Monitoring - Dinas</title>
  </Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row">
      <div class="col-md-12">
  <Link :href="$page.url.startsWith('/admin/dinas') ? '/admin/dinas/monitor' : '/dinas/monitor'" class="btn btn-md btn-primary border-0 shadow mb-3"><i class="fa fa-long-arrow-alt-left me-2"></i> Kembali</Link>
        <div class="card border-0 shadow">
          <div class="card-body">
            <h5>Detail Siswa</h5>
            <ul>
              <li><strong>Nama:</strong> {{ grade.student?.name }}</li>
              <li><strong>Kelas:</strong> {{ grade.student?.classroom?.title }}</li>
              <li><strong>Ujian:</strong> {{ grade.exam?.lesson?.title }} / {{ grade.exam?.title }}</li>
              <li><strong>Sesi:</strong> {{ grade.exam_session?.title }}</li>
            </ul>
            <h6 class="mt-3">Jawaban</h6>
            <ol>
              <li v-for="a in answers" :key="a.id">
                <div class="fw-semibold">{{ a.question?.question }}</div>
                <div class="text-muted">Jawaban: {{ a.answer }}</div>
              </li>
            </ol>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import DinasLayout from '../../../Layouts/Dinas.vue';
import { Head, Link } from '@inertiajs/vue3';
export default {
  layout: DinasLayout,
  components: { Head, Link },
  props: { grade: Object, answers: Array }
}
</script>
<style></style>
