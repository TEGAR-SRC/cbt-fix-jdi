<template>
  <Head><title>Detail Tryout - Operator</title></Head>
  <div class="container-fluid mb-5 mt-4">
    <Link :href="'/operator/tryouts'" class="btn btn-sm btn-outline-secondary mb-3"><i class="fa fa-long-arrow-alt-left me-1"/>Kembali</Link>
    <div class="card border-0 shadow mb-4">
      <div class="card-body">
        <h5 class="mb-3"><i class="fa fa-edit me-1"/> Detail Tryout</h5>
        <div class="table-responsive">
          <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
            <tbody>
              <tr><td style="width:30%" class="fw-bold">Nama Tryout</td><td>{{ tryout.title }}</td></tr>
              <tr><td class="fw-bold">Mata Pelajaran</td><td>{{ tryout.lesson?.title }}</td></tr>
              <tr><td class="fw-bold">Kelas</td><td>{{ tryout.classroom?.title }}</td></tr>
              <tr><td class="fw-bold">Jumlah Soal</td><td>{{ questions.length }}</td></tr>
              <tr><td class="fw-bold">Durasi (Menit)</td><td>{{ tryout.duration_minutes }}</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="card border-0 shadow">
      <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-2" style="gap:8px;">
          <h5 class="mb-0"><i class="fa fa-question-circle me-1"/> Soal Tryout</h5>
          <form @submit.prevent="submitSearch" class="d-flex" style="gap:6px;">
            <input v-model="search" type="text" class="form-control form-control-sm" placeholder="Cari soal..."/>
            <button class="btn btn-sm btn-outline-secondary" type="submit"><i class="fa fa-search"/></button>
          </form>
        </div>
        <div class="mb-2">
          <Link :href="`/operator/tryouts/${tryout.id}/questions/create`" class="btn btn-sm btn-primary me-2"><i class="fa fa-plus-circle"/> Tambah</Link>
            <Link :href="`/operator/tryouts/${tryout.id}/questions/import`" class="btn btn-sm btn-success me-2"><i class="fa fa-file-import"/> Import</Link>
            <Link :href="`/operator/tryouts/${tryout.id}/questions/ai-import`" class="btn btn-sm btn-warning text-white"><i class="fa fa-robot"/> AI Import</Link>
        </div>
        <div class="table-responsive mt-2" v-if="questions.data.length">
          <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
            <thead class="thead-dark">
              <tr>
                <th style="width:5%">No.</th>
                <th>Soal</th>
                <th style="width:15%">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(q,index) in questions.data" :key="q.id">
                <td class="text-center fw-bold">{{ index+1 + (questions.current_page-1)*questions.per_page }}</td>
                <td>
                  <div class="fw-bold" v-html="q.question"></div>
                  <hr>
                  <ol type="A">
                    <li v-if="q.option_1" v-html="q.option_1" :class="{'text-success fw-bold': q.answer=='1'}"/>
                    <li v-if="q.option_2" v-html="q.option_2" :class="{'text-success fw-bold': q.answer=='2'}"/>
                    <li v-if="q.option_3" v-html="q.option_3" :class="{'text-success fw-bold': q.answer=='3'}"/>
                    <li v-if="q.option_4" v-html="q.option_4" :class="{'text-success fw-bold': q.answer=='4'}"/>
                    <li v-if="q.option_5" v-html="q.option_5" :class="{'text-success fw-bold': q.answer=='5'}"/>
                  </ol>
                </td>
                <td class="text-center">
                  <Link :href="`/operator/tryouts/${tryout.id}/questions/${q.id}/edit`" class="btn btn-sm btn-outline-primary me-1"><i class="fa fa-pencil-alt"/></Link>
                  <Link :href="`/operator/tryouts/${tryout.id}/questions/${q.id}`" method="delete" as="button" class="btn btn-sm btn-outline-danger" confirm="Hapus soal ini?"><i class="fa fa-trash"/></Link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-else class="text-center py-5 text-muted">Belum ada soal.</div>
        <div class="mt-3" v-if="questions.data.length">
          <Pagination :links="questions.links" />
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { Head, Link, router } from '@inertiajs/vue3';
import OperatorLayout from '../../../../Layouts/Operator.vue';
import Pagination from '../../../../Components/Pagination.vue';
import { ref } from 'vue';
export default { layout: OperatorLayout, components:{Head,Link,Pagination}, props:{ tryout:Object, questions:Object, filters:Object }, setup(props){ const search=ref(props.filters?.q||''); const submitSearch=()=>{ router.get(`/operator/tryouts/${props.tryout.id}/questions`, { q: search.value }); }; return { search, submitSearch }; } }
</script>
<style scoped>.table td,.table th{vertical-align:top;}</style>
