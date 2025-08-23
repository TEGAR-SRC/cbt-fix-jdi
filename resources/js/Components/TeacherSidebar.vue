<template>
  <nav id="sidebarMenu" class="sidebar d-lg-block collapse neo-sidebar sumopod-sidebar" data-simplebar>
    <div class="sidebar-inner px-4 pt-3">
      <ul class="nav flex-column pt-3 pt-md-0">
        <li class="sidebar-section">TEACHER</li>
        <li class="nav-item" :class="{ 'active': $page.url.startsWith('/teacher/dashboard') }">
          <Link href="/teacher/dashboard" class="nav-link d-flex justify-content-between">
            <span>
              <span class="sidebar-icon">
                <i class="bi bi-grid"></i>
              </span>
              <span class="sidebar-text">Dashboard</span>
            </span>
          </Link>
        </li>

        <li class="nav-item" :class="{ 'active': $page.url.startsWith('/teacher/reports') }">
          <Link href="/teacher/reports" class="nav-link d-flex justify-content-between">
            <span>
              <span class="sidebar-icon"><i class="bi bi-journal-check"></i></span>
              <span class="sidebar-text">Laporan Nilai</span>
            </span>
          </Link>
        </li>
        <li class="nav-item" :class="{ 'active': $page.url.startsWith('/teacher/exams') }">
          <Link href="/teacher/exams" class="nav-link d-flex justify-content-between">
            <span>
              <span class="sidebar-icon"><i class="bi bi-file-earmark-text"></i></span>
              <span class="sidebar-text">Ujian Saya</span>
            </span>
          </Link>
        </li>
        <li class="nav-item" :class="{ 'active': $page.url.startsWith('/teacher/questions') }">
          <Link href="/teacher/questions" class="nav-link d-flex justify-content-between">
            <span>
              <span class="sidebar-icon"><i class="bi bi-collection"></i></span>
              <span class="sidebar-text">Bank Soal</span>
            </span>
          </Link>
        </li>

        <li class="nav-item" :class="{ 'active': $page.url.startsWith('/teacher/exam-sessions') }">
          <Link href="/teacher/exam-sessions" class="nav-link d-flex justify-content-between">
            <span>
              <span class="sidebar-icon"><i class="bi bi-stopwatch"></i></span>
              <span class="sidebar-text">Sesi Ujian</span>
            </span>
          </Link>
        </li>

        <li class="nav-item" :class="{ 'active': $page.url.startsWith('/teacher/exam-control') }">
          <Link href="/teacher/exam-control" class="nav-link d-flex justify-content-between">
            <span>
              <span class="sidebar-icon"><i class="bi bi-power"></i></span>
              <span class="sidebar-text">Kontrol Ujian</span>
            </span>
          </Link>
        </li>

        <li class="nav-item" :class="{ 'active': $page.url.startsWith('/teacher/monitor') }">
          <Link href="/teacher/monitor" class="nav-link d-flex justify-content-between">
            <span>
              <span class="sidebar-icon"><i class="bi bi-activity"></i></span>
              <span class="sidebar-text">Monitor</span>
            </span>
          </Link>
        </li>

        <li class="nav-item" :class="{ 'active': $page.url.startsWith('/teacher/classrooms') }">
          <Link href="/teacher/classrooms" class="nav-link d-flex justify-content-between">
            <span>
              <span class="sidebar-icon"><i class="bi bi-back"></i></span>
              <span class="sidebar-text">Kelas</span>
            </span>
          </Link>
        </li>

        <li class="nav-item" :class="{ 'active': $page.url.startsWith('/teacher/students') }">
          <Link href="/teacher/students" class="nav-link d-flex justify-content-between">
            <span>
              <span class="sidebar-icon"><i class="bi bi-people"></i></span>
              <span class="sidebar-text">Siswa</span>
            </span>
          </Link>
        </li>

        <li class="nav-item">
          <Link href="/logout" method="post" as="button" class="nav-link d-flex justify-content-between w-100 text-start">
            <span>
              <span class="sidebar-icon"><i class="bi bi-box-arrow-right"></i></span>
              <span class="sidebar-text">Keluar</span>
            </span>
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
.sumopod-sidebar { 
    background: #111827 !important; /* dark slate */
    border-right: 1px solid #1f2937; 
    min-width: 220px; 
    font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
    color: #e5e7eb; /* text-gray-200 */
}
.sumopod-sidebar .sidebar-section { 
    font-weight: 600; 
    color: #9ca3af; /* text-gray-400 */
    font-size: 12px; 
    margin: 20px 0 10px 18px; 
    letter-spacing: 1px; 
    text-transform: uppercase; 
}
.sumopod-sidebar .nav-item { 
    margin-bottom: 6px; 
}
.sumopod-sidebar .nav-link { 
    border-radius: 10px; 
    padding: 12px 20px; 
    color: #d1d5db; /* text-gray-300 */
    font-weight: 500; 
    background: transparent; 
    transition: all .2s ease-in-out; 
    font-size: 15px; 
    display: flex;
    align-items: center;
}
.sumopod-sidebar .nav-link:hover { 
    background: #1f2937; /* hover abu gelap */
    color: #3b82f6; /* biru neon */
}
.sumopod-sidebar .nav-link.active { 
    background: #2563eb; /* biru solid */
    color: #fff; 
    font-weight: 600; 
    box-shadow: inset 0 0 10px rgba(59,130,246,.4);
}
.sumopod-sidebar .sidebar-icon { 
    border-radius: 8px; 
    padding: 4px; 
    margin-right: 12px; 
    width: 28px; 
    height: 28px; 
    display: inline-flex; 
    align-items: center; 
    justify-content: center; 
    transition: color .2s ease, background .2s ease; 
    color: inherit;
}
.sumopod-sidebar .nav-link:hover .sidebar-icon { 
    color: #3b82f6;
}
.sumopod-sidebar .sidebar-text { 
    font-size: 15px; 
    font-weight: 500; 
    letter-spacing: .2px; 
}
</style>

