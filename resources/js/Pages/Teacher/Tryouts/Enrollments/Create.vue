<template>
  <Head><title>Tambah Enroll Tryout - {{ tryout.title }}</title></Head>
  <div class="container-fluid mb-5 mt-4">
    <Link :href="`/teacher/tryouts/${tryout.id}/enrollments`" class="btn btn-sm btn-outline-secondary mb-3"><i class="fa fa-long-arrow-alt-left me-1"/> Kembali</Link>
    <div class="card border-0 shadow"><div class="card-body">
      <h5 class="mb-2"><i class="fa fa-user-plus me-1"/> Tambah Siswa</h5><hr class="mt-1"/>
      <form @submit.prevent="submit">
        <div class="mb-3">
          <label class="form-label">Pilih Siswa</label>
          <input type="text" v-model="filter" class="form-control form-control-sm mb-2" placeholder="Filter nama..."/>
          <select v-model="form.student_id" class="form-select" size="8">
            <option :value="s.id" v-for="s in filteredStudents" :key="s.id">{{ s.name }} - {{ s.classroom?.title || '-' }}</option>
          </select>
          <div class="text-muted small mt-1">Total: {{ filteredStudents.length }}</div>
          <div class="text-danger small" v-if="form.errors.student_id">{{ form.errors.student_id }}</div>
        </div>
        <button class="btn btn-primary" :disabled="form.processing || !form.student_id"><i class="fa fa-save me-1"/> Simpan</button>
      </form>
    </div></div>
  </div>
</template>
<script>
import { Head, Link, useForm } from '@inertiajs/vue3';
import TeacherLayout from '../../../../Layouts/Teacher.vue';
import { computed, ref } from 'vue';
export default {
  layout: TeacherLayout,
  components:{Head,Link},
  props:{ tryout:Object, students:Array },
  setup(props){
    const form = useForm({ student_id:null });
    const filter = ref('');
    const filteredStudents = computed(()=> props.students.filter(s=> s.name.toLowerCase().includes(filter.value.toLowerCase())));
    const submit=()=>{ form.post(`/teacher/tryouts/${props.tryout.id}/enrollments`); };
    return { form, submit, filter, filteredStudents };
  }
}
</script>
<style scoped></style>
