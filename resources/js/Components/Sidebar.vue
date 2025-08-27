<template>
    <nav id="sidebarMenu" class="sidebar d-lg-block bg-gray-800 text-white collapse" data-simplebar>
        <div class="sidebar-inner px-3 pt-3">
            <div class="sidebar-brand mb-3 text-center">
                <div class="d-flex flex-column align-items-center">
                    <img :src="brandLogo" @error="logoError" alt="Logo" class="img-fluid sidebar-logo mb-1" />
                    <div class="fw-bold text-uppercase small brand-text text-truncate w-100 text-center" title="{{ brandName }}">{{ brandName }}</div>
                </div>
            </div>
            <ul class="nav flex-column sidebar-menu">
                <li class="nav-item" :class="isActive(dashboardPath)">
                    <Link :href="dashboardPath" class="nav-link py-2 d-flex align-items-center">
                        <span class="sidebar-icon me-2" v-html="icons.speedometer" />
                        <span>Dashboard</span>
                    </Link>
                </li>
                <li v-for="group in groups" :key="group.key" class="mt-3">
                    <button class="btn btn-sm w-100 text-start sidebar-group-toggle px-2 py-1 d-flex align-items-center" @click="toggle(group.key)">
                        <span class="chevron me-1" :class="{ open: isOpen(group.key) }">▸</span>
                        <span class="flex-grow-1 text-truncate group-label">{{ group.label }}</span>
                        <span class="badge bg-primary-subtle text-white ms-2" v-if="group.items.length<10">{{ group.items.length }}</span>
                        <span class="badge bg-warning text-dark ms-2" v-else>+{{ group.items.length }}</span>
                    </button>
                    <transition name="fade">
                        <ul v-show="isOpen(group.key)" class="list-unstyled mb-0 mt-1">
                            <li v-for="item in group.items" :key="item.key" class="mb-1">
                                <Link :href="item.href" class="nav-link py-2 d-flex align-items-center rounded small position-relative" :class="[ isCurrent(item.href) ? 'active highlight-pulse' : '' ]">
                                    <span class="sidebar-icon me-2" v-html="item.icon" />
                                    <span class="flex-grow-1 text-truncate">{{ item.label }}</span>
                                    <span v-if="isCurrent(item.href)" class="bullet-active"></span>
                                </Link>
                            </li>
                        </ul>
                    </transition>
                </li>
                <li class="mt-4">
                    <Link href="/logout" method="post" as="button" class="nav-link nav-link-logout d-flex align-items-center py-2 w-100">
                        <span class="sidebar-icon me-2" v-html="icons.logout" />
                        <span>Keluar</span>
                    </Link>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, reactive } from 'vue';

