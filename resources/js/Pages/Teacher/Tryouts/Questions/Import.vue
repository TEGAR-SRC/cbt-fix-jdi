<template>
  <Head><title>Import Soal Tryout - {{ tryout.title }}</title></Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row"><div class="col-md-12">
      <Link :href="`/teacher/tryouts/${tryout.id}/questions`" class="btn btn-md btn-primary border-0 shadow mb-3 me-3"><i class="fa fa-long-arrow-alt-left me-2"/> Kembali</Link>
      <a href="/assets/excel/questions.xls" target="_blank" class="btn btn-md btn-success border-0 shadow mb-3 text-white me-2"><i class="fa fa-file-excel me-2"/> Contoh Format</a>
      <Link :href="`/teacher/tryouts/${tryout.id}/questions/ai-import`" class="btn btn-md btn-warning border-0 shadow mb-3 text-white"><i class="fa fa-robot me-2"/> AI Import</Link>
      <div class="card border-0 shadow"><div class="card-body">
        <h5><i class="fa fa-file-import"/> Import Soal Tryout</h5><hr>
        <form @submit.prevent="submit">
          <div class="mb-4">
            <label>File Excel</label>
            <input type="file" class="form-control" @input="form.file = $event.target.files[0]">
            <div v-if="errors.file" class="alert alert-danger mt-2">{{ errors.file }}</div>
            <div v-if="errors[0]" class="alert alert-danger mt-2">{{ errors[0] }}</div>
          </div>
          <button type="submit" class="btn btn-md btn-primary border-0 shadow me-2" :disabled="processing"><i class="fa fa-upload me-1"/> Upload</button>
          <button type="reset" class="btn btn-md btn-warning border-0 shadow" @click="form.file=null">Reset</button>
        </form>
      </div></div>
    </div></div>
  </div>
</template>
<script>
import { Head, Link, router } from '@inertiajs/vue3';
import TeacherLayout from '../../../../Layouts/Teacher.vue';
import { reactive } from 'vue';
export default { layout: TeacherLayout, components:{Head,Link}, props:{ tryout:Object, errors:Object }, setup(props){ const form=reactive({file:null}); const processing=false; const submit=()=>{ router.post(`/teacher/tryouts/${props.tryout.id}/questions/import`, { file: form.file }, { forceFormData:true }); }; return { form, submit, processing }; } };
</script>
<style></style>
