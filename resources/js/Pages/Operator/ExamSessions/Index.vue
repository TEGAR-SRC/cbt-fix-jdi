<template>
  <Head>
    <title>Sesi Ujian - Operator</title>
  </Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row">
      <div class="col-md-8">
        <div class="row">
          <div class="col-md-3 col-12 mb-2">
            <Link href="/operator/exam-sessions/create" class="btn btn-md btn-primary border-0 shadow w-100" type="button">
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
                    <th class="border-0">Judul Sesi</th>
                    <th class="border-0">Ujian</th>
                    <th class="border-0">Waktu Mulai</th>
                    <th class="border-0">Peserta</th>
                    <th class="border-0 rounded-end" style="width:20%">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(session, index) in examSessions.data" :key="index">
                    <td class="fw-bold text-center">{{ ++index + (examSessions.current_page - 1) * examSessions.per_page }}</td>
                    <td>{{ session.title }}</td>
                    <td>{{ session.exam?.title }}</td>
                    <td>{{ session.start_time }}</td>
                    <td>{{ session.enrollees_count || 0 }} siswa</td>
                    <td class="text-center">
                      <Link :href="`/operator/exam-sessions/${session.id}`" class="btn btn-sm btn-secondary border-0 shadow me-1" type="button">
                        <i class="fa fa-eye"></i>
                      </Link>
                      <Link :href="`/operator/exam-sessions/${session.id}/edit`" class="btn btn-sm btn-info border-0 shadow me-1" type="button">
                        <i class="fa fa-pencil-alt"></i>
                      </Link>
                      <button @click.prevent="destroy(session.id)" class="btn btn-sm btn-danger border-0">
                        <i class="fa fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <Pagination :links="examSessions.links" align="end" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import OperatorLayout from '../../../Layouts/Operator.vue';
import Pagination from '../../../Components/Pagination.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Swal from 'sweetalert2';

export default {
  layout: OperatorLayout,
  components: { Head, Link, Pagination },
  props: { examSessions: Object },
  setup() {
    const search = ref('' || (new URL(document.location)).searchParams.get('q'));
    
    const handleSearch = () => {
      router.get('/operator/exam-sessions', { q: search.value });
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
      }).then((result) => {
        if (result.isConfirmed) {
          router.delete(`/operator/exam-sessions/${id}`);
          Swal.fire({
            title: 'Deleted!',
            text: 'Sesi ujian berhasil dihapus.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
          });
        }
      });
    };
    
    return { search, handleSearch, destroy };
  }
}
</script>

<style></style>
