<template>
  <Head><title>Detail Hasil - {{ assignment.title }}</title></Head>
  <div class="mb-3 d-flex justify-content-between align-items-center">
    <Link :href="`/operator/assignments/${assignment.id}/results`" class="btn btn-sm btn-outline-secondary"><i class="fa fa-long-arrow-alt-left me-1"/> Kembali</Link>
    <div class="btn-group">
      <button v-if="!submission.finished_at" @click="forceFinish" class="btn btn-sm btn-danger"><i class="fa fa-stop me-1"/> Paksa Selesai</button>
      <button v-else @click="reopen" class="btn btn-sm btn-warning"><i class="fa fa-undo me-1"/> Buka Kunci</button>
    </div>
  </div>
  <div class="card border-0 shadow mb-3">
    <div class="card-header"><strong>Ringkasan</strong></div>
    <div class="card-body">
      <div class="row mb-2"><div class="col-sm-3 text-muted">Siswa</div><div class="col-sm-9">{{ submission.student?.name }}</div></div>
      <div class="row mb-2"><div class="col-sm-3 text-muted">Mulai</div><div class="col-sm-9">{{ submission.started_at ? new Date(submission.started_at).toLocaleString(): '-' }}</div></div>
      <div class="row mb-2"><div class="col-sm-3 text-muted">Selesai</div><div class="col-sm-9">{{ submission.finished_at ? new Date(submission.finished_at).toLocaleString(): '-' }}</div></div>
      <div class="row mb-2"><div class="col-sm-3 text-muted">Benar</div><div class="col-sm-9">{{ submission.total_correct }}/{{ submission.total_questions }}</div></div>
      <div class="row mb-2"><div class="col-sm-3 text-muted">Skor</div><div class="col-sm-9">{{ submission.score }}</div></div>
    </div>
  </div>
  <div class="card border-0 shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
      <strong>Jawaban</strong>
      <div>
        <span class="badge bg-success me-1">Benar</span>
        <span class="badge bg-danger">Salah</span>
      </div>
    </div>
    <div class="card-body p-0">
      <table class="table table-sm mb-0">
        <thead><tr><th style="width:50px;">#</th><th>Pertanyaan</th><th>Jawaban</th><th>Kunci</th><th>Hasil</th></tr></thead>
        <tbody>
          <tr v-for="(a,i) in answers" :key="i">
            <td>{{ i+1 }}</td>
            <td v-html="a.question_text" style="max-width:400px;"></td>
            <td>{{ a.answer_text }}</td>
            <td>{{ a.correct_answer }}</td>
            <td>
              <span class="badge" :class="a.is_correct ? 'bg-success':'bg-danger'">{{ a.is_correct ? 'Benar':'Salah' }}</span>
            </td>
          </tr>
          <tr v-if="!answers.length"><td colspan="5" class="text-center py-3">Tidak ada jawaban.</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
<script>
import { Head, Link, router } from '@inertiajs/vue3';
import OperatorLayout from '../../../../Layouts/Operator.vue';
export default { layout: OperatorLayout, components:{Head,Link}, props:{ assignment:Object, submission:Object, answers:Array }, setup(props){ const forceFinish=()=>{ if(confirm('Yakin paksa selesai?')){ router.post(`/operator/assignments/${props.assignment.id}/results/${props.submission.id}/force-finish`); } }; const reopen=()=>{ if(confirm('Yakin buka kunci?')){ router.post(`/operator/assignments/${props.assignment.id}/results/${props.submission.id}/reopen`); } }; return { forceFinish, reopen }; } };
</script>
<style></style>
