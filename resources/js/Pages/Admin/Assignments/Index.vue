<template>
  <Head><title>Tugas Harian</title></Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row">
      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h3 class="mb-0">Tugas Harian</h3>
          <Link href="/admin/assignments/create" class="btn btn-primary">Tambah</Link>
        </div>
        <div class="card border-0 shadow">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                <thead class="thead-dark">
                  <tr>
                    <th>Judul</th>
                    <th>Mapel</th>
                    <th>Kelas</th>
                    <th>Jatuh Tempo</th>
                    <th>Publikasi</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="a in assignments.data" :key="a.id">
                    <td>{{ a.title }}</td>
                    <td>{{ a.lesson?.title }}</td>
                    <td>{{ a.classroom?.title }}</td>
                    <td>{{ a.due_at ? new Date(a.due_at).toLocaleString() : '-' }}</td>
                    <td>
                      <span v-if="a.published_at" class="badge bg-success">Dipublikasikan</span>
                      <span v-else class="badge bg-secondary">Draft</span>
                    </td>
                    <td>
                      <div class="btn-group btn-group-sm">
                        <Link :href="`/admin/assignments/${a.id}/questions`" class="btn btn-info">Soal</Link>
                        <Link :href="`/admin/assignments/${a.id}/edit`" class="btn btn-warning">Edit</Link>
                        <Link :href="`/admin/assignments/${a.id}`" method="delete" as="button" class="btn btn-danger" preserve-scroll confirm="Hapus tugas ini?">Hapus</Link>
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
    </div>
  </div>
</template>
<script>
import AdminLayout from '../../../Layouts/Admin.vue';
import Pagination from '../../../Components/Pagination.vue';
import { Head, Link } from '@inertiajs/vue3';
export default {
  layout: AdminLayout,
  components: { Head, Link, Pagination },
  props: { assignments: Object }
}
</script>
<style></style>
