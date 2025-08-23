<template>
    <nav class="navbar navbar-expand-lg navbar-transparent navbar-dark navbar-theme-primary mb-4 shadow">
        <div class="container position-relative">
            <Link class="navbar-brand me-lg-3" href="/student/dashboard">
                <img class="navbar-brand-dark" src="/assets/images/logo.png" style="height:70px">
            </Link>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                </ul>
                <form class="d-flex" v-if="$page.props.auth.student">
                    <Link href="/students/logout" method="post" class="btn btn-secondary shadow" as="button">LOGOUT</Link>
                </form>
            </div>
        </div>
    </nav>
    <div class="container">
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

<style>

</style>