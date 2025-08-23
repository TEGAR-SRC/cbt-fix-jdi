<template>
  <Head><title>Orang Tua - Admin</title></Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row">
      <div class="col-md-8">
        <div class="row">
          <div class="col-md-3 col-12 mb-2">
            <Link href="/admin/guardians/create" class="btn btn-md btn-primary border-0 shadow w-100"><i class="fa fa-plus-circle"></i> Tambah</Link>
          </div>
          <div class="col-md-9 col-12 mb-2">
            <form @submit.prevent="handleSearch">
              <div class="input-group">
                <input type="text" class="form-control border-0 shadow" v-model="search" placeholder="kata kunci nama...">
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
                <thead><tr><th>Nama</th><th>Kontak</th><th>Siswa</th><th style="width:15%">Aksi</th></tr></thead>
                <tbody>
                  <tr v-for="g in guardians.data" :key="g.id">
                    <td>{{ g.name }}</td>
                    <td>
                      <div v-if="g.email">{{ g.email }}</div>
                      <div v-if="g.phone">{{ g.phone }}</div>
                    </td>
                    <td>{{ g.students_count }} siswa</td>
                    <td class="text-center">
                      <Link :href="`/admin/guardians/${g.id}/edit`" class="btn btn-sm btn-info border-0 shadow me-2"><i class="fa fa-pencil-alt"></i></Link>
                      <button class="btn btn-sm btn-danger border-0" @click.prevent="destroy(g.id)"><i class="fa fa-trash"></i></button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <Pagination :links="guardians.links" align="end" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import LayoutAdmin from '../../../Layouts/Admin.vue';
import Pagination from '../../../Components/Pagination.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Swal from 'sweetalert2';
export default {
  layout: LayoutAdmin,
  components: { Head, Link, Pagination },
  props: { guardians: Object },
  setup(){
    const search = ref('' || (new URL(document.location)).searchParams.get('q'))
    const handleSearch = () => router.get('/admin/guardians', { q: search.value })
    const destroy = (id) => {
      Swal.fire({ title:'Hapus?', text:'Data tidak dapat dikembalikan!', icon:'warning', showCancelButton:true })
        .then(r=>{ if(r.isConfirmed) router.delete(`/admin/guardians/${id}`) })
    }
    return { search, handleSearch, destroy }
  }
}
</script>
