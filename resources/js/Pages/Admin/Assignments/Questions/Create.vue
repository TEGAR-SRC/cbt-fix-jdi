<template>
  <Head><title>Tambah Soal: {{ assignment.title }}</title></Head>
  <div class="container-fluid mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Tambah Soal</h4>
      <Link :href="`/admin/assignments/${assignment.id}/questions`" class="btn btn-light">Kembali</Link>
    </div>
    <div class="card border-0 shadow">
      <div class="card-body">
        <form @submit.prevent="submit">
          <div class="mb-3">
            <label class="form-label">Pertanyaan</label>
            <textarea v-model="form.question" class="form-control" rows="4"></textarea>
            <div class="text-danger small" v-if="errors.question">{{ errors.question }}</div>
          </div>
          <div class="row g-3">
            <div class="col-md-6" v-for="n in 5" :key="n">
              <label class="form-label">Opsi {{ n }}</label>
              <input type="text" class="form-control" v-model="form[`option_${n}`]">
            </div>
          </div>
          <div class="mt-3">
            <label class="form-label">Jawaban Benar</label>
            <select class="form-select" v-model="form.answer">
              <option value="">(Subjektif / belum ditentukan)</option>
              <option v-for="n in 5" :key="n" :value="`option_${n}`">Opsi {{ n }}</option>
            </select>
          </div>
          <div class="mt-3">
            <label class="form-label">Urutan</label>
            <input type="number" class="form-control" v-model="form.order" min="0" />
          </div>
          <div class="mt-4 d-flex">
            <button class="btn btn-primary me-2" :disabled="processing">Simpan</button>
            <Link :href="`/admin/assignments/${assignment.id}/questions`" class="btn btn-light">Batal</Link>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
<script>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '../../../../Layouts/Admin.vue';
export default {
  layout: AdminLayout,
  components:{Head,Link},
  props:{ assignment:Object, errors:Object },
  setup(props){
    const form = useForm({ question:'', option_1:'', option_2:'', option_3:'', option_4:'', option_5:'', answer:'', order:'' });
    const submit = () => form.post(`/admin/assignments/${props.assignment.id}/questions`);
    return { form, submit, processing: form.processing };
  }
}
</script>
<style></style>
