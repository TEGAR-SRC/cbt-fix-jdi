<template>
    <nav id="sidebarMenu" class="sidebar d-lg-block bg-gray-800 text-white collapse" data-simplebar>
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

                <li class="nav-item">
                    <span class="mt-2 d-flex justify-content-between">
                        <span>
                            <span class="sidebar-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                    class="bi bi-mortarboard-fill icon icon-xs me-2" viewBox="0 0 16 16">
                                    <path
                                        d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1 1v2h3v-2a1 1 0 0 0-1-1V6.739l.686-.275a.5.5 0 0 0 .025-.917l-7.5-3.5Z" />
                                    <path
                                        d="M4.176 9.032a.5.5 0 0 0-.656.327l-.5 1.7a.5.5 0 0 0 .294.605l4.5 1.8a.5.5 0 0 0 .372 0l4.5-1.8a.5.5 0 0 0 .294-.605l-.5-1.7a.5.5 0 0 0-.656-.327L8 10.466 4.176 9.032Z" />
                                </svg>
                            </span>
                            <span class="sidebar-text fw-bold">UJIAN ONLINE</span>
                        </span>
                        <span>
                            <span class="badge badge-sm bg-secondary ms-1 text-gray-800">PRO</span>
                        </span>
                    </span>
                </li>

                <li role="separator" class="dropdown-divider mt-4 mb-3 border-gray-700"></li>

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

                <li role="separator" class="dropdown-divider mt-2 mb-2 border-gray-700"></li>

                <!-- Section: Manajemen -->
                <li class="nav-item mt-2 mb-1"><span class="sidebar-text text-uppercase text-muted small">Manajemen</span></li>

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

                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/assignments') }">
                    <Link href="/admin/assignments" class="nav-link d-flex justify-content-between">
                    <span>
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-journal-text icon icon-xs me-2" viewBox="0 0 16 16">
                                <path d="M5 8a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5A.5.5 0 0 1 5 8Zm0 2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5A.5.5 0 0 1 5 10Zm0-4a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5A.5.5 0 0 1 5 6Z"/>
                                <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2Z"/>
                                <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1H2v.5a.5.5 0 0 1-1 0V6H.5a.5.5 0 0 1 0-1H1Z"/>
                            </svg>
                        </span>
                        <span class="sidebar-text">Tugas Harian</span>
                    </span>
                    </Link>
                </li>

                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/guardians') }">
                    <Link href="/admin/guardians" class="nav-link d-flex justify-content-between">
                    <span>
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-people icon icon-xs me-2" viewBox="0 0 16 16">
                              <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.718 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.012.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm-7 7s-1 0-1-1 1-4 5-4a5.5 5.5 0 0 1 2.5.598 5.7 5.7 0 0 0-1.22.713C7.31 10.629 6.34 11.259 4.622 11.259 2.904 11.259 1.935 10.629 1.382 9.982.79 9.292.625 8.525.623 8.262l.008-.002.012-.002H4.978c-.001.264-.167 1.03-.76 1.72C3.688 11.371 2.718 12 1 12c-1.718 0-2.687-.63-3.24-1.276C-.834 9.034-.999 8.267-1 8.004l.008-.002.012-.002H-1.022Z"/>
                            </svg>
                        </span>
                        <span class="sidebar-text">Orang Tua</span>
                    </span>
                    </Link>
                </li>

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
                        <span class="sidebar-text">Pengguna</span>
                    </span>
                    </Link>
                </li>

                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/admins') }">
                    <Link href="/admin/admins/create" class="nav-link d-flex justify-content-between">
                    <span>
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-person-plus icon icon-xs me-2" viewBox="0 0 16 16">
                                <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                                <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
                            </svg>
                        </span>
                        <span class="sidebar-text">Tambah Admin</span>
                    </span>
                    </Link>
                </li>

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

                <li role="separator" class="dropdown-divider mt-3 mb-2 border-gray-700"></li>

                <!-- Section: Ujian -->
                <li class="nav-item mt-2 mb-1"><span class="sidebar-text text-uppercase text-muted small">Ujian</span></li>

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
                        <span class="sidebar-text">Ujian</span>
                    </span>
                    </Link>
                </li>

                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/tryouts') }">
                    <Link href="/admin/tryouts" class="nav-link d-flex justify-content-between">
                    <span>
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-clipboard-check icon icon-xs me-2" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M10.854 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0L5.5 8.207a.5.5 0 1 1 .708-.708L7.5 8.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                <path d="M10 1.5v1h1a1 1 0 0 1 1 1V14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V3.5a1 1 0 0 1 1-1h1v-1A1.5 1.5 0 0 1 7.5 0h1A1.5 1.5 0 0 1 10 1.5zM6 2v1h4V2a.5.5 0 0 0-.5-.5h-3A.5.5 0 0 0 6 2z"/>
                            </svg>
                        </span>
                        <span class="sidebar-text">Tryout</span>
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

                <li role="separator" class="dropdown-divider mt-3 mb-2 border-gray-700"></li>

                <!-- Section: Monitoring & Kontrol -->
                <li class="nav-item mt-2 mb-1"><span class="sidebar-text text-uppercase text-muted small">Monitoring &amp; Kontrol</span></li>

                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/monitor') }">
                    <Link href="/admin/monitor" class="nav-link d-flex justify-content-between">
                    <span>
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-eye icon icon-xs me-2" viewBox="0 0 16 16">
                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/>
                                <path d="M8 5.5a2.5 2.5 0 1 1 0 5a2.5 2.5 0 0 1 0-5z"/>
                            </svg>
                        </span>
                        <span class="sidebar-text">Monitoring Ujian</span>
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

                <li role="separator" class="dropdown-divider mt-3 mb-2 border-gray-700"></li>

                <!-- Section: Proctoring & Keamanan -->
                <li class="nav-item mt-2 mb-1"><span class="sidebar-text text-uppercase text-muted small">Proctoring &amp; Keamanan</span></li>

                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/proctoring/sessions') }">
                    <Link href="/admin/proctoring/sessions" class="nav-link d-flex justify-content-between">
                    <span>
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-camera-video icon icon-xs me-2" viewBox="0 0 16 16">
                                <path d="M0 4a2 2 0 0 1 2-2h7.5a2 2 0 0 1 1.983 1.738l3.11-1.382A1 1 0 0 1 16 5.269v7.462a1 1 0 0 1-1.406.913l-3.111-1.382A2 2 0 0 1 9.5 13H2a2 2 0 0 1-2-2V4zm4.5 1a.5.5 0 1 0 0 1 .5.5 0 0 0 0-1zm0 2a.5.5 0 1 0 0 1 .5.5 0 0 0 0-1z"/>
                            </svg>
                        </span>
                        <span class="sidebar-text">Sesi Proctoring</span>
                    </span>
                    </Link>
                </li>

                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/proctoring/violations') }">
                    <Link href="/admin/proctoring/violations" class="nav-link d-flex justify-content-between">
                    <span>
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-exclamation-triangle icon icon-xs me-2" viewBox="0 0 16 16">
                                <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/>
                                <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/>
                            </svg>
                        </span>
                        <span class="sidebar-text">Pelanggaran</span>
                    </span>
                    </Link>
                </li>

                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/proctoring/photos') }">
                    <Link href="/admin/proctoring/photos" class="nav-link d-flex justify-content-between">
                    <span>
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-camera icon icon-xs me-2" viewBox="0 0 16 16">
                                <path d="M15 12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h1.172a3 3 0 0 0 2.12-.879l.83-.828A1 1 0 0 1 6.827 3h2.344a1 1 0 0 1 .707.293l.828.828A3 3 0 0 0 12.828 5H14a1 1 0 0 1 1 1v6zM2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4H2z"/>
                                <path d="M8 11a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5zm0 1a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7zM3 6.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
                            </svg>
                        </span>
                        <span class="sidebar-text">Foto Proctoring</span>
                    </span>
                    </Link>
                </li>

                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/proctoring/activities') }">
                    <Link href="/admin/proctoring/activities" class="nav-link d-flex justify-content-between">
                    <span>
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-activity icon icon-xs me-2" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M6 2a.5.5 0 0 1 .47.33L8.667 8.5l1.2-3a.5.5 0 0 1 .933.002L12.5 9H15a.5.5 0 0 1 0 1h-2.8a.5.5 0 0 1-.467-.324L10.8 7.5l-1.2 3a.5.5 0 0 1-.934 0L6.53 3.67 5.2 7H1a.5.5 0 0 1 0-1h3.6a.5.5 0 0 1 .467.324L6 2z"/>
                            </svg>
                        </span>
                        <span class="sidebar-text">Log Aktivitas</span>
                    </span>
                    </Link>
                </li>

                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/proctoring/network') }">
                    <Link href="/admin/proctoring/network" class="nav-link d-flex justify-content-between">
                    <span>
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-wifi icon icon-xs me-2" viewBox="0 0 16 16">
                                <path d="M15.384 6.115a.485.485 0 0 0-.047-.736A12.444 12.444 0 0 0 8 3C5.259 3 2.723 3.882.663 5.379a.485.485 0 0 0-.047.736.525.525 0 0 0 .668.05A11.448 11.448 0 0 1 8 4c2.507 0 4.827.892 6.716 2.164a.525.525 0 0 0 .668-.05z"/>
                                <path d="M13.229 8.271a.482.482 0 0 0-.063-.745A9.455 9.455 0 0 0 8 6c-1.905 0-3.68.56-5.166 1.526a.48.48 0 0 0-.063.745.525.525 0 0 0 .652.065A8.46 8.46 0 0 1 8 7a8.46 8.46 0 0 1 4.576 1.336c.206.132.48.108.653-.065zm-2.183 2.183c.226-.226.185-.605-.1-.75A6.473 6.473 0 0 0 8 9c-1.06 0-2.062.254-2.946.704-.285.145-.326.524-.1.75l.015.015c.16.16.407.19.611.09A5.478 5.478 0 0 1 8 10c.868 0 1.69.201 2.42.56.203.1.45.07.61-.091l.016-.015zM9.06 12.44c.196-.196.198-.52-.04-.66A1.99 1.99 0 0 0 8 11.5a1.99 1.99 0 0 0-1.02.28c-.238.14-.236.464-.04.66l.706.706a.5.5 0 0 0 .708 0l.707-.707z"/>
                            </svg>
                        </span>
                        <span class="sidebar-text">Status Jaringan</span>
                    </span>
                    </Link>
                </li>

                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/proctoring/settings') }">
                    <Link href="/admin/proctoring/settings" class="nav-link d-flex justify-content-between">
                    <span>
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-gear icon icon-xs me-2" viewBox="0 0 16 16">
                                <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                                <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292-.16c-.764-.415-1.6.42-1.184 1.185l.159.292A1.873 1.873 0 0 0 1.945 8.93l-.319.094c-.835.246-.835 1.428 0 1.674l.319.094a1.873 1.873 0 0 0 1.115 2.692l-.16.291c-.415.764.42 1.6 1.185 1.184l.292-.159a1.873 1.873 0 0 0 2.692 1.115l.094.319z"/>
                            </svg>
                        </span>
                        <span class="sidebar-text">Pengaturan</span>
                    </span>
                    </Link>
                </li>

                <li role="separator" class="dropdown-divider mt-3 mb-2 border-gray-700"></li>

                <!-- Section: Laporan -->
                <li class="nav-item mt-2 mb-1"><span class="sidebar-text text-uppercase text-muted small">Laporan</span></li>

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
                        <span class="sidebar-text">Laporan Nilai</span>
                    </span>
                    </Link>
                </li>

                <li role="separator" class="dropdown-divider mt-3 mb-2 border-gray-700"></li>

                <!-- Section: Dinas Pendidikan -->
                <li class="nav-item mt-2 mb-1"><span class="sidebar-text text-uppercase text-muted small">Dinas Pendidikan</span></li>

                <li class="nav-item" :class="{ 'active': $page.url === '/admin/dinas' || $page.url.startsWith('/admin/dinas?') }">
                    <Link href="/admin/dinas" class="nav-link d-flex justify-content-between">
                    <span>
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-bank icon icon-xs me-2" viewBox="0 0 16 16">
                                <path d="M8 0 0 4l8 4 8-4-8-4Zm6 10v2h1v1H1v-1h1v-2H1V9h14v1h-1Zm-2 0H4v2h8v-2Z"/>
                            </svg>
                        </span>
                        <span class="sidebar-text">Dashboard</span>
                    </span>
                    </Link>
                </li>

                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/dinas/monitor') }">
                    <Link href="/admin/dinas/monitor" class="nav-link d-flex justify-content-between">
                    <span>
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-activity icon icon-xs me-2" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M6 2a.5.5 0 0 1 .47.33L8.667 8.5l1.2-3a.5.5 0 0 1 .933.002L12.5 9H15a.5.5 0 0 1 0 1h-2.8a.5.5 0 0 1-.467-.324L10.8 7.5l-1.2 3a.5.5 0 0 1-.934 0L6.53 3.67 5.2 7H1a.5.5 0 0 1 0-1h3.6a.5.5 0 0 1 .467.324L6 2z"/>
                            </svg>
                        </span>
                        <span class="sidebar-text">Monitoring</span>
                    </span>
                    </Link>
                </li>

                <li role="separator" class="dropdown-divider mt-3 mb-2 border-gray-700"></li>

                <!-- Section: Pengaturan -->
                <li class="nav-item mt-2 mb-1"><span class="sidebar-text text-uppercase text-muted small">Pengaturan</span></li>

                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/settings') }">
                    <Link href="/admin/settings" class="nav-link d-flex justify-content-between">
                    <span>
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-gear icon icon-xs me-2" viewBox="0 0 16 16">
                                <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                                <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 2.02 8.873l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.135 4.32l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.291.159a1.873 1.873 0 0 0 2.693-1.115l.094-.319z"/>
                            </svg>
                        </span>
                        <span class="sidebar-text">Pengaturan</span>
                    </span>
                    </Link>
                </li>

                <li role="separator" class="dropdown-divider mt-2 mb-2 border-gray-700"></li>

                <li class="nav-item">
                    <Link href="/logout" method="post" as="button" class="nav-link nav-link-logout d-flex justify-content-between w-100 text-start">
                    <span>
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-box-arrow-right icon icon-xs me-2" viewBox="0 0 16 16">
                                <path d="M10 15a1 1 0 0 0 1-1v-3h-1v3H2V2h8v3h1V2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8z"/>
                                <path d="M15.354 8.354a.5.5 0 0 0 0-.708L12.172 4.464a.5.5 0 1 0-.708.708L13.293 7.5H6.5a.5.5 0 0 0 0 1h6.793l-1.829 2.328a.5.5 0 1 0 .708.708l3.182-3.182z"/>
                            </svg>
                        </span>
                        <span class="sidebar-text">Keluar</span>
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
/* Styling untuk tombol keluar */
.nav-link-logout {
    background-color: #dc3545 !important; /* Warna merah untuk tombol keluar */
    color: #ffffff !important;
    border-radius: 8px;
    margin-top: 8px;
    transition: all 0.3s ease;
}

.nav-link-logout:hover {
    background-color: #c82333 !important; /* Warna merah lebih gelap saat hover */
    color: #ffffff !important;
    transform: translateX(2px);
}

.nav-link-logout:focus,
.nav-link-logout:active {
    background-color: #bd2130 !important;
    color: #ffffff !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.nav-link-logout .sidebar-icon {
    color: #ffffff !important;
}

.nav-link-logout .sidebar-text {
    color: #ffffff !important;
    font-weight: 500;
}
</style>