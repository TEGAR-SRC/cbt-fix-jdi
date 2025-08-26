<template>
  <div class="d-flex">
    <!-- If opened via /admin/dinas we rely on Admin layout's sidebar, so hide local -->
    <Sidebar v-if="!$page.url.startsWith('/admin/dinas')" />
    <main class="flex-fill content bg-body-neo">
      <slot />
    </main>
  </div>
</template>
<script>
import Sidebar from '../Components/Sidebar.vue';
export default { components:{ Sidebar } }
</script>
<style>
body, .content { background:#f8fafc !important; }
.content { min-height:100vh; }
</style>
