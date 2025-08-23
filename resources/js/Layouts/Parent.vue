<template>
  <nav class="navbar navbar-light bg-white border-bottom px-4 col-12 d-lg-none">
    <a class="navbar-brand me-lg-5" href="/"><img class="navbar-brand-light" src=""/></a>
    <div class="d-flex align-items-center">
      <button class="navbar-toggler d-lg-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </nav>
  <ParentSidebar />
  <main class="content bg-body-neo">
    <Navbar />
    <slot />
  </main>
</template>

<script>
import Navbar from "../Components/Navbar.vue";
import ParentSidebar from '../Components/ParentSidebar.vue';
export default { components: { Navbar, ParentSidebar } }
</script>

<style>
.bg-body-neo { background: #f8fafc; }
.content { min-height: 100vh; }
.content .card { border: 1px solid #e6eaf2; border-radius: 14px; background:#ffffff; }
</style>
