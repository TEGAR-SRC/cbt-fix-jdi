<template>
  <Head><title>Buat Tugas</title></Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row">
      <div class="col-12 col-lg-8">
        <div class="card border-0 shadow">
          <div class="card-body">
            <form @submit.prevent="submit">
              <div class="mb-3">
                <label class="form-label">Judul</label>
                <input v-model="form.title" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea v-model="form.description" class="form-control" rows="5"></textarea>
              </div>
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Mapel</label>
                  <select v-model="form.lesson_id" class="form-select" required>
                    <option :value="''" disabled>Pilih Mapel</option>
                    <option v-for="l in lessons" :key="l.id" :value="l.id">{{ l.title }}</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Kelas</label>
                  <select v-model="form.classroom_id" class="form-select" required>
                    <option :value="''" disabled>Pilih Kelas</option>
                    <option v-for="c in classrooms" :key="c.id" :value="c.id">{{ c.title }}</option>
                  </select>
                </div>
              </div>
              <div class="row g-3 mt-1">
                <div class="col-md-6">
                  <label class="form-label">Jatuh Tempo</label>
                  <input type="datetime-local" v-model="form.due_at" class="form-control" />
                </div>
                <div class="col-md-6">
                  <label class="form-label">Publikasikan Pada</label>
                  <input type="datetime-local" v-model="form.published_at" class="form-control" />
                </div>
              </div>
              <div class="mt-3 d-flex gap-2">
                <Link href="/admin/assignments" class="btn btn-light">Batal</Link>
                <button class="btn btn-primary" type="submit">Simpan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import AdminLayout from '../../../Layouts/Admin.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
export default {
  layout: AdminLayout,
  components: { Head, Link },
  props: { lessons: Array, classrooms: Array },
  setup(){
    const form = useForm({ title:'', description:'', lesson_id:'', classroom_id:'', due_at:'', published_at:'' });
    const submit = () => form.post('/admin/assignments');
    return { form, submit };
  }
}
</script>
<style></style>
