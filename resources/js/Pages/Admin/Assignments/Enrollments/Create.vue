<template>
  <Head><title>Tambah Enrolle Tugas - {{ assignment.title }}</title></Head>
  <div class="container-fluid mb-5 mt-5">
    <Link :href="`/admin/assignments/${assignment.id}/enrollments`" class="btn btn-md btn-primary border-0 shadow mb-3"><i class="fa fa-long-arrow-alt-left me-2"/> Kembali</Link>
    <div class="card border-0 shadow"><div class="card-body">
      <h5><i class="fa fa-user-plus"></i> Tambah Siswa</h5><hr>
      <form @submit.prevent="submit">
        <div class="table-responsive mb-3">
          <table class="table table-bordered"><thead><tr><th style="width:5%"><input type="checkbox" v-model="all" @change="toggleAll"/></th><th>Nama</th><th>Kelas</th></tr></thead>
            <tbody>
              <tr v-for="s in students" :key="s.id">
                <td><input type="checkbox" v-model="form.student_id" :value="s.id"/></td>
                <td>{{ s.name }}</td>
                <td>{{ s.classroom?.title }}</td>
              </tr>
            </tbody>
          </table>
          <div v-if="errors.student_id" class="alert alert-danger mt-2">{{ errors.student_id }}</div>
        </div>
        <button class="btn btn-md btn-primary me-2" type="submit">Simpan</button>
        <button class="btn btn-md btn-warning" type="reset" @click="reset">Reset</button>
      </form>
    </div></div>
  </div>
</template>
<script>
import AdminLayout from '../../../../Layouts/Admin.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';
export default { layout: AdminLayout, components:{Head,Link}, props:{ assignment:Object, students:Array, errors:Object }, setup(props){ const form=reactive({ student_id:[] }); const all=ref(false); const toggleAll=()=>{ form.student_id = all.value ? props.students.map(s=>s.id):[]; }; const reset=()=>{ form.student_id=[]; all.value=false; }; const submit=()=>{ router.post(`/admin/assignments/${props.assignment.id}/enrollments`, form); }; return { form, all, toggleAll, reset, submit }; } }
</script>
<style></style>
