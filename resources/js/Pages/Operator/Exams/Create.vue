<template>
  <Head>
    <title>Buat Ujian - Operator</title>
  </Head>
  <div class="container-fluid py-4">
    <Link href="/operator/exams" class="btn btn-md btn-primary border-0 shadow mb-3">
      <i class="fa fa-long-arrow-alt-left me-2"></i> Kembali
    </Link>
    <h4 class="fw-semibold mb-3">Buat Ujian</h4>
    <form @submit.prevent="submit">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Judul</label>
          <input v-model="form.title" class="form-control" required />
          <div v-if="errors.title" class="alert alert-danger mt-2">{{ errors.title }}</div>
        </div>
        <div class="col-md-3">
          <label class="form-label">Mata Pelajaran</label>
          <select v-model="form.lesson_id" class="form-select" required>
            <option v-for="l in lessons" :value="l.id" :key="l.id">{{ l.title }}</option>
          </select>
          <div v-if="errors.lesson_id" class="alert alert-danger mt-2">{{ errors.lesson_id }}</div>
        </div>
        <div class="col-md-3">
          <label class="form-label">Kelas</label>
          <select v-model="form.classroom_id" class="form-select" required>
            <option v-for="c in classrooms" :value="c.id" :key="c.id">{{ c.title }}</option>
          </select>
          <div v-if="errors.classroom_id" class="alert alert-danger mt-2">{{ errors.classroom_id }}</div>
        </div>
        <div class="col-md-3">
          <label class="form-label">Durasi (menit)</label>
          <input v-model.number="form.duration" type="number" min="1" class="form-control" required />
          <div v-if="errors.duration" class="alert alert-danger mt-2">{{ errors.duration }}</div>
        </div>
        <div class="col-12">
          <label class="form-label">Deskripsi</label>
          <textarea v-model="form.description" class="form-control" rows="4" required></textarea>
          <div v-if="errors.description" class="alert alert-danger mt-2">{{ errors.description }}</div>
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
        <Link href="/operator/exams" class="btn btn-light ms-2">Batal</Link>
      </div>
    </form>
  </div>
</template>

<script>
import OperatorLayout from '../../../Layouts/Operator.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';

export default {
  layout: OperatorLayout,
  components: { Head, Link },
  props: { classrooms: Array, lessons: Array, errors: Object },
  setup() {
    const form = reactive({
      title: '',
      lesson_id: '',
      classroom_id: '',
      duration: 60,
      description: '',
      random_question: 'Y',
      random_answer: 'Y',
      show_answer: 'N'
    });

    const submit = () => {
      router.post('/operator/exams', form);
    };

    return { form, submit };
  }
}
</script>

<style></style>
