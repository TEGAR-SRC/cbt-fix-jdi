<template>
  <Head><title>Tryout</title></Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row">
      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h3 class="mb-0">Tryout</h3>
          <Link href="/admin/tryouts/create" class="btn btn-primary">Tambah</Link>
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
                    <th>Durasi (menit)</th>
                    <th>Jadwal</th>
                    <th>Publikasi</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="t in tryouts.data" :key="t.id">
                    <td>{{ t.title }}</td>
                    <td>{{ t.lesson?.title }}</td>
                    <td>{{ t.classroom?.title }}</td>
                    <td>{{ t.duration_minutes }}</td>
                    <td>
                      <div>
                        <div>Mulai: {{ t.start_at ? new Date(t.start_at).toLocaleString() : '-' }}</div>
                        <div>Selesai: {{ t.end_at ? new Date(t.end_at).toLocaleString() : '-' }}</div>
                      </div>
                    </td>
                    <td>
                      <span v-if="t.published_at" class="badge bg-success">Dipublikasikan</span>
                      <span v-else class="badge bg-secondary">Draft</span>
                    </td>
                    <td>
                      <div class="btn-group btn-group-sm">
                        <Link :href="`/admin/tryouts/${t.id}/edit`" class="btn btn-warning">Edit</Link>
                        <Link :href="`/admin/tryouts/${t.id}`" method="delete" as="button" class="btn btn-danger" preserve-scroll confirm="Hapus tryout ini?">Hapus</Link>
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
   props: { tryouts: Object }
 }
 </script>
 <style></style>
