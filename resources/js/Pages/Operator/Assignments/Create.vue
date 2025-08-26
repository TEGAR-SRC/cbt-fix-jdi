<template>
  <Head><title>Buat Tugas - Operator</title></Head>
  <div class="container-fluid mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Buat Tugas</h4>
      <Link href="/operator/assignments" class="btn btn-light">Kembali</Link>
    </div>
    <div class="card border-0 shadow"><div class="card-body">
      <form @submit.prevent="submit">
        <div class="mb-3">
          <label class="form-label">Judul *</label>
          <input v-model="form.title" type="text" class="form-control" required />
        </div>
        <div class="mb-3">
          <label class="form-label">Deskripsi</label>
          <textarea v-model="form.description" class="form-control" rows="3" />
        </div>
        <div class="row">
          <div class="col-md-4 mb-3">
            <label class="form-label">Pelajaran *</label>
            <select v-model="form.lesson_id" class="form-select" required>
              <option value="" disabled>Pilih</option>
              <option v-for="l in lessons" :key="l.id" :value="l.id">{{ l.title }}</option>
            </select>
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Kelas *</label>
            <select v-model="form.classroom_id" class="form-select" required>
              <option value="" disabled>Pilih</option>
              <option v-for="c in classrooms" :key="c.id" :value="c.id">{{ c.title }}</option>
            </select>
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Jatuh Tempo</label>
            <input v-model="form.due_at" type="datetime-local" class="form-control" />
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Tanggal Publikasi</label>
          <input v-model="form.published_at" type="datetime-local" class="form-control" />
        </div>
        <div class="text-end">
          <button class="btn btn-primary" :disabled="form.processing"><i class="fa fa-save me-1"/> Simpan</button>
        </div>
      </form>
    </div></div>
  </div>
</template>
<script>
import { Head, Link, useForm } from '@inertiajs/vue3';
import OperatorLayout from '../../../Layouts/Operator.vue';
export default { layout: OperatorLayout, components:{Head,Link}, props:{ lessons:Array, classrooms:Array }, setup(){ const form=useForm({ title:'',description:'',lesson_id:'',classroom_id:'',due_at:'',published_at:'' }); const submit=()=> form.post('/operator/assignments'); return { form, submit }; } };
</script>
<style></style>
