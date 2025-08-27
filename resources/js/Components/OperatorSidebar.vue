<template>
  <nav id="sidebarMenu" class="sidebar d-lg-block collapse neo-sidebar sumopod-sidebar" data-simplebar>
    <div class="sidebar-inner px-4 pt-3">
      <ul class="nav flex-column pt-3 pt-md-0">
        <li class="sidebar-section">OPERATOR</li>
        <li class="nav-item" :class="{ 'active': $page.url.startsWith('/operator/dashboard') }">
          <Link href="/operator/dashboard" class="nav-link d-flex justify-content-between">
            <span><span class="sidebar-icon"><i class="bi bi-grid"></i></span><span class="sidebar-text">Dashboard</span></span>
          </Link>
        </li>
        <li class="nav-item" :class="{ 'active': $page.url.startsWith('/operator/exams') }">
          <Link href="/operator/exams" class="nav-link d-flex justify-content-between">
            <span><span class="sidebar-icon"><i class="bi bi-file-earmark-text"></i></span><span class="sidebar-text">Ujian</span></span>
          </Link>
        </li>
        <li class="nav-item" :class="{ 'active': $page.url.startsWith('/operator/tryouts') }">
          <Link href="/operator/tryouts" class="nav-link d-flex justify-content-between">
            <span><span class="sidebar-icon"><i class="bi bi-clipboard-check"></i></span><span class="sidebar-text">Tryout</span></span>
          </Link>
        </li>
        <li class="nav-item" :class="{ 'active': $page.url.startsWith('/operator/questions') }">
          <Link href="/operator/questions" class="nav-link d-flex justify-content-between">
            <span><span class="sidebar-icon"><i class="bi bi-collection"></i></span><span class="sidebar-text">Bank Soal</span></span>
          </Link>
        </li>
        <li class="nav-item" :class="{ 'active': $page.url.startsWith('/operator/exam-sessions') }">
          <Link href="/operator/exam-sessions" class="nav-link d-flex justify-content-between">
            <span><span class="sidebar-icon"><i class="bi bi-stopwatch"></i></span><span class="sidebar-text">Sesi Ujian</span></span>
          </Link>
        </li>
        <li class="nav-item" :class="{ 'active': $page.url.startsWith('/operator/exam-control') }">
          <Link href="/operator/exam-control" class="nav-link d-flex justify-content-between">
            <span><span class="sidebar-icon"><i class="bi bi-power"></i></span><span class="sidebar-text">Kontrol Ujian</span></span>
          </Link>
        </li>
        <li class="nav-item" :class="{ 'active': $page.url.startsWith('/operator/monitor') }">
          <Link href="/operator/monitor" class="nav-link d-flex justify-content-between">
            <span><span class="sidebar-icon"><i class="bi bi-activity"></i></span><span class="sidebar-text">Monitor</span></span>
          </Link>
        </li>
        <li class="nav-item" :class="{ 'active': $page.url.startsWith('/operator/classrooms') }">
          <Link href="/operator/classrooms" class="nav-link d-flex justify-content-between">
            <span><span class="sidebar-icon"><i class="bi bi-back"></i></span><span class="sidebar-text">Kelas</span></span>
          </Link>
        </li>
        <li class="nav-item" :class="{ 'active': $page.url.startsWith('/operator/students') }">
          <Link href="/operator/students" class="nav-link d-flex justify-content-between">
            <span><span class="sidebar-icon"><i class="bi bi-people"></i></span><span class="sidebar-text">Siswa</span></span>
          </Link>
        </li>
        <li class="nav-item" :class="{ 'active': $page.url.startsWith('/operator/reports') }">
          <Link href="/operator/reports" class="nav-link d-flex justify-content-between">
            <span><span class="sidebar-icon"><i class="bi bi-journal-check"></i></span><span class="sidebar-text">Laporan Nilai</span></span>
          </Link>
        </li>
        <li class="nav-item" :class="{ 'active': $page.url.startsWith('/operator/lessons') }">
          <Link href="/operator/lessons" class="nav-link d-flex justify-content-between">
            <span><span class="sidebar-icon"><i class="bi bi-book"></i></span><span class="sidebar-text">Mapel</span></span>
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
