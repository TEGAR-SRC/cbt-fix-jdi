<template>
  <Head><title>Buat Tryout - Guru</title></Head>
  <div class="container-fluid mb-5 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Buat Tryout</h5>
      <Link href="/teacher/tryouts" class="btn btn-sm btn-outline-secondary">Kembali</Link>
    </div>
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <form @submit.prevent="submit">
          <div class="mb-3">
            <label class="form-label">Judul</label>
            <input v-model="form.title" type="text" class="form-control"/>
            <div v-if="errors?.title" class="text-danger small mt-1">{{ errors.title }}</div>
          </div>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Mapel</label>
              <select v-model="form.lesson_id" class="form-select">
                <option value="" disabled>Pilih Mapel</option>
                <option v-for="l in lessons" :key="l.id" :value="l.id">{{ l.title }}</option>
              </select>
              <div v-if="errors?.lesson_id" class="text-danger small mt-1">{{ errors.lesson_id }}</div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Kelas</label>
              <select v-model="form.classroom_id" class="form-select">
                <option value="" disabled>Pilih Kelas</option>
                <option v-for="c in classrooms" :key="c.id" :value="c.id">{{ c.title }}</option>
              </select>
              <div v-if="errors?.classroom_id" class="text-danger small mt-1">{{ errors.classroom_id }}</div>
            </div>
            <div class="col-md-4">
              <label class="form-label">Durasi (menit)</label>
              <input type="number" min="1" v-model.number="form.duration_minutes" class="form-control"/>
              <div v-if="errors?.duration_minutes" class="text-danger small mt-1">{{ errors.duration_minutes }}</div>
            </div>
            <div class="col-md-4">
              <label class="form-label">Mulai</label>
              <input type="datetime-local" v-model="form.start_at" class="form-control"/>
              <div v-if="errors?.start_at" class="text-danger small mt-1">{{ errors.start_at }}</div>
            </div>
            <div class="col-md-4">
              <label class="form-label">Selesai</label>
              <input type="datetime-local" v-model="form.end_at" class="form-control"/>
              <div v-if="errors?.end_at" class="text-danger small mt-1">{{ errors.end_at }}</div>
            </div>
          </div>
          <div class="mb-3 mt-3">
            <label class="form-label">Deskripsi</label>
            <textarea rows="4" v-model="form.description" class="form-control"/>
          </div>
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="publishNowTeacher" v-model="publishNow"/>
            <label class="form-check-label" for="publishNowTeacher">Publikasikan sekarang</label>
          </div>
          <div class="d-flex justify-content-end gap-2">
            <button type="reset" class="btn btn-light" @click="resetForm">Reset</button>
            <button type="submit" class="btn btn-primary" :disabled="form.processing">
              <span v-if="form.processing" class="spinner-border spinner-border-sm me-1"/>Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
<script>
import { Head, Link, useForm } from '@inertiajs/vue3';
import TeacherLayout from '../../../Layouts/Teacher.vue';
import { ref } from 'vue';
export default { layout: TeacherLayout, components:{Head,Link}, props:{ lessons:Array, classrooms:Array, errors:Object }, setup(){ const form=useForm({ title:'', description:'', lesson_id:'', classroom_id:'', duration_minutes:60, start_at:'', end_at:'', published_at:null }); const publishNow=ref(false); const submit=()=>{ form.published_at = publishNow.value ? new Date().toISOString() : null; form.post('/teacher/tryouts'); }; const resetForm=()=>{ form.reset(); publishNow.value=false; }; return { form, publishNow, submit, resetForm }; } }
</script>
<style scoped>.card{border-radius:12px;} .form-control,.form-select{border-color:#e2e8f0;} .form-control:focus,.form-select:focus{box-shadow:0 0 0 .15rem rgba(13,110,253,.25);} </style>