const icons = {
    speedometer: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-speedometer2"><path d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4zM3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 9.31a.91.91 0 1 0 1.302 1.258l3.434-4.297a.389.389 0 0 0-.029-.518z"/><path fill-rule="evenodd" d="M0 10a8 8 0 1 1 15.547 2.661c-.442 1.253-1.845 1.602-2.932 1.25C11.309 13.488 9.475 13 8 13c-1.474 0-3.31.488-4.615.911-1.087.352-2.49.003-2.932-1.25A7.988 7.988 0 0 1 0 10zm8-7a7 7 0 0 0-6.603 9.329c.203.575.923.876 1.68.63C4.397 12.533 6.358 12 8 12s3.604.532 4.923.96c.757.245 1.477-.056 1.68-.631A7 7 0 0 0 8 3z"/></svg>',
    exams: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square"><path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293z"/><path d="M13.459 4.69 5 13.146V15h1.854L15.459 5.854l-2-2z"/><path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h9a.5.5 0 0 0 0-1h-9a.5.5 0 0 1-.5-.5v-9A.5.5 0 0 1 2.5 4H8a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 4.5v9z"/></svg>',
    session: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-stopwatch"><path d="M6.5 1A.5.5 0 0 1 7 .5h2a.5.5 0 0 1 0 1v.55a7 7 0 1 1-2 0V1.5a.5.5 0 0 1-.5-.5z"/><path d="M8 4a.5.5 0 0 1 .5.5v4.21l2.248 1.299a.5.5 0 0 1-.496.868l-2.5-1.444A.5.5 0 0 1 7.5 9.5v-5A.5.5 0 0 1 8 4z"/></svg>',
    assignment: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-journal-text"><path d="M5 8a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5A.5.5 0 0 1 5 8Zm0 2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5A.5.5 0 0 1 5 10Zm0-4a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5A.5.5 0 0 1 5 6Z"/><path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2Z"/></svg>',
    tryout: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-check"><path fill-rule="evenodd" d="M10.854 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0L5.5 8.207a.5.5 0 0 1 .708-.708L7.5 8.793l2.646-2.647a.5.5 0 0 1 .708 0z"/><path d="M10 1.5v1h1a1 1 0 0 1 1 1V14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V3.5a1 1 0 0 1 1-1h1v-1A1.5 1.5 0 0 1 7.5 0h1A1.5 1.5 0 0 1 10 1.5zM6 2v1h4V2a.5.5 0 0 0-.5-.5h-3A.5.5 0 0 0 6 2z"/></svg>',
    results: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-journal-check"><path fill-rule="evenodd" d="M10.854 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0L5.5 8.207a.5.5 0 0 1 .708-.708L7.5 8.793l2.646-2.647a.5.5 0 0 1 .708 0z"/><path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2z"/></svg>',
    reports: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-graph-up-arrow"><path fill-rule="evenodd" d="M0 0h1v15h15v1H0V0Zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.9l-3.613 4.417a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61L13.445 4H10.5a.5.5 0 0 1-.5-.5Z"/></svg>',
    monitor: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/><path d="M8 5.5a2.5 2.5 0 1 1 0 5a2.5 2.5 0 0 1 0-5z"/></svg>',
    control: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-power"><path d="M7.5 1v7h1V1h-1z"/><path d="M3.05 3.05a7 7 0 1 0 9.9 9.9l-.707-.707a6 6 0 1 1-8.486-8.486l.707-.707z"/></svg>',
    proctorSession: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-camera-video"><path d="M0 5a2 2 0 0 1 2-2h7.5A2.5 2.5 0 0 1 12 5.5v1.14l2.386-1.193A.5.5 0 0 1 15 5.89v4.22a.5.5 0 0 1-.614.474L12 9.39V10.5A2.5 2.5 0 0 1 9.5 13H2a2 2 0 0 1-2-2V5z"/></svg>',
    violations: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle"><path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057z"/><path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/></svg>',
    photos: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-camera"><path d="M15 12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h1.172a3 3 0 0 0 2.12-.879l.83-.828A1 1 0 0 1 6.827 3h2.344a1 1 0 0 1 .707.293l.828.828A3 3 0 0 0 12.828 5H14a1 1 0 0 1 1 1v6z"/><path d="M8 11a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5z"/></svg>',
    activity: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-activity"><path fill-rule="evenodd" d="M6 2a.5.5 0 0 1 .47.33L8.667 8.5l1.2-3a.5.5 0 0 1 .933.002L12.5 9H15a.5.5 0 0 1 0 1h-2.8a.5.5 0 0 1-.467-.324L10.8 7.5l-1.2 3a.5.5 0 0 1-.934 0L6.53 3.67 5.2 7H1a.5.5 0 0 1 0-1h3.6a.5.5 0 0 1 .467.324L6 2z"/></svg>',
    network: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-wifi"><path d="M15.384 6.115a.485.485 0 0 0-.047-.736A12.444 12.444 0 0 0 8 3C5.259 3 2.723 3.882.663 5.379a.485.485 0 0 0-.047.736.525.525 0 0 0 .668.05A11.448 11.448 0 0 1 8 4c2.507 0 4.827.892 6.716 2.164a.525.525 0 0 0 .668-.05z"/><path d="M13.229 8.271a.482.482 0 0 0-.063-.745A9.455 9.455 0 0 0 8 6c-1.905 0-3.68.56-5.166 1.526a.48.48 0 0 0-.063.745.525.525 0 0 0 .652.065A8.46 8.46 0 0 1 8 7a8.46 8.46 0 0 1 4.576 1.336c.206.132.48.108.653-.065zm-2.183 2.183c.226-.226.185-.605-.1-.75A6.473 6.473 0 0 0 8 9c-1.06 0-2.062.254-2.946.704-.285.145-.326.524-.1.75l.015.015c.16.16.407.19.611.09A5.478 5.478 0 0 1 8 10c.868 0 1.69.201 2.42.56.203.1.45.07.61-.091l.016-.015zM9.06 12.44c.196-.196.198-.52-.04-.66A1.99 1.99 0 0 0 8 11.5a1.99 1.99 0 0 0-1.02.28c-.238.14-.236.464-.04.66l.706.706a.5.5 0 0 0 .708 0l.707-.707z"/></svg>',
    classroom: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-back"><path d="M0 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2h2a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2H2a2 2 0 0 1-2-2V2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H2z"/></svg>',
    students: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill"><path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/><path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/><path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/></svg>',
    lessons: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmarks"><path d="M2 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L7 13.101l-4.223 2.815A.5.5 0 0 1 2 15.5V4z"/><path d="M4.268 1H12a1 1 0 0 1 1 1v11.768l.223.148A.5.5 0 0 0 14 13.5V2a2 2 0 0 0-2-2H6a2 2 0 0 0-1.732 1z"/></svg>',
    guardians: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people"><path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Z"/><path d="M11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm-7 7s-1 0-1-1 1-4 5-4a5.5 5.5 0 0 1 2.5.598 5.5 5.5 0 0 0-2.5-.598c-4 0-5 3-5 4s1 1 1 1h8Z"/><path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/></svg>',
    admins: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shield-lock"><path d="M5.5 9a1.5 1.5 0 1 1 3 0v1.035a.5.5 0 0 1-.146.354l-1 1a.5.5 0 0 1-.708 0l-1-1A.5.5 0 0 1 5.5 10.035V9z"/><path d="M8 0c-.69 0-1.542.218-2.516.56C3.485 1.246 1.982 1.8.727 2.02A.5.5 0 0 0 0 2.5v4.764c0 3.587 2.507 6.614 6.684 8.49a.5.5 0 0 0 .632 0C11.993 13.878 15 10.85 15 7.264V2.5a.5.5 0 0 0-.727-.48c-1.255.22-2.758.774-4.757 1.46C9.542.218 8.69 0 8 0z"/></svg>',
    addAdmin: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus"><path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/><path d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/><path d="M4 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H4z"/></svg>',
    dinas: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bank"><path d="M8 0 0 4l8 4 8-4-8-4Zm6 10v2h1v1H1v-1h1v-2H1V9h14v1h-1Zm-2 0H4v2h8v-2Z"/></svg>',
    settings: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear"><path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/><path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319z"/></svg>',
    logout: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-box-arrow-right"><path d="M10 15a1 1 0 0 0 1-1v-3h-1v3H2V2h8v3h1V2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8z"/><path d="M15.354 8.354a.5.5 0 0 0 0-.708L12.172 4.464a.5.5 0 1 0-.708.708L13.293 7.5H6.5a.5.5 0 0 0 0 1h6.793l-1.829 2.328a.5.5 0 1 0 .708.708l3.182-3.182z"/></svg>'
};

