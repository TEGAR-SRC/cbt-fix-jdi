<template>
  <Head><title>Edit Tryout</title></Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row">
      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h3 class="mb-0">Edit Tryout</h3>
          <Link href="/admin/tryouts" class="btn btn-outline-secondary">Kembali</Link>
        </div>
        <div class="card border-0 shadow">
          <div class="card-body">
            <form @submit.prevent="submit">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Judul</label>
                  <input v-model="form.title" type="text" class="form-control" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label">Mapel</label>
                  <select v-model="form.lesson_id" class="form-select" required>
                    <option value="" disabled>Pilih Mapel</option>
                    <option v-for="l in lessons" :key="l.id" :value="l.id">{{ l.title }}</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Kelas</label>
                  <select v-model="form.classroom_id" class="form-select" required>
                    <option value="" disabled>Pilih Kelas</option>
                    <option v-for="c in classrooms" :key="c.id" :value="c.id">{{ c.title }}</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Durasi (menit)</label>
                  <input v-model.number="form.duration_minutes" type="number" min="1" max="1440" class="form-control" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label">Mulai</label>
                  <input v-model="form.start_at" type="datetime-local" class="form-control" />
                </div>
                <div class="col-md-6">
                  <label class="form-label">Selesai</label>
                  <input v-model="form.end_at" type="datetime-local" class="form-control" />
                </div>
                <div class="col-12">
                  <label class="form-label">Deskripsi</label>
                  <textarea v-model="form.description" rows="4" class="form-control"></textarea>
                </div>
                <div class="col-md-6">
                  <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="publish" v-model="publishNow" />
                    <label class="form-check-label" for="publish">Publikasikan sekarang</label>
                  </div>
                </div>
              </div>
              <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary">Simpan</button>
                <Link :href="`/admin/tryouts/${tryout.id}`" method="delete" as="button" class="btn btn-danger" preserve-scroll confirm="Hapus tryout ini?">Hapus</Link>
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
  props: { tryout: Object, lessons: Array, classrooms: Array },
  setup(props){
    const form = useForm({
      title: props.tryout.title,
      description: props.tryout.description,
      lesson_id: props.tryout.lesson_id,
      classroom_id: props.tryout.classroom_id,
      duration_minutes: props.tryout.duration_minutes,
      start_at: props.tryout.start_at ? props.tryout.start_at.substring(0,16) : '',
      end_at: props.tryout.end_at ? props.tryout.end_at.substring(0,16) : '',
      published_at: props.tryout.published_at,
    });
    const publishNow = Vue.ref(!!props.tryout.published_at);
    function submit(){
      form.published_at = publishNow.value ? new Date().toISOString() : null;
      form.put(`/admin/tryouts/${props.tryout.id}`);
    }
    return { form, submit, publishNow };
  }
}
</script>
<style></style>
