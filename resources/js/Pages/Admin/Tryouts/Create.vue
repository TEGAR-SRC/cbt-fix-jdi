<template>
  <Head>
    <title>Tambah Tryout - Aplikasi Ujian Online</title>
  </Head>
  <div class="container-fluid mb-5 mt-4">
    <div class="neo-pagehead card border-0 shadow-sm mb-4">
      <div class="card-body py-3 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
          <div class="neo-bullet me-3"></div>
          <div>
            <h5 class="mb-0 fw-bold text-dark">Buat Tryout</h5>
            <small class="text-muted">Atur detail tryout dan jadwal</small>
          </div>
        </div>
        <Link href="/admin/tryouts" class="btn btn-sm btn-outline-secondary"><i class="fa fa-long-arrow-alt-left me-1"></i> Kembali</Link>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <h6 class="text-uppercase text-muted mb-3">Informasi Tryout</h6>
            <form @submit.prevent="submit">
              <div class="mb-4">
                <label>Judul Tryout</label>
                <input type="text" class="form-control" placeholder="Masukkan Judul Tryout" v-model="form.title">
                <div v-if="errors?.title" class="alert alert-danger mt-2">{{ errors.title }}</div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="mb-4">
                    <label>Mata Pelajaran</label>
                    <select class="form-select" v-model="form.lesson_id">
                      <option value="" disabled>Pilih Mapel</option>
                      <option v-for="l in lessons" :key="l.id" :value="l.id">{{ l.title }}</option>
                    </select>
                    <div v-if="errors?.lesson_id" class="alert alert-danger mt-2">{{ errors.lesson_id }}</div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-4">
                    <label>Kelas</label>
                    <select class="form-select" v-model="form.classroom_id">
                      <option value="" disabled>Pilih Kelas</option>
                      <option v-for="c in classrooms" :key="c.id" :value="c.id">{{ c.title }}</option>
                    </select>
                    <div v-if="errors?.classroom_id" class="alert alert-danger mt-2">{{ errors.classroom_id }}</div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="mb-4">
                    <label>Durasi (Menit)</label>
                    <input type="number" min="1" class="form-control" placeholder="Durasi" v-model="form.duration_minutes">
                    <div v-if="errors?.duration_minutes" class="alert alert-danger mt-2">{{ errors.duration_minutes }}</div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="mb-4">
                    <label>Mulai</label>
                    <input type="datetime-local" class="form-control" v-model="form.start_at">
                    <div v-if="errors?.start_at" class="alert alert-danger mt-2">{{ errors.start_at }}</div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="mb-4">
                    <label>Selesai</label>
                    <input type="datetime-local" class="form-control" v-model="form.end_at">
                    <div v-if="errors?.end_at" class="alert alert-danger mt-2">{{ errors.end_at }}</div>
                  </div>
                </div>
              </div>

              <div class="mb-4">
                <label>Deskripsi</label>
                <textarea class="form-control" rows="4" placeholder="Deskripsi singkat" v-model="form.description"></textarea>
                <div v-if="errors?.description" class="alert alert-danger mt-2">{{ errors.description }}</div>
              </div>

              <div class="mb-4 form-check">
                <input class="form-check-input" type="checkbox" id="publishNow" v-model="publishNow">
                <label class="form-check-label" for="publishNow">Publikasikan sekarang</label>
              </div>

              <div class="d-flex justify-content-end">
                <button type="reset" class="btn btn-light border me-2" @click="resetForm">Reset</button>
                <button type="submit" class="btn btn-primary" :disabled="form.processing">
                  <span v-if="form.processing" class="spinner-border spinner-border-sm me-2"></span>Simpan
                </button>
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
import { ref } from 'vue';
export default {
  layout: AdminLayout,
  components: { Head, Link },
  props: { lessons: Array, classrooms: Array, errors: Object },
  setup(){
    const form = useForm({ title:'', description:'', lesson_id:'', classroom_id:'', duration_minutes:60, start_at:'', end_at:'', published_at:null });
    const publishNow = ref(false);
    const submit = () => { form.published_at = publishNow.value ? new Date().toISOString() : null; form.post('/admin/tryouts'); };
    const resetForm = () => { form.reset(); publishNow.value = false; };
    return { form, publishNow, submit, resetForm };
  }
}
</script>
<style>
.neo-pagehead { background:#fff; }
.neo-bullet { width:10px; height:28px; border-radius:8px; background:#0d6efd; box-shadow:0 6px 18px rgba(13,110,253,.25); }
.form-control, .form-select { border-color:#e2e8f0; }
.form-control:focus, .form-select:focus { border-color:#86b7fe; box-shadow:0 0 0 .2rem rgba(13,110,253,.15); }
.card.shadow-sm, .card.shadow-sm .card { box-shadow:0 .25rem .75rem rgba(2,6,23,0.06)!important; }
</style>
