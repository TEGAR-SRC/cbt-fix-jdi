<template>
  <Head><title>Enrolle Tryout - {{ tryout.title }}</title></Head>
  <div class="container-fluid mb-5 mt-5">
    <Link :href="`/admin/tryouts`" class="btn btn-md btn-primary border-0 shadow mb-3"><i class="fa fa-long-arrow-alt-left me-2"/> Kembali</Link>
    <div class="card border-0 shadow mb-4"><div class="card-body">
      <h5><i class="fa fa-info-circle"></i> Detail Tryout</h5><hr>
      <table class="table table-bordered mb-0"><tbody>
        <tr><td class="fw-bold" style="width:25%">Judul</td><td>{{ tryout.title }}</td></tr>
        <tr><td class="fw-bold">Mapel</td><td>{{ tryout.lesson?.title }}</td></tr>
        <tr><td class="fw-bold">Kelas</td><td>{{ tryout.classroom?.title }}</td></tr>
        <tr><td class="fw-bold">Jumlah Soal</td><td>{{ tryout.questions_count || '-' }}</td></tr>
      </tbody></table>
    </div></div>
    <div class="card border-0 shadow"><div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="mb-0"><i class="fa fa-users"></i> Siswa Terdaftar</h5>
        <Link :href="`/admin/tryouts/${tryout.id}/enrollments/create`" class="btn btn-sm btn-primary"><i class="fa fa-user-plus me-1"/> Tambah</Link>
      </div><hr>
      <div v-if="enrollments.data.length" class="table-responsive">
        <table class="table table-bordered table-centered mb-0">
          <thead><tr><th>#</th><th>Nama</th><th>Kelas</th><th>Aksi</th></tr></thead>
          <tbody>
            <tr v-for="(e,i) in enrollments.data" :key="e.id">
              <td>{{ i+1 }}</td>
              <td>{{ e.student.name }}</td>
              <td>{{ e.student.classroom?.title }}</td>
              <td><Link :href="`/admin/tryouts/${tryout.id}/enrollments/${e.id}`" method="delete" as="button" class="btn btn-sm btn-danger" confirm="Hapus?"><i class="fa fa-trash"/></Link></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else class="text-muted">Belum ada.</div>
    </div></div>
  </div>
</template>
<script>
import AdminLayout from '../../../../Layouts/Admin.vue';
import { Head, Link } from '@inertiajs/vue3';
export default { layout: AdminLayout, components:{Head,Link}, props:{ tryout:Object, enrollments:Object } }
</script>
<style></style>
