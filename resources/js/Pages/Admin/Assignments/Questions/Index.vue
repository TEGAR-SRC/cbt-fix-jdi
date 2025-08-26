<template>
  <Head><title>Soal Tugas: {{ assignment.title }}</title></Head>
  <div class="container-fluid mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Soal Tugas: {{ assignment.title }}</h4>
      <div>
        <Link :href="`/admin/assignments`" class="btn btn-light me-2">Kembali</Link>
        <Link :href="`/admin/assignments/${assignment.id}/questions/create`" class="btn btn-primary">Tambah Soal</Link>
      </div>
    </div>
    <div class="card border-0 shadow">
      <div class="card-body">
        <div v-if="!questions.length" class="text-center py-5 text-muted">Belum ada soal.</div>
        <div v-else class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Pertanyaan</th>
                <th>Jawaban</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(q,i) in questions" :key="q.id">
                <td>{{ i+1 }}</td>
                <td style="max-width:400px">{{ q.question.slice(0,120) }}</td>
                <td>{{ q.answer || '-' }}</td>
                <td>
                  <div class="btn-group btn-group-sm">
                    <Link :href="`/admin/assignments/${assignment.id}/questions/${q.id}/edit`" class="btn btn-warning">Edit</Link>
                    <Link :href="`/admin/assignments/${assignment.id}/questions/${q.id}`" method="delete" as="button" class="btn btn-danger" confirm="Hapus soal ini?">Hapus</Link>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '../../../../Layouts/Admin.vue';
export default { layout: AdminLayout, components:{Head,Link}, props:{ assignment:Object, questions:Array } }
</script>
<style></style>
