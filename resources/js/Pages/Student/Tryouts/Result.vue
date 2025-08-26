<template>
  <Head><title>Hasil Tryout - {{ tryout.title }}</title></Head>
  <div class="row justify-content-center mt-4">
    <div class="col-md-6">
      <div class="card border-0 shadow">
        <div class="card-header"><h5 class="mb-0">Hasil Tryout</h5></div>
        <div class="card-body text-center">
          <h5 class="fw-bold mb-1">{{ tryout.title }}</h5>
          <p class="text-muted mb-4">{{ tryout.lesson?.title }} • {{ tryout.classroom?.title }}</p>
          <div class="display-5 fw-bold mb-3">{{ attempt.score }}</div>
          <p>Benar: <strong>{{ attempt.total_correct }}</strong> dari {{ attempt.total_questions }} soal</p>
          <Link href="/student/dashboard" class="btn btn-primary mt-3">Kembali ke Dashboard</Link>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import LayoutStudent from '../../../Layouts/Student.vue';
import { Head, Link } from '@inertiajs/vue3';
export default { layout: LayoutStudent, components:{Head,Link}, props:{ tryout:Object, attempt:Object } };
</script>
<style></style>
