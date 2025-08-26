<template>
  <Head><title>Detail Hasil Tryout - {{ tryout.title }}</title></Head>
  <div class="mb-3"><Link :href="`/admin/tryouts/${tryout.id}/results`" class="btn btn-sm btn-primary"><i class="fa fa-long-arrow-alt-left me-1"/> Kembali</Link></div>
  <div class="card border-0 shadow mb-4">
    <div class="card-header"><strong>Ringkasan</strong></div>
    <div class="card-body">
      <div class="row mb-2"><div class="col-md-3 text-muted">Siswa</div><div class="col-md-9">{{ attempt.student?.name }}</div></div>
      <div class="row mb-2"><div class="col-md-3 text-muted">Mulai</div><div class="col-md-9">{{ attempt.started_at ? new Date(attempt.started_at).toLocaleString() : '-' }}</div></div>
      <div class="row mb-2"><div class="col-md-3 text-muted">Selesai</div><div class="col-md-9">{{ attempt.finished_at ? new Date(attempt.finished_at).toLocaleString() : '-' }}</div></div>
      <div class="row mb-2"><div class="col-md-3 text-muted">Benar</div><div class="col-md-9">{{ attempt.total_correct }}/{{ attempt.total_questions }}</div></div>
      <div class="row mb-2"><div class="col-md-3 text-muted">Skor</div><div class="col-md-9 fw-bold">{{ attempt.score }}</div></div>
    </div>
  </div>
  <div class="card border-0 shadow">
    <div class="card-header"><strong>Jawaban Soal</strong></div>
    <div class="card-body table-responsive p-0">
      <table class="table table-sm table-bordered mb-0">
        <thead>
          <tr><th style="width:60px;">No</th><th>Soal</th><th>Jawaban Siswa</th><th>Kunci</th><th>Benar?</th></tr>
        </thead>
        <tbody>
          <tr v-for="(a,i) in answers" :key="a.id">
            <td>{{ i+1 }}</td>
            <td v-html="a.question.question"/>
            <td v-html="a.answer ? a.question['option_'+a.answer] : '<em>-</em>'"/>
            <td v-html="a.question['option_'+a.question.answer]"/>
            <td>
              <span v-if="a.is_correct" class="badge bg-success">Benar</span>
              <span v-else class="badge bg-danger">Salah</span>
            </td>
          </tr>
          <tr v-if="!answers.length"><td colspan="5" class="text-center py-3">Tidak ada jawaban.</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
<script>
import AdminLayout from '../../../../Layouts/Admin.vue';
import { Head, Link } from '@inertiajs/vue3';
export default { layout: AdminLayout, components:{Head,Link}, props:{ tryout:Object, attempt:Object, answers:Array } };
</script>
<style></style>
