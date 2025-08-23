<template>
  <div class="d-flex">
    <!-- Hide Dinas sidebar when opened via Admin proxy so Admin sidebar stays visible -->
    <aside v-if="!$page.url.startsWith('/admin/dinas')" class="p-3 border-end" style="width:260px;min-height:100vh;background:#fafafa">
      <h6 class="fw-bold">Dinas Pendidikan</h6>
      <ul class="list-unstyled mt-3">
        <li class="mb-2">
          <Link :href="$page.url.startsWith('/admin/dinas') ? '/admin/dinas' : '/dinas/dashboard'" class="text-decoration-none">Dashboard</Link>
        </li>
        <li class="mb-2">
          <Link :href="$page.url.startsWith('/admin/dinas') ? '/admin/dinas/monitor' : '/dinas/monitor'" class="text-decoration-none">Monitoring Ujian</Link>
        </li>
      </ul>
    </aside>
    <main class="flex-fill">
      <slot />
    </main>
  </div>
</template>
<script>
import { Link } from '@inertiajs/vue3';
export default { components: { Link } }
</script>
<style></style>
