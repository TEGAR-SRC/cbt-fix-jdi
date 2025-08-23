<template>
    <nav id="sidebarMenu" class="sidebar d-lg-block collapse neo-sidebar sumopod-sidebar" data-simplebar>
        <div class="sidebar-inner px-4 pt-3">
            <div
                class="user-card d-flex d-md-none align-items-center justify-content-between justify-content-md-center pb-4">

                <div class="collapse-close d-md-none">
                    <a href="#sidebarMenu" data-bs-toggle="collapse" data-bs-target="#sidebarMenu"
                        aria-controls="sidebarMenu" aria-expanded="true" aria-label="Toggle navigation">
                        <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>
            </div>
                <ul class="nav flex-column pt-3 pt-md-0">
                    <li class="sidebar-section">LEARNING</li>

                <li class="nav-item">
                    <span class="mt-2 d-flex justify-content-between align-items-center">
                        <span>
                            <span class="sidebar-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#0d6efd"
                                    class="bi bi-mortarboard-fill icon icon-xs me-2" viewBox="0 0 16 16">
                                    <path
                                        d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1 1v2h3v-2a1 1 0 0 0-1-1V6.739l.686-.275a.5.5 0 0 0 .025-.917l-7.5-3.5Z" />
                                    <path
                                        d="M4.176 9.032a.5.5 0 0 0-.656.327l-.5 1.7a.5.5 0 0 0 .294.605l4.5 1.8a.5.5 0 0 0 .372 0l4.5-1.8a.5.5 0 0 0 .294-.605l-.5-1.7a.5.5 0 0 0-.656-.327L8 10.466 4.176 9.032Z" />
                                </svg>
                            </span>
                            <span class="sidebar-text fw-bold">CBT AI</span>
                        </span>
            <span class="badge badge-sm bg-primary-subtle text-primary ms-2">PRO</span>
                    </span>
                </li>

        <li role="separator" class="dropdown-divider mt-4 mb-3"></li>
        <li class="sidebar-section">SERVICES</li>

                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/dashboard') }">
                    <Link href="/admin/dashboard" class="nav-link d-flex justify-content-between">
                    <span>
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                class="bi bi-speedometer2 icon icon-xs me-2" viewBox="0 0 16 16">
                                <path
                                    d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4zM3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 9.31a.91.91 0 1 0 1.302 1.258l3.434-4.297a.389.389 0 0 0-.029-.518z" />
                                <path fill-rule="evenodd"
                                    d="M0 10a8 8 0 1 1 15.547 2.661c-.442 1.253-1.845 1.602-2.932 1.25C11.309 13.488 9.475 13 8 13c-1.474 0-3.31.488-4.615.911-1.087.352-2.49.003-2.932-1.25A7.988 7.988 0 0 1 0 10zm8-7a7 7 0 0 0-6.603 9.329c.203.575.923.876 1.68.63C4.397 12.533 6.358 12 8 12s3.604.532 4.923.96c.757.245 1.477-.056 1.68-.631A7 7 0 0 0 8 3z" />
                            </svg>
                        </span>
                        <span class="sidebar-text">Dashboard</span>
                    </span>
                    </Link>
                </li>

                <li role="separator" class="dropdown-divider mt-2 mb-2"></li>

                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/lessons') }">
                    <Link href="/admin/lessons" class="nav-link d-flex justify-content-between">
                    <span>
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                class="bi bi-bookmarks icon icon-xs me-2" viewBox="0 0 16 16">
                                <path
                                    d="M2 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L7 13.101l-4.223 2.815A.5.5 0 0 1 2 15.5V4zm2-1a1 1 0 0 0-1 1v10.566l3.723-2.482a.5.5 0 0 1 .554 0L11 14.566V4a1 1 0 0 0-1-1H4z" />
                                <path
                                    d="M4.268 1H12a1 1 0 0 1 1 1v11.768l.223.148A.5.5 0 0 0 14 13.5V2a2 2 0 0 0-2-2H6a2 2 0 0 0-1.732 1z" />
                            </svg>
                        </span>
                        <span class="sidebar-text">Mata Pelajaran</span>
                    </span>
                    </Link>
                </li>

                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/classrooms') }">
                    <Link href="/admin/classrooms" class="nav-link d-flex justify-content-between">
                    <span>
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                class="bi bi-back icon icon-xs me-2" viewBox="0 0 16 16">
                                <path
                                    d="M0 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2h2a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2H2a2 2 0 0 1-2-2V2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H2z" />
                            </svg>
                        </span>
                        <span class="sidebar-text">Kelas</span>
                    </span>
                    </Link>
                </li>

                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/students') }">
                    <Link href="/admin/students" class="nav-link d-flex justify-content-between">
                    <span>
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                class="bi bi-people-fill icon icon-xs me-2" viewBox="0 0 16 16">
                                <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                <path fill-rule="evenodd"
                                    d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z" />
                                <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z" />
                            </svg>
                        </span>
                        <span class="sidebar-text">Siswa</span>
                    </span>
                    </Link>
                </li>

                <li role="separator" class="dropdown-divider mt-2 mb-2"></li>

                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/admins') }">
                    <Link href="/admin/admins" class="nav-link d-flex justify-content-between">
                    <span>
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                class="bi bi-shield-lock icon icon-xs me-2" viewBox="0 0 16 16">
                                <path d="M5.5 9a1.5 1.5 0 1 1 3 0v1.035a.5.5 0 0 1-.146.354l-1 1a.5.5 0 0 1-.708 0l-1-1A.5.5 0 0 1 5.5 10.035V9z"/>
                                <path d="M8 0c-.69 0-1.542.218-2.516.56C3.485 1.246 1.982 1.8.727 2.02A.5.5 0 0 0 0 2.5v4.764c0 3.587 2.507 6.614 6.684 8.49a.5.5 0 0 0 .632 0C11.993 13.878 15 10.85 15 7.264V2.5a.5.5 0 0 0-.727-.48c-1.255.22-2.758.774-4.757 1.46C9.542.218 8.69 0 8 0z"/>
                            </svg>
                        </span>
                        <span class="sidebar-text">Pengguna Admin</span>
                    </span>
                    </Link>
                </li>

                <li role="separator" class="dropdown-divider mt-2 mb-2 border-primary"></li>
                <li class="sidebar-section">INFRASTRUCTURE</li>

                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/monitor') }">
                    <Link href="/admin/monitor" class="nav-link d-flex justify-content-between">
                    <span>
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-eye icon icon-xs me-2" viewBox="0 0 16 16">
                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/>
                                <path d="M8 5.5a2.5 2.5 0 1 1 0 5a2.5 2.5 0 0 1 0-5z"/>
                            </svg>
                        </span>
                        <span class="sidebar-text">Proctoring</span>
                    </span>
                    </Link>
                </li>

                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/exam-control') }">
                    <Link href="/admin/exam-control" class="nav-link d-flex justify-content-between">
                    <span>
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-power icon icon-xs me-2" viewBox="0 0 16 16">
                                <path d="M7.5 1v7h1V1h-1z"/>
                                <path d="M3.05 3.05a7 7 0 1 0 9.9 9.9l-.707-.707a6 6 0 1 1-8.486-8.486l.707-.707z"/>
                            </svg>
                        </span>
                        <span class="sidebar-text">Kontrol Ujian</span>
                    </span>
                    </Link>
                </li>

                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/settings') }">
                    <Link href="/admin/settings" class="nav-link d-flex justify-content-between">
                    <span>
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-gear icon icon-xs me-2" viewBox="0 0 16 16">
                                <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                                <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.433 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.433-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.159c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 2.96 4.469l-.159-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
                            </svg>
                        </span>
                        <span class="sidebar-text">Pengaturan</span>
                    </span>
                    </Link>
                </li>
                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/exams') }">
                    <Link href="/admin/exams" class="nav-link d-flex justify-content-between">
                    <span>
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                class="bi bi-pencil-square icon icon-xs me-2" viewBox="0 0 16 16">
                                <path
                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd"
                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                            </svg>
                        </span>
                        <span class="sidebar-text">Bank Ujian</span>
                    </span>
                    </Link>
                </li>

                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/exam_sessions') }">
                    <Link href="/admin/exam_sessions" class="nav-link d-flex justify-content-between">
                    <span>
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                class="bi bi-stopwatch icon icon-xs me-2" viewBox="0 0 16 16">
                                <path d="M8.5 5.6a.5.5 0 1 0-1 0v2.9h-3a.5.5 0 0 0 0 1H8a.5.5 0 0 0 .5-.5V5.6z" />
                                <path
                                    d="M6.5 1A.5.5 0 0 1 7 .5h2a.5.5 0 0 1 0 1v.57c1.36.196 2.594.78 3.584 1.64a.715.715 0 0 1 .012-.013l.354-.354-.354-.353a.5.5 0 0 1 .707-.708l1.414 1.415a.5.5 0 1 1-.707.707l-.353-.354-.354.354a.512.512 0 0 1-.013.012A7 7 0 1 1 7 2.071V1.5a.5.5 0 0 1-.5-.5zM8 3a6 6 0 1 0 .001 12A6 6 0 0 0 8 3z" />
                            </svg>
                        </span>
                        <span class="sidebar-text">Sesi Ujian</span>
                    </span>
                    </Link>
                </li>

                <li role="separator" class="dropdown-divider mt-2 mb-2"></li>

                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/reports') }">
                    <Link href="/admin/reports" class="nav-link d-flex justify-content-between">
                    <span>
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                class="bi bi-graph-up-arrow icon icon-xs me-2" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M0 0h1v15h15v1H0V0Zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.9l-3.613 4.417a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61L13.445 4H10.5a.5.5 0 0 1-.5-.5Z" />
                            </svg>
                        </span>
                        <span class="sidebar-text">Laporan & Analytics</span>
                    </span>
                    </Link>
                </li>

            </ul>
        </div>
    </nav>
