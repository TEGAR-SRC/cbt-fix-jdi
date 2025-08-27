<template>
  <Head><title>Edit Tryout - Guru</title></Head>
  <div class="container-fluid mb-5 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Edit Tryout</h5>
      <Link href="/teacher/tryouts" class="btn btn-sm btn-outline-secondary">Kembali</Link>
    </div>
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <form @submit.prevent="submit">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Judul</label>
              <input v-model="form.title" type="text" class="form-control"/>
            </div>
            <div class="col-md-6">
              <label class="form-label">Mapel</label>
              <select v-model="form.lesson_id" class="form-select">
                <option value="" disabled>Pilih Mapel</option>
                <option v-for="l in lessons" :key="l.id" :value="l.id">{{ l.title }}</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Kelas</label>
              <select v-model="form.classroom_id" class="form-select">
                <option value="" disabled>Pilih Kelas</option>
                <option v-for="c in classrooms" :key="c.id" :value="c.id">{{ c.title }}</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Durasi (menit)</label>
              <input v-model.number="form.duration_minutes" type="number" min="1" max="1440" class="form-control"/>
            </div>
            <div class="col-md-6">
              <label class="form-label">Mulai</label>
              <input v-model="form.start_at" type="datetime-local" class="form-control"/>
            </div>
            <div class="col-md-6">
              <label class="form-label">Selesai</label>
              <input v-model="form.end_at" type="datetime-local" class="form-control"/>
            </div>
            <div class="col-12">
              <label class="form-label">Deskripsi</label>
              <textarea v-model="form.description" rows="4" class="form-control"/>
            </div>
            <div class="col-12">
              <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" id="publishNowEditT" v-model="publishNow"/>
                <label class="form-check-label" for="publishNowEditT">Publikasikan sekarang</label>
              </div>
            </div>
          </div>
          <div class="mt-4 d-flex gap-2">
            <button class="btn btn-primary" :disabled="form.processing">Simpan</button>
            <Link :href="`/teacher/tryouts/${tryout.id}`" method="delete" as="button" class="btn btn-danger" preserve-scroll confirm="Hapus tryout ini?">Hapus</Link>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
<script>
import { Head, Link, useForm } from '@inertiajs/vue3';
import TeacherLayout from '../../../Layouts/Teacher.vue';
export default { layout: TeacherLayout, components:{Head,Link}, props:{ tryout:Object, lessons:Array, classrooms:Array }, setup(props){ const form=useForm({ title:props.tryout.title, description:props.tryout.description, lesson_id:props.tryout.lesson_id, classroom_id:props.tryout.classroom_id, duration_minutes:props.tryout.duration_minutes, start_at: props.tryout.start_at ? props.tryout.start_at.substring(0,16):'', end_at: props.tryout.end_at ? props.tryout.end_at.substring(0,16):'', published_at: props.tryout.published_at }); const publishNow = Vue.ref(!!props.tryout.published_at); function submit(){ form.published_at = publishNow.value ? new Date().toISOString() : null; form.put(`/teacher/tryouts/${props.tryout.id}`); } return { form, submit, publishNow }; } }
</script>
<style scoped>.card{border-radius:12px;}</style>
