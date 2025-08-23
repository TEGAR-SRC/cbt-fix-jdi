<template>
    <nav class="navbar navbar-top navbar-expand navbar-dashboard bg-dark text-white border-0 ps-0 pe-2 pb-0 shadow-sm sticky-top neo-blur">
    <div class="container-fluid px-0">
            <div class="d-flex justify-content-between w-100" id="navbarSupportedContent">
        <div class="d-flex align-items-center gap-2 ps-2">
            <img v-if="$page.props.branding?.school_logo" :src="$page.props.branding.school_logo" alt="logo" style="height:28px"/>
                    <span class="fw-semibold text-white" style="text-shadow: 0 1px 2px rgba(0,0,0,.4)">{{ $page.props.branding?.site_name || 'CBT AI' }}</span>
                </div>
                <!-- Navbar links -->
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item dropdown ms-lg-3">
                        <a class="nav-link dropdown-toggle pt-1 px-0" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <div class="media d-flex align-items-center">
                                <img class="avatar rounded-circle" alt="Image placeholder" :src="`https://ui-avatars.com/api/?name=${$page.props.auth.user.name}&amp;background=0d6efd&amp;color=ffffff&amp;size=100`">
                                <div class="media-body ms-2 align-items-center d-none d-lg-block">
                                    <span class="mb-0 font-small fw-bold text-dark">{{ $page.props.auth.user.name }}</span>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dashboard-dropdown dropdown-menu-end mt-2 py-1 border-0 shadow">
                            <Link class="dropdown-item d-flex align-items-center" href="/logout" method="post" as="button">
                                <svg class="dropdown-icon text-danger me-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                    </path>
                                </svg>
                                Logout
                            </Link>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>

    //import Link
    import { Link } from '@inertiajs/vue3';

    export default {

        //register component
        components: {
            Link,
        }
    }

</script>

<style>
/* Navbar polish */
.navbar-dashboard.bg-dark { background:#1c1c1d !important; }
.navbar-dashboard .avatar { box-shadow: 0 0 0 2px rgba(13,110,253,.45); }
.neo-blur { backdrop-filter: saturate(1.2) blur(6px); }
</style>