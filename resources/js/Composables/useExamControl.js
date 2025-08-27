import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

export function useExamControl(props, basePath){
  const selectedSession = ref(props.filters?.exam_session_id || '');
  const extraMinutes = ref({});

  const applyFilter = () => {
    router.get(`${basePath}/exam-control`, { exam_session_id: selectedSession.value || undefined }, {
      preserveState: true, preserveScroll: true, replace: true
    });
  };
  watch(selectedSession, applyFilter);

  const formatMs = (ms, end_time) => {
    if (end_time) return '00:00:00';
    const v = Math.max(0, Number(ms || 0));
    const totalSec = Math.floor(v / 1000);
    const h = Math.floor(totalSec / 3600).toString().padStart(2,'0');
    const m = Math.floor((totalSec % 3600) / 60).toString().padStart(2,'0');
    const s = Math.floor(totalSec % 60).toString().padStart(2,'0');
    return `${h}:${m}:${s}`;
  };

  const postWithToast = (url, successMsg) => {
    router.post(url, {}, { preserveScroll: true, onSuccess: () => {
      Swal.fire({ title: 'Berhasil', text: successMsg, icon: 'success', timer: 1800, showConfirmButton: false });
      router.reload({ only: ['grades'] });
    }});
  };

  const confirmStop = (id) => {
    Swal.fire({ title: 'Hentikan ujian?', text: 'Siswa tidak dapat melanjutkan sebelum dibuka kembali.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, Hentikan', cancelButtonText: 'Batal' })
      .then(r => { if(r.isConfirmed) postWithToast(`${basePath}/monitor/${id}/stop`, 'Ujian dihentikan.'); });
  };
  const confirmUnlock = (id) => {
    Swal.fire({ title: 'Buka ujian?', text: 'Siswa dapat melanjutkan ujian.', icon: 'question', showCancelButton: true, confirmButtonText: 'Ya, Buka', cancelButtonText: 'Batal' })
      .then(r => { if(r.isConfirmed) postWithToast(`${basePath}/monitor/${id}/unlock`, 'Ujian dibuka.'); });
  };
  const confirmReopen = (id) => {
    Swal.fire({ title: 'Buka kembali ujian yang sudah selesai?', text: 'Ujian akan dibuka kembali untuk siswa.', icon: 'question', showCancelButton: true, confirmButtonText: 'Ya, Reopen', cancelButtonText: 'Batal' })
      .then(r => { if(r.isConfirmed) postWithToast(`${basePath}/monitor/${id}/reopen`, 'Ujian selesai dibuka kembali.'); });
  };
  const confirmAddTime = (id) => {
    const minutes = extraMinutes.value[id] || 0;
    if(!minutes || minutes < 1){
      Swal.fire({ title: 'Input tidak valid', text: 'Masukkan menit tambahan (>=1).', icon: 'error', timer: 1500, showConfirmButton: false });
      return;
    }
    Swal.fire({ title: `Tambah waktu ${minutes} menit?`, text: 'Sisa waktu siswa akan bertambah sekarang.', icon: 'question', showCancelButton: true, confirmButtonText: 'Ya, Tambah', cancelButtonText: 'Batal' })
      .then(r => { if(r.isConfirmed){ router.post(`${basePath}/monitor/${id}/add-time`, { extra_minutes: minutes }, { preserveScroll: true, onSuccess: () => { Swal.fire({ title: 'Berhasil', text: `Waktu +${minutes} menit.`, icon: 'success', timer: 1800, showConfirmButton: false }); router.reload({ only: ['grades'] }); } }); }});
  };

  return { selectedSession, extraMinutes, applyFilter, formatMs, confirmStop, confirmUnlock, confirmReopen, confirmAddTime };
}