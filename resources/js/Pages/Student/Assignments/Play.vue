<template>
  <Head><title>Kerjakan Tugas - {{ assignment.title }}</title></Head>
  <div class="row mb-3"><div class="col-md-12"><h5 class="fw-bold mb-0">{{ assignment.title }}</h5><small class="text-muted">{{ assignment.lesson?.title }} • {{ assignment.classroom?.title }}</small></div></div>
  <div class="row">
    <div class="col-md-8">
      <div v-for="q in questions" :key="q.id" class="card border-0 shadow mb-3">
        <div class="card-body">
          <div class="fw-semibold" v-html="q.question"></div>
          <ol type="A" class="mt-2">
            <li v-if="q.option_1"><label><input type="radio" name="q{{q.id}}" :value="1" :checked="picked(q.id)==1" @change="choose(q.id,1)"> <span v-html="q.option_1"></span></label></li>
            <li v-if="q.option_2"><label><input type="radio" name="q{{q.id}}" :value="2" :checked="picked(q.id)==2" @change="choose(q.id,2)"> <span v-html="q.option_2"></span></label></li>
            <li v-if="q.option_3"><label><input type="radio" name="q{{q.id}}" :value="3" :checked="picked(q.id)==3" @change="choose(q.id,3)"> <span v-html="q.option_3"></span></label></li>
            <li v-if="q.option_4"><label><input type="radio" name="q{{q.id}}" :value="4" :checked="picked(q.id)==4" @change="choose(q.id,4)"> <span v-html="q.option_4"></span></label></li>
            <li v-if="q.option_5"><label><input type="radio" name="q{{q.id}}" :value="5" :checked="picked(q.id)==5" @change="choose(q.id,5)"> <span v-html="q.option_5"></span></label></li>
          </ol>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-0 shadow">
        <div class="card-body">
          <div class="d-flex justify-content-between"><span>Total Soal</span><strong>{{ questions.length }}</strong></div>
          <div class="d-flex justify-content-between"><span>Benar</span><strong>{{ submission.total_correct }}</strong></div>
          <div class="d-flex justify-content-between"><span>Skor</span><strong>{{ submission.score }}</strong></div>
          <hr>
          <Link :href="`/student/assignments/${assignment.id}/finish`" method="post" as="button" class="btn btn-success w-100" confirm="Selesai?">Selesai</Link>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import LayoutStudent from '../../../Layouts/Student.vue';
import { Head, router, Link } from '@inertiajs/vue3';
export default { layout: LayoutStudent, components:{Head,Link}, props:{ assignment:Object, submission:Object, questions:Array }, setup(props){ const chosen = {}; const picked=(qid)=> chosen[qid]; const choose=(qid, ans)=>{ chosen[qid]=ans; router.post(`/student/assignments/${props.assignment.id}/answer`, { question_id: qid, answer: ans }, { preserveScroll:true, preserveState:true }); }; return { picked, choose }; } }
</script>
<style></style>
