<template>
    <div class="d-flex">
        <!-- Sidebar -->
        <Sidebar />
        
        <!-- Main Content -->
        <div class="content bg-body-neo flex-grow-1">
            <!-- Navbar -->
            <Navbar />
            
            <!-- Page Content -->
            <div class="container-fluid">
                <slot />
            </div>
        </div>
    </div>
</template>

<script>
import Navbar from "../Components/Navbar.vue";
import Sidebar from '../Components/Sidebar.vue';

export default {
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

/* Ensure sidebar is visible */
.sidebar {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    height: 100vh !important;
    z-index: 1000 !important;
    width: 280px !important;
    overflow-y: auto !important;
}

/* Ensure proper layout */
.content {
    margin-left: 280px !important;
}

@media (max-width: 991.98px) {
    .content {
        margin-left: 0 !important;
    }
}
</style>