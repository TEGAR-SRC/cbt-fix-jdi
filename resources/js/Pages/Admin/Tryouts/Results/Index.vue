<template>
  <Head><title>Hasil Tryout - {{ tryout.title }}</title></Head>
  <div class="mb-3 d-flex justify-content-between align-items-center">
    <div>
      <Link :href="`/admin/tryouts`" class="btn btn-sm btn-primary"><i class="fa fa-long-arrow-alt-left me-1"/> Kembali</Link>
    </div>
    <form @submit.prevent="searchSubmit" class="d-flex" style="gap:6px;">
      <input v-model="query" type="text" class="form-control form-control-sm" placeholder="Cari siswa..." />
      <button class="btn btn-sm btn-secondary" type="submit"><i class="fa fa-search"/></button>
    </form>
  </div>
  <div class="card border-0 shadow">
    <div class="card-header">
      <strong>Hasil Tryout: {{ tryout.title }}</strong>
    </div>
    <div class="card-body table-responsive p-0">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th style="width:50px;">#</th>
            <th>Nama Siswa</th>
            <th>Mulai</th>
            <th>Selesai</th>
            <th>Benar</th>
            <th>Skor</th>
            <th style="width:90px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(a,i) in attempts.data" :key="a.id">
            <td>{{ i+1 + (attempts.current_page-1)*attempts.per_page }}</td>
            <td>{{ a.student?.name }}</td>
            <td>{{ a.started_at ? new Date(a.started_at).toLocaleString(): '-' }}</td>
            <td>{{ a.finished_at ? new Date(a.finished_at).toLocaleString(): '-' }}</td>
            <td>{{ a.total_correct }}/{{ a.total_questions }}</td>
            <td>{{ a.score }}</td>
            <td><Link :href="`/admin/tryouts/${tryout.id}/results/${a.id}`" class="btn btn-sm btn-info"><i class="fa fa-eye"/></Link></td>
          </tr>
          <tr v-if="!attempts.data.length"><td colspan="7" class="text-center py-3">Belum ada data.</td></tr>
        </tbody>
      </table>
    </div>
    <div class="card-footer"><Pagination :links="attempts.links" /></div>
  </div>
</template>
<script>
import AdminLayout from '../../../../Layouts/Admin.vue';
import Pagination from '../../../../Components/Pagination.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
export default { layout: AdminLayout, components:{Head,Link,Pagination}, props:{ tryout:Object, attempts:Object, filters:Object }, setup(props){ const query=ref(props.filters?.q||''); const searchSubmit=()=>{ router.get(`/admin/tryouts/${props.tryout.id}/results`, { q: query.value }); }; return { query, searchSubmit }; } };
</script>
<style></style>
