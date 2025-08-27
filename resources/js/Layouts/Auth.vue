<template>
    <section class="vh-lg-100 mt-5 mt-lg-0 bg-soft d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <slot />
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
// Simplified layout without navbar logo as requested.
</script>

<style scoped>
section{background:#f5f7fb;}
</style>