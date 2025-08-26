<template>
  <Head><title>Edit Soal: {{ assignment.title }}</title></Head>
  <div class="container-fluid mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Edit Soal</h4>
      <Link :href="`/operator/assignments/${assignment.id}/questions`" class="btn btn-light">Kembali</Link>
    </div>
    <div class="card border-0 shadow"><div class="card-body">
      <form @submit.prevent="submit">
        <div class="mb-3">
          <label class="form-label">Pertanyaan</label>
          <textarea v-model="form.question" class="form-control" rows="4"></textarea>
          <div class="text-danger small" v-if="errors.question">{{ errors.question }}</div>
        </div>
        <div class="row g-3">
          <div class="col-md-6" v-for="n in 5" :key="n">
            <label class="form-label">Opsi {{ n }}</label>
            <input type="text" class="form-control" v-model="form[`option_${n}`]">
          </div>
        </div>
        <div class="mt-3">
          <label class="form-label">Jawaban Benar</label>
          <select class="form-select" v-model="form.answer">
            <option value="">(Subjektif / belum ditentukan)</option>
            <option v-for="n in 5" :key="n" :value="`option_${n}`">Opsi {{ n }}</option>
          </select>
        </div>
        <div class="mt-3">
          <label class="form-label">Urutan</label>
          <input type="number" class="form-control" v-model="form.order" min="0" />
        </div>
        <div class="mt-4 d-flex">
          <button class="btn btn-primary me-2" :disabled="processing">Update</button>
          <Link :href="`/operator/assignments/${assignment.id}/questions`" class="btn btn-light">Batal</Link>
        </div>
      </form>
    </div></div>
  </div>
</template>
<script>
import { Head, Link, useForm } from '@inertiajs/vue3';
import OperatorLayout from '../../../../Layouts/Operator.vue';
export default { layout: OperatorLayout, components:{Head,Link}, props:{ assignment:Object, question:Object, errors:Object }, setup(props){ const form=useForm({question:props.question.question||'',option_1:props.question.option_1||'',option_2:props.question.option_2||'',option_3:props.question.option_3||'',option_4:props.question.option_4||'',option_5:props.question.option_5||'',answer:props.question.answer||'',order:props.question.order||''}); const submit=()=>form.put(`/operator/assignments/${props.assignment.id}/questions/${props.question.id}`); return {form,submit,processing:form.processing}; } }
</script>
<style></style>
