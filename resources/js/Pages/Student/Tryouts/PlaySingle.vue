<template>
  <Head>
    <title>Tryout No. {{ page }} - {{ tryout.title }}</title>
  </Head>
  <div class="row mb-5">
    <div class="col-md-7">
      <div class="card border-0 shadow">
        <div class="card-header d-flex justify-content-between">
          <h5 class="mb-0">Soal No. <strong>{{ page }}</strong></h5>
          <!-- skor disembunyikan saat pengerjaan -->
        </div>
        <div class="card-body">
          <div v-if="question_active">
            <div v-html="question_active.question.question"></div>
            <table class="mt-3">
              <tbody>
                <tr v-for="(opt, idx) in options" :key="idx">
                  <td width="50" style="padding:6px;">
                    <button :class="btnClass(idx+1)" class="btn btn-sm w-100" @click.prevent="submitAnswer(question_active.question.id, idx+1)">{{ opt }}</button>
                  </td>
                  <td style="padding:6px;" v-html="question_active.question['option_'+(idx+1)]"></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else class="alert alert-danger border-0 shadow">Soal tidak ditemukan.</div>
        </div>
        <div class="card-footer">
          <div class="d-flex justify-content-between">
            <div><button v-if="page>1" @click.prevent="goto(page-1)" class="btn btn-gray-400 btn-sm">Sebelumnya</button></div>
            <div><button v-if="page < all_questions.length" @click.prevent="goto(page+1)" class="btn btn-gray-400 btn-sm">Selanjutnya</button></div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-5">
      <div class="card border-0 shadow">
        <div class="card-header text-center">
          <div class="badge bg-success p-2">{{ question_answered }} dikerjakan</div>
        </div>
        <div class="card-body" style="height:330px;overflow-y:auto;">
          <div class="d-flex flex-wrap">
            <div v-for="(q,i) in all_questions" :key="q.id" style="width:20%;padding:4px;">
              <button @click.prevent="goto(i+1)" :class="numberBtnClass(i)" class="btn btn-sm w-100">{{ i+1 }}</button>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <button @click="finishConfirm" class="btn btn-danger w-100">Selesai Tryout</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import LayoutStudent from '../../../Layouts/Student.vue';
import { Head, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
export default { layout: LayoutStudent, components:{Head}, props:{ tryout:Object, attempt:Object, page:Number, all_questions:Array, question_active:Object, question_answered:Number }, setup(props){
  const options=['A','B','C','D','E'];
  const goto=(p)=> router.get(`/student/tryouts/${props.tryout.id}/${p}`);
  const submitAnswer=(question_id, ans)=>{ router.post(`/student/tryouts/${props.tryout.id}/answer`,{ question_id, answer: ans },{ preserveState:true, preserveScroll:true }); };
  const question_activeAnswer=()=> props.question_active?.answer || 0;
  const btnClass=(ans)=> question_activeAnswer()==ans? 'btn-info':'btn-outline-info';
  const numberBtnClass=(i)=> { const active = (i+1)==props.page; const answered = (props.all_questions[i]?.answer||0) !== 0; return active? 'btn-gray-400': (answered? 'btn-info':'btn-outline-info'); };
  const finishConfirm=()=>{ Swal.fire({title:'Selesai?', text:'Yakin selesai?', icon:'warning', showCancelButton:true, confirmButtonText:'Ya'}).then(r=>{ if(r.isConfirmed){ router.post(`/student/tryouts/${props.tryout.id}/finish`); } }); };
  const beforeUnload = (e)=> {
    if(!props.attempt.finished_at){ e.preventDefault(); e.returnValue=''; }
  };
  window.addEventListener('beforeunload', beforeUnload);
  const visibilityHandler = () => {
    if(document.hidden && !props.attempt.finished_at){
      try {
        const url = `/student/tryouts/${props.tryout.id}/exit`;
        if(navigator.sendBeacon){ navigator.sendBeacon(url, new Blob([], {type:'application/json'})); }
        else { fetch(url, { method:'POST', headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content') }}); }
      } catch(e){}
    }
  };
  const pageHideHandler = () => { if(!props.attempt.finished_at){ visibilityHandler(); } };
  window.addEventListener('pagehide', pageHideHandler);
  document.addEventListener('visibilitychange', visibilityHandler);
  router.on('finish', () => {
    window.removeEventListener('beforeunload', beforeUnload);
    document.removeEventListener('visibilitychange', visibilityHandler);
  window.removeEventListener('pagehide', pageHideHandler);
  });
  return { options, goto, submitAnswer, btnClass, numberBtnClass, finishConfirm, question_activeAnswer };
 } };
</script>
<style></style>
