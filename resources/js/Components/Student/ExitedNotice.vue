<template>
  <div v-if="show" class="alert alert-warning d-flex align-items-center" role="alert">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle me-2" viewBox="0 0 16 16">
      <path d="M7.938 2.016a.13.13 0 0 1 .125 0l6.857 3.94c.11.064.18.183.18.312v7.463a.35.35 0 0 1-.35.35H1.25a.35.35 0 0 1-.35-.35V6.268c0-.129.07-.248.18-.312l6.857-3.94ZM8 5c-.535 0-.954.462-.9.995l.35 3.507a.552.552 0 0 0 1.1 0L8.9 5.995A.905.905 0 0 0 8 5Zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2Z"/>
    </svg>
    <div class="flex-grow-1">
      <div class="fw-bold">Anda sudah keluar dari ujian sebelumnya.</div>
      <div class="small">Sistem mendeteksi Anda keluar dari sesi ujian. Hubungi operator untuk melanjutkan ujian.</div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ExitedNotice',
  props: {
    show: { type: Boolean, default: false }
  }
}
</script>

<style scoped>
</style>
