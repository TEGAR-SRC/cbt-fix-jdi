<template>
    <div class="container py-4">
        <div v-if="$page.props.auth?.student" class="d-flex justify-content-end mb-3">
            <Link href="/students/logout" method="post" as="button" class="btn btn-sm btn-outline-danger">Logout</Link>
        </div>
        <div v-if="$page.props.session && $page.props.session.error" class="alert alert-danger alert-dismissible fade show" role="alert">
            {{$page.props.session.error}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div v-if="$page.props.session && $page.props.session.success" class="alert alert-success alert-dismissible fade show" role="alert">
            {{$page.props.session.success}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <slot />
    </div>
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

<style scoped>
.container{ max-width: 980px; }
</style>