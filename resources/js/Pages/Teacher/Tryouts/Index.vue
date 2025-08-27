<template>
  <Head><title>Tryout - Guru</title></Head>
  <div class="container-fluid mb-5 mt-4">
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
        <Link href="/teacher/tryouts/create" class="btn btn-primary"><i class="fa fa-plus-circle me-1"/>Tambah</Link>
      </div>
    </div>
    <div class="card border-0 shadow">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
            <thead class="thead-dark">
              <tr>
                <th style="width:5%">No.</th>
                <th>Tryout</th>
                <th>Pelajaran</th>
                <th>Kelas</th>
                <th>Durasi</th>
                <th>Jadwal</th>
                <th>Publikasi</th>
                <th style="width:22%">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(t,index) in tryouts.data" :key="t.id">
                <td class="text-center fw-bold">{{ ++index + (tryouts.current_page - 1) * tryouts.per_page }}</td>
                <td>{{ t.title }}</td>
                <td>{{ t.lesson?.title }}</td>
                <td class="text-center">{{ t.classroom?.title }}</td>
                <td class="text-center">{{ t.duration_minutes }} mnt</td>
                <td class="small">
                  <div><strong>Mulai:</strong> {{ t.start_at ? new Date(t.start_at).toLocaleString() : '-' }}</div>
                  <div><strong>Selesai:</strong> {{ t.end_at ? new Date(t.end_at).toLocaleString() : '-' }}</div>
                </td>
                <td class="text-center">
                  <span v-if="t.published_at" class="badge bg-success">Publish</span>
                  <span v-else class="badge bg-secondary">Draft</span>
                </td>
                <td class="text-center">
                  <div class="btn-group btn-group-sm mb-1" role="group">
                    <Link :href="`/teacher/tryouts/${t.id}/questions`" class="btn btn-outline-secondary" title="Soal"><i class="fa fa-question"/></Link>
                    <Link :href="`/teacher/tryouts/${t.id}/enrollments`" class="btn btn-outline-secondary" title="Enroll"><i class="fa fa-users"/></Link>
                    <Link :href="`/teacher/tryouts/${t.id}/results`" class="btn btn-outline-secondary" title="Hasil"><i class="fa fa-chart-bar"/></Link>
                  </div>
                  <div class="btn-group btn-group-sm" role="group">
                    <Link :href="`/teacher/tryouts/${t.id}/edit`" class="btn btn-outline-primary" title="Edit"><i class="fa fa-pencil-alt"/></Link>
                    <Link :href="`/teacher/tryouts/${t.id}`" method="delete" as="button" class="btn btn-outline-danger" preserve-scroll confirm="Hapus tryout ini?" title="Hapus"><i class="fa fa-trash"/></Link>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <Pagination :links="tryouts.links" align="end" />
      </div>
    </div>
  </div>
</template>
<script>
import { Head, Link, router } from '@inertiajs/vue3';
import Pagination from '../../../Components/Pagination.vue';
import TeacherLayout from '../../../Layouts/Teacher.vue';
import { ref } from 'vue';
export default { layout: TeacherLayout, components:{Head,Link,Pagination}, props:{ tryouts:Object, filters:Object }, setup(props){ const search = ref(props.filters?.q||''); const handleSearch=()=> router.get('/teacher/tryouts',{ q: search.value }); return { search, handleSearch }; } }
</script>
<style scoped>.table td, .table th { vertical-align: middle; }</style>
