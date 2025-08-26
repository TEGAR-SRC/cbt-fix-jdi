<template>
  <Head>
    <title>Detail Tryout - Aplikasi Ujian Online</title>
  </Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row">
      <div class="col-md-12">
        <Link href="/admin/tryouts" class="btn btn-md btn-primary border-0 shadow mb-3" type="button"><i class="fa fa-long-arrow-alt-left me-2"></i> Kembali</Link>
        <div class="card border-0 shadow mb-4">
          <div class="card-body">
            <h5><i class="fa fa-edit"></i> Detail Tryout</h5>
            <hr>
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
            <h5><i class="fa fa-question-circle"></i> Soal Tryout</h5>
            <hr>
            <Link :href="`/admin/tryouts/${tryout.id}/questions/create`" class="btn btn-md btn-primary border-0 shadow me-2" type="button"><i class="fa fa-plus-circle"></i> Tambah</Link>
            <Link :href="`/admin/tryouts/${tryout.id}/questions/import`" class="btn btn-md btn-success border-0 shadow text-white me-2" type="button"><i class="fa fa-file-excel"></i> Import</Link>
            <Link :href="`/admin/tryouts/${tryout.id}/questions/ai-import`" class="btn btn-md btn-warning border-0 shadow text-white" type="button"><i class="fa fa-robot"></i> AI Import</Link>
            <div class="table-responsive mt-3" v-if="questions.length">
              <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                <thead class="thead-dark">
                  <tr class="border-0">
                    <th class="border-0 rounded-start" style="width:5%">No.</th>
                    <th class="border-0">Soal</th>
                    <th class="border-0 rounded-end" style="width:15%">Aksi</th>
                  </tr>
                </thead>
                <div class="mt-2"></div>
                <tbody>
                  <tr v-for="(q,index) in questions" :key="q.id">
                    <td class="fw-bold text-center">{{ index+1 }}</td>
                    <td>
                      <div class="fw-bold" v-html="q.question"></div>
                      <hr>
                      <ol type="A">
                        <li v-if="q.option_1" v-html="q.option_1" :class="{ 'text-success fw-bold': q.answer == '1' }"></li>
                        <li v-if="q.option_2" v-html="q.option_2" :class="{ 'text-success fw-bold': q.answer == '2' }"></li>
                        <li v-if="q.option_3" v-html="q.option_3" :class="{ 'text-success fw-bold': q.answer == '3' }"></li>
                        <li v-if="q.option_4" v-html="q.option_4" :class="{ 'text-success fw-bold': q.answer == '4' }"></li>
                        <li v-if="q.option_5" v-html="q.option_5" :class="{ 'text-success fw-bold': q.answer == '5' }"></li>
                      </ol>
                    </td>
                    <td class="text-center">
                      <Link :href="`/admin/tryouts/${tryout.id}/questions/${q.id}/edit`" class="btn btn-sm btn-info border-0 shadow me-2" type="button"><i class="fa fa-pencil-alt"></i></Link>
                      <Link :href="`/admin/tryouts/${tryout.id}/questions/${q.id}`" method="delete" as="button" class="btn btn-sm btn-danger border-0" confirm="Hapus soal ini?"><i class="fa fa-trash"></i></Link>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div v-else class="text-center py-5 text-muted">Belum ada soal.</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '../../../../Layouts/Admin.vue';
export default { layout: AdminLayout, components:{Head,Link}, props:{ tryout:Object, questions:Array } }
</script>
<style></style>