</template>

<script>

//import Link
import { Link } from '@inertiajs/vue3';

export default {

    //register components
    components: {
        Link
    },

}

</script>

<style>
/* Nickelfox dashboard sidebar clone */
/* Admin sidebar: milky white gradient, clean style */
/* Modern light sidebar */
.sumopod-sidebar {
    background: rgb(248,250,252) !important; /* milky white background */
    border-right: 1px solid #e5e7eb; /* abu terang */
    min-width: 220px;
    box-shadow: 2px 0 16px 0 rgba(32,64,122,.08);
    font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
    color: #232a3b; /* teks utama */
}

.sumopod-sidebar .sidebar-section {
    font-weight: 700;
    color: #6b7280; /* abu teks label */
    font-size: 13px;
    margin: 22px 0 8px 18px;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.sumopod-sidebar .nav-item {
    margin-bottom: 6px;
}

.sumopod-sidebar .nav-link {
    border-radius: 10px;
    padding: 12px 20px;
    color: #374151; /* abu teks nav */
    font-weight: 500;
    font-size: 15px;
    display: flex;
    align-items: center;
    background: transparent;
    box-shadow: none;
    transition: all 0.2s ease-in-out;
}

.sumopod-sidebar .nav-link:hover {
    background: #e7f1ff; /* biru muda */
    color: #0d6efd; /* biru utama */
    box-shadow: 0 2px 12px 0 rgba(13,110,253,.08);
}

.sumopod-sidebar .nav-link.active {
    background: #2563eb; /* biru solid */
    color: #fff;
    font-weight: 600;
    box-shadow: inset 0 0 10px rgba(37,99,235,.3);
}

.sumopod-sidebar .sidebar-icon {
    background: #e7f1ff;
    border-radius: 8px;
    padding: 4px;
    margin-right: 12px;
    color: #0d6efd;
    width: 28px;
    height: 28px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px 0 rgba(13,110,253,.08);
    transition: color .18s, background .18s;
}

.sumopod-sidebar .sidebar-icon svg {
    color: inherit;
    opacity: 0.9;
    transition: color 0.2s ease;
}

.sumopod-sidebar .nav-link:hover .sidebar-icon {
    background: #dbeafe;
    color: #2563eb;
}

.sumopod-sidebar .dropdown-divider {
    border-color: #e5e7eb; /* abu divider terang */
}

/* Remove conflicting neo-sidebar background */
.neo-sidebar .nav-link { 
    color:#6b7280 !important; 
    font-weight:600; 
    border-radius: .55rem; 
    padding:.6rem .8rem; 
    transition: background-color .15s ease, color .15s ease; 
    opacity: 1 !important; 
}
.neo-sidebar .nav-link:hover { background: #e7f1ff; color:#0d6efd; }
.neo-sidebar .nav-link:focus,
.neo-sidebar .nav-link:active { background:#0d6efd !important; color:#fff !important; }
/* Active route: solid blue */
.neo-sidebar .nav-item.active > .nav-link { 
    background:#0d6efd !important; 
    color:#fff !important; 
    box-shadow: none; 
}
.neo-sidebar .nav-item.active > .nav-link:hover { 
    background:#0d6efd !important; 
    color:#fff !important; 
}
.neo-sidebar .dropdown-divider { border-color: #e5e7eb !important; }
.neo-sidebar .sidebar-icon svg {
    color: #0d6efd !important;
    fill: #0d6efd !important;
    stroke: #0d6efd !important;
    opacity: 1 !important;
    transition: filter .18s;
}
.neo-sidebar .nav-item.active .sidebar-icon svg {
    filter: none;
}
.neo-sidebar *, 
.neo-sidebar .nav-link *, 
.neo-sidebar .sidebar-text { opacity: 1 !important; }
.neo-sidebar .dropdown-divider { border-color:#e5e7eb !important; }
.bg-primary-subtle { background: rgba(13,110,253,.1) !important; }
.text-primary { color:#2563eb !important; }
</style>