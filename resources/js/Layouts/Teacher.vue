<template>
    <nav class="navbar navbar-light bg-white border-bottom px-4 col-12 d-lg-none">
        <a class="navbar-brand me-lg-5" href="/teacher/dashboard">
            <img v-if="$page.props.branding?.school_logo" :src="$page.props.branding.school_logo" class="navbar-brand-dark" style="height:50px" alt="Logo"/>
            <img v-else class="navbar-brand-dark" src="/assets/images/logo.png" style="height:50px" alt="Logo"/>
        </a>
        <div class="d-flex align-items-center">
            <button class="navbar-toggler d-lg-none collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <Sidebar />

    <main class="content bg-body-neo">
        <slot />
    </main>
</template>

<script>
import Sidebar from '../Components/Sidebar.vue';
export default { components: { Sidebar } }
</script>

<style>
body, .content { background: #f8fafc !important; }
.content { min-height: 100vh; }
.bg-body-neo { background: #f8fafc; }
</style>
