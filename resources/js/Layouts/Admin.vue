<template>
    <nav class="navbar navbar-light bg-white border-bottom px-4 col-12 d-lg-none">
        <a class="navbar-brand me-lg-5" href="/">
            <img class="navbar-brand-dark" src=""/> 
            <img class="navbar-brand-light" src=""/>
        </a>
        <div class="d-flex align-items-center">
            <button class="navbar-toggler d-lg-none collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <!-- sidebar -->
    <Sidebar />

    <main class="content bg-body-neo">

        <!-- navbar -->
        <Navbar />

        <!-- content -->
        <slot />

    </main>

</template>

<script>
    //import navbar
    import Navbar from "../Components/Navbar.vue";

    //import sidebar
    import Sidebar from '../Components/Sidebar.vue';

    export default {

        //register components
        components: {
            Navbar,
            Sidebar
        },
    }

</script>

<style>
/* Clean white background for SumoPod/Edupus style */
body, .content {
    background: #f8fafc !important;
}
.content {
    min-height: 100vh;
}
.bg-body-neo { background: #f8fafc; }

/* Softer cards across the app */
.content .card { border: 1px solid #e6eaf2; border-radius: 14px; background:#ffffff; }
.content .card.shadow-sm { box-shadow: 0 .35rem 1rem rgba(0,0,0,.35)!important; }
</style>