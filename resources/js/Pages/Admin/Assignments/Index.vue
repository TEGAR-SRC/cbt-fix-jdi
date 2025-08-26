<template>
  <Head>
    <title>Tugas Harian - Aplikasi Ujian Online</title>
  </Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row">
      <div class="col-md-8">
        <div class="row">
          <div class="col-md-3 col-12 mb-2">
            <Link href="/admin/assignments/create" class="btn btn-md btn-primary border-0 shadow w-100" type="button">
              <i class="fa fa-plus-circle"></i> Tambah
            </Link>
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
                    <th class="border-0">Tugas</th>
                    <th class="border-0">Pelajaran</th>
                    <th class="border-0">Kelas</th>
                    <th class="border-0">Jatuh Tempo</th>
                    <th class="border-0">Jumlah Soal</th>
                    <th class="border-0">Publikasi</th>
                    <th class="border-0 rounded-end" style="width:18%">Aksi</th>
                  </tr>
                </thead>
                <div class="mt-2"></div>
                <tbody>
                  <tr v-for="(a,index) in assignments.data" :key="a.id">
                    <td class="fw-bold text-center">{{ ++index + (assignments.current_page - 1) * assignments.per_page }}</td>
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
                      <Link :href="`/admin/assignments/${a.id}/questions`" class="btn btn-sm btn-primary border-0 shadow me-2" type="button" title="Soal">
                        <i class="fa fa-plus-circle"></i>
                      </Link>
                      <Link :href="`/admin/assignments/${a.id}/enrollments`" class="btn btn-sm btn-warning border-0 shadow me-2 text-white" type="button" title="Enrolle">
                        <i class="fa fa-users"></i>
                      </Link>
                      <Link :href="`/admin/assignments/${a.id}/results`" class="btn btn-sm btn-success border-0 shadow me-2 text-white" type="button" title="Hasil">
                        <i class="fa fa-chart-bar"></i>
                      </Link>
                      <Link :href="`/admin/assignments/${a.id}/edit`" class="btn btn-sm btn-info border-0 shadow me-2" type="button" title="Edit"><i class="fa fa-pencil-alt"></i></Link>
                      <button @click.prevent="destroy(a.id)" class="btn btn-sm btn-danger border-0" title="Hapus"><i class="fa fa-trash"></i></button>
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
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Swal from 'sweetalert2';
export default {
  layout: AdminLayout,
  components: { Head, Link, Pagination },
  props: { assignments: Object, filters: Object },
  setup(props){
    const search = ref(props.filters?.q || '');
    const handleSearch = () => {
      router.get('/admin/assignments', { q: search.value });
    };
    const destroy = (id) => {
      Swal.fire({
        title: 'Apakah Anda yakin?',
        text: 'Anda tidak akan dapat mengembalikan ini!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then(result => {
        if(result.isConfirmed){
          router.delete(`/admin/assignments/${id}`);
          Swal.fire({
            title: 'Deleted!',
            text: 'Tugas berhasil dihapus!.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false,
          });
        }
      })
    };
    return { search, handleSearch, destroy };
  }
}
</script>
<style></style>