const userRole = computed(()=> page.props.auth?.user?.role || 'admin');
// dynamic brand name (sidebar title) using cbt_name > site_name > fallback
const brandName = computed(()=> (page.props.branding?.cbt_name || page.props.branding?.site_name || 'CBT').trim());
const brandLogo = computed(()=> {
    let base = page.props.branding?.school_logo || '';
    // If backend generated //localhost/storage path adjust to current host
    if(base && base.startsWith('/storage/')){
        base = `${window.location.origin}${base}`;
    }
    if(!base){
        // ultimate minimal fallback (1x1 transparent) kalau belum ada logo
        base = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';
    }
    const bust = page.props.branding?.logo_cache_bust;
    return bust && base.includes('/storage/') ? `${base}?v=${bust}` : base;
});
let logoErrored = false;
function logoError(e){
    if(!logoErrored){
        logoErrored = true;
        e.target.src = '/assets/images/logo.png';
    }
}

// Dynamic dashboard path per role (ensures guru melihat /teacher/dashboard bukan /admin/dashboard)
const dashboardPath = computed(()=> {
    switch(userRole.value){
        case 'teacher': return '/teacher/dashboard';
        case 'operator': return '/operator/dashboard';
    case 'dinas': return '/dinas/dashboard';
    case 'parent': return '/parent/grades';
        default: return '/admin/dashboard';
    }
});

