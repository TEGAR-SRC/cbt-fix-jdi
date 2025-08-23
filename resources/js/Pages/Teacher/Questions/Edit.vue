<template>
  <TeacherLayout>
    <div class="container-fluid py-4">
      <h4 class="fw-semibold mb-3">Edit Soal</h4>
      <form @submit.prevent="submit">
        <div class="mb-3">
          <label class="form-label">Pertanyaan</label>
          <textarea v-model="form.question" class="form-control" rows="3" required></textarea>
        </div>
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label">Pilihan 1</label><input v-model="form.option_1" class="form-control" required /></div>
          <div class="col-md-6"><label class="form-label">Pilihan 2</label><input v-model="form.option_2" class="form-control" required /></div>
          <div class="col-md-6"><label class="form-label">Pilihan 3</label><input v-model="form.option_3" class="form-control" required /></div>
          <div class="col-md-6"><label class="form-label">Pilihan 4</label><input v-model="form.option_4" class="form-control" required /></div>
          <div class="col-md-6"><label class="form-label">Pilihan 5</label><input v-model="form.option_5" class="form-control" required /></div>
          <div class="col-md-6">
            <label class="form-label">Jawaban Benar</label>
            <select v-model="form.answer" class="form-select" required>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
            </select>
          </div>
        </div>
        <div class="mt-4">
          <button class="btn btn-primary">Update</button>
          <Link :href="`/teacher/exams/${exam.id}`" class="btn btn-light ms-2">Batal</Link>
        </div>
      </form>
    </div>
  </TeacherLayout>
</template>
<script>
import { Link, router } from '@inertiajs/vue3'
import TeacherLayout from '../../../Layouts/Teacher.vue'
export default {
  components: { TeacherLayout, Link },
  props: { exam: Object, question: Object },
  data(){
    return { form: {
      question: this.question?.question || '',
      option_1: this.question?.option_1 || '',
      option_2: this.question?.option_2 || '',
      option_3: this.question?.option_3 || '',
      option_4: this.question?.option_4 || '',
      option_5: this.question?.option_5 || '',
      answer: String(this.question?.answer || '1')
    } }
  },
  methods:{
    submit(){ router.put(`/teacher/exams/${this.exam.id}/questions/${this.question.id}`, this.form) }
  }
}
</script>
