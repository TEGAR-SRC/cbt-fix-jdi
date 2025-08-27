<template>
  <Head><title>AI Import Soal - {{ tryout.title }}</title></Head>
  <div class="mb-3"><Link :href="`/teacher/tryouts/${tryout.id}/questions`" class="btn btn-sm btn-outline-secondary"><i class="fa fa-long-arrow-alt-left me-1"/> Kembali</Link></div>
  <div class="row">
    <div class="col-md-4">
      <div class="card border-0 shadow mb-4">
        <div class="card-header"><strong>Generate Soal AI</strong></div>
        <div class="card-body">
          <form @submit.prevent="submitGenerate">
            <div class="mb-2">
              <label class="form-label">Kategori / Topik</label>
              <input v-model="form.category" type="text" class="form-control form-control-sm" required />
            </div>
            <div class="mb-2">
              <label class="form-label">Jumlah</label>
              <input v-model.number="form.count" type="number" min="1" max="50" class="form-control form-control-sm" required />
            </div>
            <div class="mb-3">
              <label class="form-label">Kesulitan (opsional)</label>
              <select v-model="form.difficulty" class="form-select form-select-sm">
                <option value="">-</option>
                <option value="mudah">Mudah</option>
                <option value="sedang">Sedang</option>
                <option value="sulit">Sulit</option>
              </select>
            </div>
            <button class="btn btn-sm btn-primary" :disabled="processing"><i class="fa fa-robot me-1"/> Generate</button>
          </form>
          <div v-if="error" class="alert alert-danger mt-3 py-2 px-3 small mb-0"><i class="fa fa-exclamation-triangle me-1"/> {{ error }}</div>
        </div>
      </div>
      <div class="alert alert-info small"><i class="fa fa-info-circle me-1"/> Pastikan API key sudah di-set: SUMOPOD_AI_KEY.</div>
    </div>
    <div class="col-md-8">
      <div class="card border-0 shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
          <strong>Preview Soal ({{ preview.length }})</strong>
          <button v-if="preview.length" class="btn btn-sm btn-success" @click="submitConfirm" :disabled="confirming"><i class="fa fa-cloud-upload-alt me-1"/> Simpan Dipilih</button>
        </div>
        <div class="card-body" style="max-height:70vh; overflow:auto;">
          <div v-if="!preview.length" class="text-muted">Belum ada data.</div>
          <div v-for="(p,i) in preview" :key="i" class="border rounded p-2 mb-2">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <strong>#{{ i+1 }}</strong>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" v-model="p.include" />
              </div>
            </div>
            <div v-html="p.question" class="mb-2"/>
            <ol type="A" class="mb-2">
              <li v-for="(opt,oi) in p.options" :key="oi" v-html="opt" :class="{'fw-bold text-success': (oi+1)===p.answer}"/>
            </ol>
            <div class="small">Jawaban: <span class="badge bg-success">{{ String.fromCharCode(64+p.answer) }}</span></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import TeacherLayout from '../../../../Layouts/Teacher.vue';
export default { layout: TeacherLayout, components:{Head,Link}, props:{ tryout:Object, preview:{type:Array,default:()=>[]}, form:Object, error:String }, setup(props){
  const form = useForm({ category: props.form?.category||'', count: props.form?.count||10, difficulty: props.form?.difficulty||'' });
  const submitGenerate=()=>{ form.post(`/teacher/tryouts/${props.tryout.id}/questions/ai-import/generate`); };
  const submitConfirm=()=>{ router.post(`/teacher/tryouts/${props.tryout.id}/questions/ai-import/confirm`, { items: props.preview }); };
  return { form, submitGenerate, submitConfirm, preview: props.preview, processing: form.processing, confirming: form.processing };
} };
</script>
<style scoped>.border{background:#fff}</style>
