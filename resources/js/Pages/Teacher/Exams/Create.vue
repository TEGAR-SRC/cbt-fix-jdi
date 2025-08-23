<template>
  <StaffLayout>
    <div class="container-fluid py-4">
      <h4 class="fw-semibold mb-3">Buat Ujian</h4>
      <form @submit.prevent="submit">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Judul</label>
            <input v-model="form.title" class="form-control" required />
          </div>
          <div class="col-md-3">
            <label class="form-label">Mata Pelajaran</label>
            <select v-model="form.lesson_id" class="form-select" required>
              <option v-for="l in lessons" :value="l.id" :key="l.id">{{ l.title }}</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Kelas</label>
            <select v-model="form.classroom_id" class="form-select" required>
              <option v-for="c in classrooms" :value="c.id" :key="c.id">{{ c.title }}</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Durasi (menit)</label>
            <input v-model.number="form.duration" type="number" min="1" class="form-control" required />
          </div>
          <div class="col-12">
            <label class="form-label">Deskripsi</label>
            <textarea v-model="form.description" class="form-control" rows="4" required></textarea>
          </div>
          <div class="col-md-4">
            <label class="form-label">Acak Soal</label>
            <select v-model="form.random_question" class="form-select" required>
              <option value="Y">Ya</option>
              <option value="N">Tidak</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Acak Jawaban</label>
            <select v-model="form.random_answer" class="form-select" required>
              <option value="Y">Ya</option>
              <option value="N">Tidak</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Tampilkan Kunci</label>
            <select v-model="form.show_answer" class="form-select" required>
              <option value="N">Tidak</option>
              <option value="Y">Ya</option>
            </select>
          </div>
        </div>
        <div class="mt-4">
          <button class="btn btn-primary">Simpan</button>
          <Link :href="`${basePath}/exams`" class="btn btn-light ms-2">Batal</Link>
        </div>
      </form>
    </div>
  </StaffLayout>
</template>
<script>
import { Link, router } from '@inertiajs/vue3'
import StaffLayout from '../../../Layouts/Staff.vue'
export default {
  components: { StaffLayout, Link },
  props: { classrooms: Array, lessons: Array },
  data(){
    return { form: { title:'', lesson_id:'', classroom_id:'', duration:60, description:'', random_question:'Y', random_answer:'Y', show_answer:'N' } }
  },
  computed: {
    basePath(){
      const seg = (this.$page?.url || window.location.pathname).split('/')[1] || 'teacher'
      return `/${seg}`
    }
  },
  methods:{
    submit(){ router.post(`${this.basePath}/exams`, this.form) }
  }
}
</script>
