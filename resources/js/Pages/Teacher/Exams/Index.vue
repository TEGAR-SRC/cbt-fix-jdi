<template>
  <TeacherLayout>
    <div class="container-fluid py-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0">Ujian Saya</h4>
        <Link href="/teacher/exams/create" class="btn btn-primary">Buat Ujian</Link>
      </div>

      <form class="row g-2 mb-3" @submit.prevent="search">
        <div class="col-sm-8 col-md-6 col-lg-4">
          <input v-model="q" class="form-control" placeholder="Cari judul ujian..." />
        </div>
        <div class="col-auto">
          <button class="btn btn-outline-secondary">Cari</button>
        </div>
      </form>

      <div class="card">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>Judul</th>
                <th>Kelas</th>
                <th>Durasi</th>
                <th class="text-end">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="exam in exams.data" :key="exam.id">
                <td class="fw-medium">{{ exam.title }}</td>
                <td>{{ exam.classroom?.title }}</td>
                <td>{{ exam.duration }} menit</td>
                <td class="text-end">
                  <Link :href="`/teacher/exams/${exam.id}`" class="btn btn-sm btn-outline-secondary me-2">Detail</Link>
                  <Link :href="`/teacher/exams/${exam.id}/edit`" class="btn btn-sm btn-outline-primary me-2">Edit</Link>
                  <Link :href="`/teacher/exams/${exam.id}`" method="delete" as="button" class="btn btn-sm btn-outline-danger">Hapus</Link>
                </td>
              </tr>
              <tr v-if="exams.data.length === 0">
                <td colspan="4" class="text-center text-muted py-4">Belum ada ujian</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="card-footer bg-transparent">
          <Pagination :links="exams.links" align="end" />
        </div>
      </div>
    </div>
  </TeacherLayout>
  
</template>
<script>
import { Link, router } from '@inertiajs/vue3'
import TeacherLayout from '../../../Layouts/Teacher.vue'
import Pagination from '../../../Components/Pagination.vue'
export default { 
  components: { TeacherLayout, Link, Pagination },
  props: { exams: Object, subject: String },
  data(){
    return { q: new URLSearchParams(location.search).get('q') || '' }
  },
  methods:{
    search(){
      const params = this.q ? { q: this.q } : {}
      router.get('/teacher/exams', params, { preserveState: true, replace: true })
    }
  }
}
</script>
