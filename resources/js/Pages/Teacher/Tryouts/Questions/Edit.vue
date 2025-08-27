<template>
  <Head><title>Edit Soal Tryout - Guru</title></Head>
  <div class="container-fluid mb-5 mt-4">
    <Link :href="`/teacher/tryouts/${tryout.id}/questions`" class="btn btn-sm btn-outline-secondary mb-3"><i class="fa fa-long-arrow-alt-left me-1"/>Kembali</Link>
    <div class="card border-0 shadow">
      <div class="card-body">
        <h5 class="mb-3">Edit Soal</h5>
        <form @submit.prevent="submit">
          <div class="mb-3">
            <label class="form-label">Pertanyaan</label>
            <textarea v-model="form.question" rows="4" class="form-control"/>
            <div v-if="errors?.question" class="text-danger small mt-1">{{ errors.question }}</div>
          </div>
          <div class="row g-3">
            <div class="col-md-6" v-for="n in 5" :key="n">
              <label class="form-label">Opsi {{ n }}</label>
              <textarea :placeholder="`Opsi ${n}`" v-model="form[`option_${n}`]" rows="2" class="form-control"/>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-md-4">
              <label class="form-label">Jawaban Benar (Nomor)</label>
              <select v-model="form.answer" class="form-select">
                <option value="">- pilih -</option>
                <option v-for="n in 5" :key="'ans'+n" :value="String(n)">{{ n }}</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Urutan</label>
              <input type="number" v-model.number="form.order" class="form-control"/>
            </div>
          </div>
          <div class="mt-4 d-flex gap-2">
            <button class="btn btn-primary" :disabled="form.processing">Simpan</button>
            <Link :href="`/teacher/tryouts/${tryout.id}/questions/${question.id}`" method="delete" as="button" class="btn btn-danger" confirm="Hapus soal ini?">Hapus</Link>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
<script>
import { Head, Link, useForm } from '@inertiajs/vue3';
import TeacherLayout from '../../../../Layouts/Teacher.vue';
export default { layout: TeacherLayout, components:{Head,Link}, props:{ tryout:Object, question:Object, errors:Object }, setup(props){ const form=useForm({ question:props.question.question||'', option_1:props.question.option_1||'', option_2:props.question.option_2||'', option_3:props.question.option_3||'', option_4:props.question.option_4||'', option_5:props.question.option_5||'', answer:props.question.answer||'', order:props.question.order||'' }); const submit=()=> form.put(`/teacher/tryouts/${props.tryout.id}/questions/${props.question.id}`); return { form, submit }; } }
</script>
<style scoped></style>
