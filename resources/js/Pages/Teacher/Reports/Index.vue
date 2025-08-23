<template>
  <StaffLayout>
    <Head><title>Laporan Nilai Ujian (Guru)</title></Head>
    <div class="container-fluid py-4">
      <div class="card border-0 shadow mb-4">
        <div class="card-body">
          <h5 class="mb-3"><i class="bi bi-funnel"></i> Filter Nilai Ujian</h5>
          <form @submit.prevent="filter">
            <div class="row g-2 align-items-end">
              <div class="col-md-9">
                <label class="form-label">Ujian</label>
                <select class="form-select" v-model="form.exam_id">
                  <option v-for="ex in exams" :key="ex.id" :value="ex.id">{{ ex.title }} — Kelas: {{ ex.classroom.title }} — Mapel: {{ ex.lesson.title }}</option>
                </select>
                <div v-if="errors.exam_id" class="alert alert-danger mt-2">{{ errors.exam_id }}</div>
              </div>
              <div class="col-md-3">
                <label class="form-label text-white">.</label>
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Filter</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <div v-if="grades.length > 0" class="card border-0 shadow">
        <div class="card-body">
          <div class="row">
            <div class="col-md-9 col-12"><h5 class="mt-2"><i class="bi bi-graph-up"></i> Laporan Nilai Ujian</h5></div>
            <div class="col-md-3 col-12">
              <a :href="`${basePath}/reports/export?exam_id=${form.exam_id}`" target="_blank" class="btn btn-success w-100 text-white"><i class="bi bi-file-earmark-excel"></i> Download Excel</a>
            </div>
          </div>
          <hr />
          <div class="table-responsive">
            <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
              <thead>
                <tr>
                  <th style="width:5%">No.</th>
                  <th>Ujian</th>
                  <th>Sesi</th>
                  <th>Nama Siswa</th>
                  <th>Kelas</th>
                  <th>Pelajaran</th>
                  <th>Nilai</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(g, i) in grades" :key="g.id">
                  <td class="text-center fw-bold">{{ i + 1 }}</td>
                  <td>{{ g.exam.title }}</td>
                  <td>{{ g.exam_session.title }}</td>
                  <td>{{ g.student.name }}</td>
                  <td class="text-center">{{ g.exam.classroom.title }}</td>
                  <td>{{ g.exam.lesson.title }}</td>
                  <td class="text-center fw-bold">{{ g.grade }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </StaffLayout>
  </template>

<script>
import StaffLayout from '../../../Layouts/Staff.vue'
import { Head, router } from '@inertiajs/vue3'
import { reactive } from 'vue'

export default {
  components: { StaffLayout, Head },
  props: { errors: Object, exams: Array, grades: Array },
  setup() {
    const form = reactive({ exam_id: '' || (new URL(document.location)).searchParams.get('exam_id') })
    const basePath = `/${(window?.app?.page?.url || window.location.pathname).split('/')[1] || 'teacher'}`
    const filter = () => { router.get(`${basePath}/reports/filter`, { exam_id: form.exam_id }) }
    return { form, filter, basePath }
  }
}
</script>

<style></style>