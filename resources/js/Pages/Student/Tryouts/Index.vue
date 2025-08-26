<template>
  <Head><title>Tryout Saya</title></Head>
  <div class="row mb-3">
    <div class="col-md-12">
      <div class="d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Tryout</h4>
        <form @submit.prevent="search" class="d-flex">
          <input v-model="form.q" type="text" class="form-control form-control-sm me-2" placeholder="Cari..." />
          <button class="btn btn-sm btn-primary">Cari</button>
        </form>
      </div>
    </div>
  </div>
  <div class="row" v-if="tryouts.length">
    <div class="col-md-6 mb-3" v-for="t in tryouts" :key="t.id">
      <div class="card border-0 shadow h-100">
        <div class="card-body">
          <h5 class="mb-1">{{ t.title }}</h5>
          <small class="text-muted">{{ t.lesson?.title }} • {{ t.classroom?.title }}</small>
          <p class="mt-2 mb-2 text-truncate" style="max-height:3.5em;white-space:pre-line;">{{ t.description }}</p>
          <div class="d-flex justify-content-between align-items-center mt-2">
            <span class="badge bg-info" v-if="t.start_at">Mulai: {{ t.start_at }}</span>
            <span class="badge bg-secondary" v-if="t.end_at">Selesai: {{ t.end_at }}</span>
            <Link :href="`/student/tryouts/${t.id}/confirmation`" class="btn btn-sm btn-success text-white">Kerjakan</Link>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div v-else class="alert alert-warning border-0 shadow">Belum ada tryout.</div>
</template>
<script>
import LayoutStudent from '../../../Layouts/Student.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { reactive } from 'vue';
export default { layout: LayoutStudent, components:{Head,Link}, props:{ tryouts:Array, filters:Object }, setup(props){ const form=reactive({ q: props.filters?.q||'' }); const search=()=>{ router.get('/student/tryouts', { q: form.q }, { preserveState:true, replace:true }); }; return { form, search }; } }
</script>
<style></style>
