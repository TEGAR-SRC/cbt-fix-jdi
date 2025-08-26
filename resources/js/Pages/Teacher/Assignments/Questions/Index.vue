<template>
  <Head><title>Soal Tugas: {{ assignment.title }}</title></Head>
  <div class="container-fluid mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Soal Tugas: {{ assignment.title }}</h4>
      <div>
        <Link :href="`/teacher/assignments`" class="btn btn-light me-2">Kembali</Link>
        <Link :href="`/teacher/assignments/${assignment.id}/questions/create`" class="btn btn-primary">Tambah Soal</Link>
      </div>
    </div>
    <div class="card border-0 shadow">
      <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-2" style="gap:6px;">
          <div class="fw-bold">Daftar Soal</div>
          <form @submit.prevent="submitSearch" class="d-flex" style="gap:6px;">
            <input v-model="search" type="text" class="form-control form-control-sm" placeholder="Cari..."/>
            <button class="btn btn-sm btn-outline-secondary" type="submit"><i class="fa fa-search"/></button>
          </form>
        </div>
        <div v-if="!questions.data.length" class="text-center py-5 text-muted">Belum ada soal.</div>
        <div v-else class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead><tr><th>#</th><th>Pertanyaan</th><th>Jawaban</th><th>Aksi</th></tr></thead>
            <tbody>
              <tr v-for="(q,i) in questions.data" :key="q.id">
                <td>{{ i+1 + (questions.current_page-1)*questions.per_page }}</td>
                <td style="max-width:400px">{{ q.question.slice(0,120) }}</td>
                <td>{{ q.answer || '-' }}</td>
                <td>
                  <div class="btn-group btn-group-sm">
                    <Link :href="`/teacher/assignments/${assignment.id}/questions/${q.id}/edit`" class="btn btn-warning">Edit</Link>
                    <Link :href="`/teacher/assignments/${assignment.id}/questions/${q.id}`" method="delete" as="button" class="btn btn-danger" confirm="Hapus soal ini?">Hapus</Link>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="mt-3"><Pagination :links="questions.links" /></div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { Head, Link, router } from '@inertiajs/vue3';
import TeacherLayout from '../../../../Layouts/Teacher.vue';
import Pagination from '../../../../Components/Pagination.vue';
import { ref } from 'vue';
export default { layout: TeacherLayout, components:{Head,Link,Pagination}, props:{ assignment:Object, questions:Object, filters:Object }, setup(props){ const search=ref(props.filters?.q||''); const submitSearch=()=>{ router.get(`/teacher/assignments/${props.assignment.id}/questions`, { q: search.value }); }; return { search, submitSearch }; } }
</script>
<style></style>
