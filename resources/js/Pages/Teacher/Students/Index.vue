<template>
  <Head>
    <title>Siswa - Guru</title>
  </Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row">
      <div class="col-md-8">
        <div class="row">
          <div class="col-md-6 col-12 mb-2 d-flex gap-2">
            <Link :href="`${basePath}/students/create`" class="btn btn-md btn-primary border-0 shadow" type="button"><i class="fa fa-plus-circle"></i> Tambah</Link>
            <Link :href="`${basePath}/students/import`" class="btn btn-md btn-success border-0 shadow" type="button"><i class="fa fa-file-excel"></i> Import</Link>
            <a :href="exportHref" class="btn btn-md btn-outline-success border-0 shadow" type="button"><i class="fa fa-download"></i> Export</a>
          </div>
          <div class="col-md-6 col-12 mb-2">
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
                    <th class="border-0">Nama</th>
                    <th class="border-0">NISN</th>
                    <th class="border-0">Kelas</th>
                    <th class="border-0">Jenis Kelamin</th>
                    <th class="border-0 rounded-end" style="width:15%">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(student, index) in students.data" :key="index">
                    <td class="fw-bold text-center">{{ ++index + (students.current_page - 1) * students.per_page }}</td>
                    <td>{{ student.name }}</td>
                    <td>{{ student.nisn }}</td>
                    <td>{{ student.classroom?.title }}</td>
                    <td class="text-center">{{ student.gender }}</td>
                    <td class="text-center">
                      <Link :href="`${basePath}/students/${student.id}/edit`" class="btn btn-sm btn-info border-0 shadow me-2" type="button"><i class="fa fa-pencil-alt"></i></Link>
                      <button @click.prevent="destroy(student.id)" class="btn btn-sm btn-danger border-0"><i class="fa fa-trash"></i></button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <Pagination :links="students.links" align="end" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import StaffLayout from '../../../Layouts/Staff.vue';
import Pagination from '../../../Components/Pagination.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Swal from 'sweetalert2';

export default {
  layout: StaffLayout,
  components: { Head, Link, Pagination },
  props: { students: Object },
  setup() {
  const search = ref('' || (new URL(document.location)).searchParams.get('q'));
  const basePath = `/${(window?.app?.page?.url || window.location.pathname).split('/')[1] || 'teacher'}`;
  const handleSearch = () => { router.get(`${basePath}/students`, { q: search.value }); };
  const exportHref = computed(() => `${basePath}/students/export${search.value ? `?q=${encodeURIComponent(search.value)}` : ''}`);
  const destroy = (id) => {
      Swal.fire({ title: 'Apakah Anda yakin?', text: 'Anda tidak akan dapat mengembalikan ini!', icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Yes, delete it!' })
  .then((result) => { if (result.isConfirmed) { router.delete(`${basePath}/students/${id}`); Swal.fire({ title: 'Deleted!', text: 'Siswa berhasil dihapus.', icon: 'success', timer: 2000, showConfirmButton: false }); } });
    };
  return { search, handleSearch, destroy, exportHref };
  }
}
</script>

<style>
</style>
