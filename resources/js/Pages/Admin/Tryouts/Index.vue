<template>
  <Head>
    <title>Tryout - Aplikasi Ujian Online</title>
  </Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row">
      <div class="col-md-8">
        <div class="row">
          <div class="col-md-3 col-12 mb-2">
            <Link href="/admin/tryouts/create" class="btn btn-md btn-primary border-0 shadow w-100" type="button"><i class="fa fa-plus-circle"></i> Tambah</Link>
          </div>
          <div class="col-md-9 col-12 mb-2">
            <form @submit.prevent="handleSearch">
              <div class="input-group">
                <input type="text" class="form-control border-0 shadow" v-model="search" placeholder="masukkan kata kunci dan enter...">
                <span class="input-group-text border-0 shadow"><i class="fa fa-search"></i></span>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-1">
      <div class="col-md-12">
        <div class="card border-0 shadow">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                <thead class="thead-dark">
                  <tr class="border-0">
                    <th class="border-0 rounded-start" style="width:5%">No.</th>
                    <th class="border-0">Tryout</th>
                    <th class="border-0">Pelajaran</th>
                    <th class="border-0">Kelas</th>
                    <th class="border-0">Durasi (menit)</th>
                    <th class="border-0">Jadwal</th>
                    <th class="border-0">Publikasi</th>
                    <th class="border-0 rounded-end" style="width:15%">Aksi</th>
                  </tr>
                </thead>
                <div class="mt-2"></div>
                <tbody>
                  <tr v-for="(t,index) in tryouts.data" :key="t.id">
                    <td class="fw-bold text-center">{{ ++index + (tryouts.current_page - 1) * tryouts.per_page }}</td>
                    <td>{{ t.title }}</td>
                    <td>{{ t.lesson?.title }}</td>
                    <td class="text-center">{{ t.classroom?.title }}</td>
                    <td class="text-center">{{ t.duration_minutes }}</td>
                    <td>
                      <div class="small">
                        <div><strong>Mulai:</strong> {{ t.start_at ? new Date(t.start_at).toLocaleString() : '-' }}</div>
                        <div><strong>Selesai:</strong> {{ t.end_at ? new Date(t.end_at).toLocaleString() : '-' }}</div>
                      </div>
                    </td>
                    <td class="text-center">
                      <span v-if="t.published_at" class="badge bg-success">Publish</span>
                      <span v-else class="badge bg-secondary">Draft</span>
                    </td>
                    <td class="text-center">
                      <Link :href="`/admin/tryouts/${t.id}/questions`" class="btn btn-sm btn-primary border-0 shadow me-2" type="button" title="Soal"><i class="fa fa-plus-circle"></i></Link>
                      <Link :href="`/admin/tryouts/${t.id}/enrollments`" class="btn btn-sm btn-warning border-0 shadow me-2 text-white" type="button" title="Enrolle"><i class="fa fa-users"></i></Link>
                      <Link :href="`/admin/tryouts/${t.id}/results`" class="btn btn-sm btn-success border-0 shadow me-2 text-white" type="button" title="Hasil"><i class="fa fa-chart-bar"></i></Link>
                      <Link :href="`/admin/tryouts/${t.id}/edit`" class="btn btn-sm btn-info border-0 shadow me-2" type="button"><i class="fa fa-pencil-alt"></i></Link>
                      <Link :href="`/admin/tryouts/${t.id}`" method="delete" as="button" class="btn btn-sm btn-danger border-0" preserve-scroll confirm="Hapus tryout ini?"><i class="fa fa-trash"></i></Link>
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
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
export default {
  layout: AdminLayout,
  components: { Head, Link, Pagination },
  props: { tryouts: Object, filters: Object },
  setup(props){
    const search = ref(props.filters?.q || '');
    const handleSearch = () => { router.get('/admin/tryouts', { q: search.value }); };
    return { search, handleSearch };
  }
}
</script>
<style></style>
