<template>
  <Head>
    <title>Beranda</title>
  </Head>
  <div class="container py-5">
    <div class="alert alert-info border-0 shadow">
      <strong>Info:</strong> Anda telah keluar dari sesi ujian. Jika ingin melanjutkan, silakan hubungi admin untuk membuka ujian Anda.
    </div>
    <div class="mt-3">
      <Link href="/" class="btn btn-primary">Kembali ke Halaman Login</Link>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';

export default {
  components: { Head, Link }
};
</script>

<style>
</style>
