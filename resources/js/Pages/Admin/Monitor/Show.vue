<template>
  <Head>
    <title>Monitor Siswa - Aplikasi Ujian Online</title>
  </Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row">
      <div class="col-md-12">
        <div v-if="$page.props.session && $page.props.session.success" class="alert alert-success alert-dismissible fade show" role="alert">
          {{$page.props.session.success}}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div v-if="$page.props.session && $page.props.session.error" class="alert alert-danger alert-dismissible fade show" role="alert">
          {{$page.props.session.error}}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <Link :href="'/admin/monitor'" class="btn btn-md btn-primary border-0 shadow mb-3" type="button">
          <i class="fa fa-long-arrow-alt-left me-2"></i> Kembali
        </Link>
        <div class="card border-0 shadow mb-3">
          <div class="card-body d-flex justify-content-between">
            <div>
              <div class="fw-bold">{{ grade.student?.name }} — {{ grade.student?.classroom?.title }}</div>
              <div>{{ grade.exam?.lesson?.title }} / {{ grade.exam?.title }}</div>
            </div>
            <div class="text-end">
              <div>
                <span v-if="!grade.start_time" class="badge bg-warning text-dark">Belum Mulai</span>
                <span v-else-if="grade.start_time && !grade.end_time" class="badge bg-info">Sedang Ujian</span>
                <span v-else class="badge bg-success">Selesai</span>
              </div>
              <div class="mt-2">
                <span class="badge bg-secondary">Soal Aktif: {{ grade.current_question ?? '-' }}</span>
                <span class="badge bg-secondary ms-2">Terakhir: {{ formatTime(grade.last_activity_at) }}</span>
                <span v-if="grade.status === 'exited'" class="badge bg-danger ms-2">Keluar dari ujian</span>
              </div>
              <div class="mt-2 d-flex gap-2">
                <button type="button" class="btn btn-sm btn-danger" @click="onStop">Stop Ujian</button>
                <button v-if="grade.status === 'exited'" type="button" class="btn btn-sm btn-warning" @click="onUnlock">Buka Ujian</button>
                <button v-if="grade.end_time" type="button" class="btn btn-sm btn-primary" @click="onReopen">Buka Ujian (Selesai)</button>
              </div>
            </div>
          </div>
        </div>

        <div class="card border-0 shadow">
          <div class="card-body">
            <div class="table-responsive" style="max-height: 60vh; overflow:auto;">
              <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                <thead class="thead-dark">
                  <tr class="border-0">
                    <th class="border-0 rounded-start" style="width:6%">No</th>
                    <th class="border-0" style="width:64%">Pertanyaan</th>
                    <th class="border-0" style="width:10%">Jawab</th>
                    <th class="border-0 rounded-end" style="width:20%">Benar?</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="a in answers" :key="a.id">
                    <td class="text-center fw-bold">{{ a.question_order }}</td>
                    <td>
                      <div v-html="a.question?.question"></div>
                    </td>
                    <td class="text-center">
                      <span v-if="a.answer === 0" class="badge bg-warning text-dark">Belum</span>
                      <span v-else class="badge bg-info">{{ answerLabel(a) }}</span>
                    </td>
                    <td class="text-center">
                      <span v-if="a.answer === 0" class="badge bg-secondary">-</span>
                      <span v-else-if="a.is_correct === 'Y'" class="badge bg-success">Benar</span>
                      <span v-else class="badge bg-danger">Salah</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import LayoutAdmin from '../../../Layouts/Admin.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { onMounted, onUnmounted } from 'vue';
import Swal from 'sweetalert2';

export default {
  layout: LayoutAdmin,
  components: { Head, Link },
  props: {
    grade: Object,
    answers: Array,
  },
  setup(props) {
    const formatTime = (val) => (val ? new Date(val).toLocaleString() : '-');

    // auto-refresh every 2s while on this page
    let timer = null;
    onMounted(() => {
      timer = setInterval(() => {
        if (document.hidden) return;
        router.reload({ only: ['grade','answers'], preserveScroll: true });
      }, 2000);
    });
    onUnmounted(() => {
      if (timer) clearInterval(timer);
    });

    const stopAutoRefresh = () => { if (timer) { clearInterval(timer); timer = null; } };

    const postAndToast = (url, text) => {
      stopAutoRefresh();
      router.post(url, {}, {
        preserveScroll: true,
        onSuccess: () => {
          Swal.fire({ title: 'Berhasil', text, icon: 'success', timer: 1800, showConfirmButton: false });
          router.reload({ only: ['grade'] });
        }
      });
    };

    const onStop = () => {
      Swal.fire({
        title: 'Hentikan ujian?',
        text: 'Siswa akan dikunci hingga dibuka lagi.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hentikan',
        cancelButtonText: 'Batal'
      }).then((r) => { if (r.isConfirmed) postAndToast(`/admin/monitor/${props.grade.id}/stop`, 'Ujian dihentikan.'); });
    };

    const onUnlock = () => {
      Swal.fire({
        title: 'Buka ujian?',
        text: 'Siswa bisa melanjutkan ujian.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Buka',
        cancelButtonText: 'Batal'
      }).then((r) => { if (r.isConfirmed) postAndToast(`/admin/monitor/${props.grade.id}/unlock`, 'Ujian dibuka.'); });
    };

    const onReopen = () => {
      Swal.fire({
        title: 'Buka kembali ujian selesai?',
        text: 'Ujian akan dibuka kembali untuk siswa.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Reopen',
        cancelButtonText: 'Batal'
      }).then((r) => { if (r.isConfirmed) postAndToast(`/admin/monitor/${props.grade.id}/reopen`, 'Ujian selesai dibuka kembali.'); });
    };

    const letterMap = ['A','B','C','D','E'];
    const answerLabel = (a) => {
      if (!a.answer || a.answer === 0) return '-';
      const idx = (a.answer - 1);
      return letterMap[idx] || a.answer;
    };

  return { formatTime, answerLabel, stopAutoRefresh, onStop, onUnlock, onReopen };
  }
};
</script>

<style>
</style>