// Base admin groups used for admin directly and as template for teacher filtering
function buildAdminBaseGroups(){
    return [
        { key: 'ujian', label: 'Ujian & Penilaian', items: [
            { key: 'exams', label: 'Ujian', href: '/admin/exams', icon: icons.exams },
            { key: 'sessions', label: 'Sesi Ujian', href: '/admin/exam_sessions', icon: icons.session },
            { key: 'assignments', label: 'Tugas Harian', href: '/admin/assignments', icon: icons.assignment },
            { key: 'tryouts', label: 'Tryout', href: '/admin/tryouts', icon: icons.tryout },
            { key: 'results', label: 'Hasil Ujian', href: '/admin/results', icon: icons.results },
            { key: 'reports', label: 'Laporan Nilai', href: '/admin/reports', icon: icons.reports },
        ]},
        { key: 'monitor', label: 'Monitoring & Proctoring', items: [
            { key: 'monitoring', label: 'Monitoring Ujian', href: '/admin/monitor', icon: icons.monitor },
            { key: 'control', label: 'Kontrol Ujian', href: '/admin/exam-control', icon: icons.control },
            { key: 'proctor-sessions', label: 'Sesi Proctoring', href: '/admin/proctoring/sessions', icon: icons.proctorSession },
            { key: 'violations', label: 'Pelanggaran', href: '/admin/proctoring/violations', icon: icons.violations },
            { key: 'photos', label: 'Foto Proctoring', href: '/admin/proctoring/photos', icon: icons.photos },
            { key: 'activity', label: 'Log Aktivitas', href: '/admin/proctoring/activities', icon: icons.activity },
            { key: 'network', label: 'Status Jaringan', href: '/admin/proctoring/network', icon: icons.network },
        ]},
        { key: 'master', label: 'Data Master', items: [
            { key: 'classrooms', label: 'Kelas', href: '/admin/classrooms', icon: icons.classroom },
            { key: 'students', label: 'Siswa', href: '/admin/students', icon: icons.students },
            { key: 'lessons', label: 'Mata Pelajaran', href: '/admin/lessons', icon: icons.lessons },
            { key: 'guardians', label: 'Orang Tua', href: '/admin/guardians', icon: icons.guardians },
            { key: 'admins', label: 'Pengguna', href: '/admin/admins', icon: icons.admins },
            { key: 'add-admin', label: 'Tambah Admin', href: '/admin/admins/create', icon: icons.addAdmin },
        ]},
        { key: 'dinas', label: 'Dinas Pendidikan', items: [
            { key: 'dinas-dashboard', label: 'Dashboard Dinas', href: '/admin/dinas', icon: icons.dinas },
            { key: 'dinas-monitor', label: 'Monitoring', href: '/admin/dinas/monitor', icon: icons.activity },
        ]},
        { key: 'settings', label: 'Pengaturan', items: [
            { key: 'settings', label: 'Pengaturan', href: '/admin/settings', icon: icons.settings },
            { key: 'proctor-settings', label: 'Proctoring', href: '/admin/proctoring/settings', icon: icons.settings },
        ]},
    ];
}

const groups = computed(() => {
    const role = userRole.value;
    if(role === 'teacher') {
        // added 'tryouts' so guru melihat menu Tryout (ditampilkan setelah Tugas Harian)
        const allowed = new Set(['exams','sessions','assignments','tryouts','questions','reports','monitoring','control','classrooms','students']);
        const teacherRouteMap = {
            exams: '/teacher/exams',
            sessions: '/teacher/exam-sessions', // note admin uses underscore variant
            assignments: '/teacher/assignments',
            tryouts: '/teacher/tryouts',
            questions: '/teacher/questions',
            reports: '/teacher/reports',
            monitoring: '/teacher/monitor',
            control: '/teacher/exam-control',
            classrooms: '/teacher/classrooms',
            students: '/teacher/students',
        };
        return buildAdminBaseGroups()
            .map(g => {
                // clone & filter items
                let items = g.items.filter(it => allowed.has(it.key)).map(it => ({...it, href: teacherRouteMap[it.key] || it.href}));
                // ensure ordering: place tryouts right after assignments if both exist
                if(g.key === 'ujian') {
                    const order = ['exams','sessions','assignments','tryouts','questions','reports'];
                    items = items.sort((a,b)=> order.indexOf(a.key) - order.indexOf(b.key));
                }
                if(g.key === 'monitor') {
                    return { ...g, label: 'Monitoring & Kontrol', items };
                }
                return { ...g, items };
            })
            .filter(g => g.items.length > 0) // drop empty groups (dinas, settings, etc.)
            .filter(g => ['ujian','monitor','master'].includes(g.key)); // only keep main three
    }
    if(role === 'operator') {
        return [
            { key: 'ujian', label: 'Ujian & Penilaian', items: [
                { key: 'exams', label: 'Ujian', href: '/operator/exams', icon: icons.exams },
                { key: 'sessions', label: 'Sesi Ujian', href: '/operator/exam-sessions', icon: icons.session },
                { key: 'assignments', label: 'Tugas Harian', href: '/operator/assignments', icon: icons.assignment },
                { key: 'tryouts', label: 'Tryout', href: '/operator/tryouts', icon: icons.tryout },
                { key: 'questions', label: 'Bank Soal', href: '/operator/questions', icon: icons.assignment },
                { key: 'reports', label: 'Laporan Nilai', href: '/operator/reports', icon: icons.reports },
            ]},
            { key: 'monitor', label: 'Monitoring & Kontrol', items: [
                { key: 'monitoring', label: 'Monitoring Ujian', href: '/operator/monitor', icon: icons.monitor },
                { key: 'control', label: 'Kontrol Ujian', href: '/operator/exam-control', icon: icons.control },
            ]},
            { key: 'master', label: 'Data Master', items: [
                { key: 'classrooms', label: 'Kelas', href: '/operator/classrooms', icon: icons.classroom },
                { key: 'students', label: 'Siswa', href: '/operator/students', icon: icons.students },
                { key: 'lessons', label: 'Mata Pelajaran', href: '/operator/lessons', icon: icons.lessons },
            ]},
        ];
    }
    if(role === 'dinas') {
        return [
            { key: 'monitor', label: 'Monitoring', items: [
                { key: 'dashboard', label: 'Dashboard', href: '/dinas/dashboard', icon: icons.dinas },
                { key: 'monitoring', label: 'Monitoring', href: '/dinas/monitor', icon: icons.activity },
            ]},
        ];
    }
    if(role === 'parent') {
        return [
            { key: 'nilai', label: 'Nilai Anak', items: [
                { key: 'grades', label: 'Nilai Anak', href: '/parent/grades', icon: icons.reports },
            ]},
        ];
    }
    // default admin
    return buildAdminBaseGroups();
});

