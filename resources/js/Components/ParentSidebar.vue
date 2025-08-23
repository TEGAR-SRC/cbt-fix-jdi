<template>
  <nav id="sidebarMenu" class="sidebar d-lg-block collapse neo-sidebar sumopod-sidebar" data-simplebar>
    <div class="sidebar-inner px-4 pt-3">
      <ul class="nav flex-column pt-3 pt-md-0">
        <li class="sidebar-section">ORANG TUA</li>
        <li class="nav-item" :class="{ 'active': $page.url.startsWith('/parent/grades') }">
          <Link href="/parent/grades" class="nav-link d-flex justify-content-between">
            <span><span class="sidebar-icon"><i class="bi bi-bar-chart"></i></span><span class="sidebar-text">Nilai Anak</span></span>
          </Link>
        </li>
        <li class="nav-item">
          <Link href="/logout" method="post" as="button" class="nav-link d-flex justify-content-between w-100 text-start">
            <span><span class="sidebar-icon"><i class="bi bi-box-arrow-right"></i></span><span class="sidebar-text">Keluar</span></span>
          </Link>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
import { Link } from '@inertiajs/vue3';
export default { components: { Link } }
</script>

<style scoped>
.sumopod-sidebar { background:#111827; border-right:1px solid #1f2937; min-width:220px; color:#e5e7eb; }
.nav-link.active { background:#2563eb; color:#fff; }
.sidebar-icon { margin-right:12px; }
</style>
