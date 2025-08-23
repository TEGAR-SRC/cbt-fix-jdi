<template>
  <nav class="navbar navbar-light bg-white border-bottom px-4 col-12 d-lg-none">
    <a class="navbar-brand me-lg-5" :href="`${basePath}/dashboard`">
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

  <component :is="sidebarComponent" />

  <main class="content bg-body-neo">
    <slot />
  </main>
</template>

<script>
import TeacherSidebar from '../Components/TeacherSidebar.vue'
import OperatorSidebar from '../Components/OperatorSidebar.vue'

export default {
  components: { TeacherSidebar, OperatorSidebar },
  computed: {
    basePath() {
      // Determine role prefix from current Inertia page URL
      const seg = (this.$page?.url || window.location.pathname).split('/')[1] || 'teacher'
      return `/${seg}`
    },
    sidebarComponent() {
      const seg = (this.$page?.url || window.location.pathname).split('/')[1]
      if (seg === 'operator') return 'OperatorSidebar'
      return 'TeacherSidebar'
    }
  }
}
</script>

<style>
body, .content { background: #f8fafc !important; }
.content { min-height: 100vh; }
.bg-body-neo { background: #f8fafc; }
</style>
