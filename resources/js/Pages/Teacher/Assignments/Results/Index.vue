<template>
  <Head><title>Hasil Tugas - {{ assignment.title }}</title></Head>
  <div class="mb-3 d-flex justify-content-between align-items-center">
    <Link :href="`/teacher/assignments`" class="btn btn-sm btn-outline-secondary"><i class="fa fa-long-arrow-alt-left me-1"/> Kembali</Link>
    <form @submit.prevent="submitSearch" class="d-flex" style="gap:6px;">
      <input v-model="search" type="text" class="form-control form-control-sm" placeholder="Cari siswa..."/>
      <button class="btn btn-sm btn-outline-secondary" type="submit"><i class="fa fa-search"/></button>
    </form>
  </div>
  <div class="card border-0 shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
      <strong>Hasil Tugas: {{ assignment.title }}</strong>
      <a :href="`/teacher/assignments/${assignment.id}/results-export`" class="btn btn-sm btn-success"><i class="fa fa-download me-1"/> Export CSV</a>
    </div>
    <div class="card-body table-responsive p-0">
      <table class="table table-striped mb-0">
        <thead><tr><th style="width:50px;">#</th><th>Nama Siswa</th><th>Mulai</th><th>Selesai</th><th>Benar</th><th>Skor</th><th style="width:100px;">Aksi</th></tr></thead>
        <tbody>
          <tr v-for="(s,i) in submissions.data" :key="s.id">
            <td>{{ i+1 + (submissions.current_page-1)*submissions.per_page }}</td>
            <td>{{ s.student?.name }}</td>
            <td>{{ s.started_at ? new Date(s.started_at).toLocaleString() : '-' }}</td>
            <td>{{ s.finished_at ? new Date(s.finished_at).toLocaleString() : '-' }}</td>
            <td>{{ s.total_correct }}/{{ s.total_questions }}</td>
            <td>{{ s.score }}</td>
            <td>
              <Link :href="`/teacher/assignments/${assignment.id}/results/${s.id}`" class="btn btn-sm btn-info"><i class="fa fa-eye"/></Link>
            </td>
          </tr>
          <tr v-if="!submissions.data.length"><td colspan="7" class="text-center py-3">Belum ada data.</td></tr>
        </tbody>
      </table>
    </div>
    <div class="card-footer"><Pagination :links="submissions.links" /></div>
  </div>
</template>
<script>
import { Head, Link, router } from '@inertiajs/vue3';
import TeacherLayout from '../../../../Layouts/Teacher.vue';
import Pagination from '../../../../Components/Pagination.vue';
import { ref } from 'vue';
export default { layout: TeacherLayout, components:{Head,Link,Pagination}, props:{ assignment:Object, submissions:Object, filters:Object }, setup(props){ const search=ref(props.filters?.q||''); const submitSearch=()=>{ router.get(`/teacher/assignments/${props.assignment.id}/results`, { q: search.value }); }; return { search, submitSearch }; } };
</script>
<style></style>