const page = usePage();
const isCurrent = (path) => page.url.startsWith(path);
const isActive = (path) => isCurrent(path) ? 'active' : '';

// Collapsible state with localStorage persistence
const state = reactive({ open: JSON.parse(localStorage.getItem('sidebar.open')||'{}') });
function toggle(key){ state.open[key] = !state.open[key]; persist(); }
function isOpen(key){ return state.open[key] !== false; }
function persist(){ localStorage.setItem('sidebar.open', JSON.stringify(state.open)); }
</script>

<style>
.sidebar-menu .nav-link { color:#d1d5db; font-size:13px; }
.sidebar-menu .nav-link.active, .sidebar-menu .nav-link:hover { background:rgba(255,255,255,.08); color:#fff; }
.group-label { font-size:10px; letter-spacing:.5px; }
.bullet-active { width:6px; height:6px; border-radius:50%; background:#0d6efd; margin-left:4px; }
.nav-link-logout { background:#dc3545 !important; color:#fff !important; border-radius:8px; transition:.25s; }
.nav-link-logout:hover { background:#c82333 !important; color:#fff !important; transform:translateX(2px); }
.nav-link-logout:focus,.nav-link-logout:active { background:#bd2130 !important; box-shadow:0 0 0 0.2rem rgba(220,53,69,.25); }
/* collapsible */
.sidebar-group-toggle { background:rgba(255,255,255,.04); color:#e2e8f0; font-size:13px; font-weight:600; border:1px solid rgba(255,255,255,.07); border-radius:8px; transition:.2s; padding-top:.55rem!important; padding-bottom:.55rem!important; }
.sidebar-group-toggle:hover { background:rgba(255,255,255,.1); color:#fff; }
.group-label { font-size:13px; letter-spacing:.3px; }
.chevron { display:inline-block; transition:transform .25s; font-size:11px; opacity:.8; }
.chevron.open { transform:rotate(90deg); opacity:1; }
.fade-enter-active,.fade-leave-active { transition: all .25s ease; }
.fade-enter-from,.fade-leave-to { opacity:0; transform:translateY(-3px); }
.highlight-pulse { position:relative; }
.highlight-pulse::after { content:""; position:absolute; inset:0; border:1px solid rgba(13,110,253,.6); border-radius:8px; animation:pulse 2s infinite; }
@keyframes pulse { 0%{opacity:.7} 60%{opacity:0; transform:scale(1.08);} 100%{opacity:0;} }
.sidebar-logo { max-height:72px; max-width:180px; object-fit:contain; filter:drop-shadow(0 2px 4px rgba(0,0,0,.35)); }
.brand-text { letter-spacing:.6px; }
</style>