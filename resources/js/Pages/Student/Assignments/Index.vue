<template>
  <Head><title>Tugas Harian Saya</title></Head>
  <div class="row mb-3">
    <div class="col-md-12">
      <div class="d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Tugas Harian</h4>
        <form @submit.prevent="search" class="d-flex">
          <input v-model="form.q" type="text" class="form-control form-control-sm me-2" placeholder="Cari..." />
          <button class="btn btn-sm btn-primary">Cari</button>
        </form>
      </div>
    </div>
  </div>
  <div class="row" v-if="assignments.length">
    <div class="col-md-6 mb-3" v-for="a in assignments" :key="a.id">
      <div class="card border-0 shadow h-100">
        <div class="card-body">
          <h5 class="mb-1">{{ a.title }}</h5>
          <small class="text-muted">{{ a.lesson?.title }} • {{ a.classroom?.title }}</small>
          <p class="mt-2 mb-2 text-truncate" style="max-height:3.5em;white-space:pre-line;">{{ a.description }}</p>
          <div class="d-flex justify-content-between align-items-center mt-2">
            <span class="badge bg-info" v-if="a.due_at">Deadline: {{ a.due_at }}</span>
            <span class="badge bg-success" v-else>Terbit</span>
            <Link :href="`/student/assignments/${a.id}/confirmation`" class="btn btn-sm btn-success text-white">Kerjakan</Link>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div v-else class="alert alert-warning border-0 shadow">Belum ada tugas.</div>
</template>
<script>
import LayoutStudent from '../../../Layouts/Student.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { reactive } from 'vue';
export default { layout: LayoutStudent, components:{Head,Link}, props:{ assignments:Array, filters:Object }, setup(props){ const form=reactive({ q: props.filters?.q||'' }); const search=()=>{ router.get('/student/assignments', { q: form.q }, { preserveState:true, replace:true }); }; return { form, search }; } }
</script>
<style></style>
