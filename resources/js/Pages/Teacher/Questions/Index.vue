<template>
  <StaffLayout>
    <div class="container-fluid py-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0">Bank Soal</h4>
        <div class="text-muted small">Kelola dan cari soal lintas ujian</div>
      </div>

      <div class="card mb-3">
        <div class="card-body">
          <form @submit.prevent="applyFilters">
            <div class="row g-2 align-items-end">
              <div class="col-md-3">
                <label class="form-label">Pencarian</label>
                <input v-model="search" type="text" class="form-control" placeholder="kata kunci pertanyaan" />
              </div>
              <div class="col-md-3">
                <label class="form-label">Pelajaran</label>
                <select v-model="lesson" class="form-select">
                  <option value="">Semua</option>
                  <option v-for="ls in lessons" :key="ls.id" :value="ls.id">{{ ls.title }}</option>
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label">Kelas</label>
                <select v-model="classroom" class="form-select">
                  <option value="">Semua</option>
                  <option v-for="cl in classrooms" :key="cl.id" :value="cl.id">{{ cl.title }}</option>
                </select>
              </div>
              <div class="col-md-2">
                <label class="form-label">Ujian</label>
                <select v-model="exam" class="form-select">
                  <option value="">Semua</option>
                  <option v-for="ex in exams" :key="ex.id" :value="ex.id">{{ ex.title }}</option>
                </select>
              </div>
              <div class="col-md-1">
                <button class="btn btn-primary w-100" type="submit">Terapkan</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-end mb-2">
            <a :href="exportUrl" class="btn btn-sm btn-outline-success"><i class="bi bi-download"></i> Export</a>
          </div>
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead>
                <tr>
                  <th>Pertanyaan</th>
                  <th>Mapel / Kelas</th>
                  <th>Ujian</th>
                  <th class="text-end">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="q in questions.data" :key="q.id">
                  <td class="w-50">
                    <div class="fw-semibold" v-html="q.question"></div>
                    <div class="small text-muted">Jawaban: {{ label(q.answer) }}</div>
                  </td>
                  <td>
                    <div>{{ q.exam?.lesson?.title || '-' }}</div>
                    <div class="small text-muted">{{ q.exam?.classroom?.title || '-' }}</div>
                  </td>
                  <td>{{ q.exam?.title }}</td>
                  <td class="text-end">
                    <Link :href="`${basePath}/exams/${q.exam_id}/questions/${q.id}/edit`" class="btn btn-sm btn-outline-primary">Edit</Link>
                  </td>
                </tr>
                <tr v-if="questions.data.length === 0">
                  <td colspan="4" class="text-center text-muted py-4">Tidak ada data</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="d-flex justify-content-end">
            <Pagination :links="questions.links" align="end" />
          </div>
        </div>
      </div>
    </div>
  </StaffLayout>
</template>

<script>
import StaffLayout from '../../../Layouts/Staff.vue'
import Pagination from '../../../Components/Pagination.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

export default {
  components: { StaffLayout, Pagination, Link },
  props: { questions: Object, filters: Object, lessons: Array, classrooms: Array, exams: Array },
  setup(props) {
    const search = ref(props.filters?.q || '')
    const lesson = ref(props.filters?.lesson_id || '')
    const classroom = ref(props.filters?.classroom_id || '')
  const exam = ref(props.filters?.exam_id || '')

    const basePath = `/${(window?.app?.page?.url || window.location.pathname).split('/')[1] || 'teacher'}`
    const applyFilters = () => {
      router.get(`${basePath}/questions`, {
        q: search.value || undefined,
        lesson_id: lesson.value || undefined,
        classroom_id: classroom.value || undefined,
    exam_id: exam.value || undefined,
      }, { preserveScroll: true, replace: true })
    }

    const label = (n) => ['A','B','C','D','E'][Number(n)-1] || n

    const exportUrl = () => {
      const p = new URLSearchParams();
      if (search.value) p.append('q', search.value);
      if (lesson.value) p.append('lesson_id', lesson.value);
      if (classroom.value) p.append('classroom_id', classroom.value);
      if (exam.value) p.append('exam_id', exam.value);
      const qs = p.toString();
      return `${basePath}/questions/export${qs ? `?${qs}` : ''}`
    }

    return { search, lesson, classroom, exam, applyFilters, label, exportUrl, basePath }
  }
}
</script>

<style></style>
