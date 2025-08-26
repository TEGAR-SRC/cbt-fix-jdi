<template>
  <Head><title>Tugas Harian - Guru</title></Head>
  <div class="container-fluid mt-4 mb-5">
    <div class="row align-items-center mb-2">
      <div class="col-md-6 mb-2">
        <form @submit.prevent="handleSearch" class="mb-0">
          <div class="input-group">
            <input type="text" class="form-control border-0 shadow" v-model="search" placeholder="cari judul / deskripsi dan enter...">
            <span class="input-group-text border-0 shadow"><i class="fa fa-search"/></span>
          </div>
        </form>
      </div>
      <div class="col-md-6 mb-2 text-md-end">
        <Link href="/teacher/assignments/create" class="btn btn-primary"><i class="fa fa-plus-circle me-1"/>Tambah</Link>
      </div>
    </div>
    <div class="card border-0 shadow">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
            <thead class="thead-dark">
              <tr>
                <th style="width:5%">No.</th>
                <th>Tugas</th>
                <th>Pelajaran</th>
                <th>Kelas</th>
                <th>Jatuh Tempo</th>
                <th>Jumlah Soal</th>
                <th>Publikasi</th>
                <th style="width:22%">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(a,index) in assignments.data" :key="a.id">
                <td class="text-center fw-bold">{{ ++index + (assignments.current_page - 1) * assignments.per_page }}</td>
                <td>{{ a.title }}</td>
                <td>{{ a.lesson?.title }}</td>
                <td class="text-center">{{ a.classroom?.title }}</td>
                <td class="text-center">{{ a.due_at ? new Date(a.due_at).toLocaleString() : '-' }}</td>
                <td class="text-center">{{ a.questions?.length || 0 }}</td>
                <td class="text-center">
                  <span v-if="a.published_at" class="badge bg-success">Publish</span>
                  <span v-else class="badge bg-secondary">Draft</span>
                </td>
                <td class="text-center">
                  <div class="btn-group btn-group-sm mb-1" role="group">
                    <Link :href="`/teacher/assignments/${a.id}/questions`" class="btn btn-outline-secondary" title="Soal"><i class="fa fa-question"/></Link>
                    <Link :href="`/teacher/assignments/${a.id}/enrollments`" class="btn btn-outline-secondary" title="Enroll"><i class="fa fa-users"/></Link>
                    <Link :href="`/teacher/assignments/${a.id}/results`" class="btn btn-outline-secondary" title="Hasil"><i class="fa fa-chart-bar"/></Link>
                  </div>
                  <div class="btn-group btn-group-sm" role="group">
                    <Link :href="`/teacher/assignments/${a.id}/edit`" class="btn btn-outline-primary" title="Edit"><i class="fa fa-pencil-alt"/></Link>
                    <Link :href="`/teacher/assignments/${a.id}`" method="delete" as="button" class="btn btn-outline-danger" preserve-scroll confirm="Hapus tugas ini?" title="Hapus"><i class="fa fa-trash"/></Link>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <Pagination :links="assignments.links" align="end" />
      </div>
    </div>
  </div>
</template>
<script>
import { Head, Link, router } from '@inertiajs/vue3';
import Pagination from '../../../Components/Pagination.vue';
import TeacherLayout from '../../../Layouts/Teacher.vue';
import { ref } from 'vue';
export default { layout: TeacherLayout, components:{Head,Link,Pagination}, props:{ assignments:Object, filters:Object }, setup(props){ const search = ref(props.filters?.q||''); const handleSearch=()=> router.get('/teacher/assignments',{ q: search.value }); return { search, handleSearch }; } }
</script>
<style scoped>.table td, .table th { vertical-align: middle; }</style>
