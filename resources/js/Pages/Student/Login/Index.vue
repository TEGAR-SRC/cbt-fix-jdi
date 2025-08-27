<template>
    <Head title="Login Siswa - Aplikasi Ujian Online" />
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">
            <div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-500 position-relative overflow-hidden">
                <div v-if="form.errors.message" class="alert alert-danger mt-2">{{ form.errors.message }}</div>
                <div v-if="$page.props.session.error" class="alert alert-danger mt-2">{{ $page.props.session.error }}</div>
                <div class="text-center mb-4">
                    <img :src="brandLogo" @error="logoError" alt="Logo" class="login-box-logo mb-3 larger" />
                    <h3 class="h5 fw-bold mb-0">SELAMAT DATANG</h3>
                </div>
                <form @submit.prevent="submit" class="mt-4">

                    <div class="form-group mb-4">
                        <label for="email">Nisn</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">
                                <i class="fa fa-key"></i>
                            </span>
                            <input type="number" class="form-control" v-model="form.nisn" placeholder="Nisn">
                        </div>
                        <div v-if="form.errors.nisn" class="alert alert-danger mt-2">{{ form.errors.nisn }}</div>
                    </div>

                    <div class="form-group">
                        <div class="form-group mb-4">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon2">
                                    <i class="fa fa-lock"></i>
                                </span>
                                <input type="password" placeholder="Password" class="form-control" v-model="form.password">
                            </div>
                            <div v-if="form.errors.password" class="alert alert-danger mt-2">{{ form.errors.password }}</div>
                        </div>

                        <div class="d-flex justify-content-between align-items-top mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="remember">
                                <label class="form-check-label mb-0" for="remember">
                                    Remember me
                                </label>
                            </div>
                        </div>

                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-gray-800">LOGIN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
 </template>

<script setup>
import LayoutStudent from '../../../Layouts/Student.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
defineOptions({ layout: LayoutStudent });
const form = useForm({ nisn:'', password:'' });
function submit(){ form.post('/students/login'); }
const page = usePage();
const brandLogo = computed(()=> {
    let base = page.props.branding?.school_logo || '/assets/images/logo.png';
    if(base.startsWith('/storage/')) base = `${window.location.origin}${base}`;
    const bust = page.props.branding?.logo_cache_bust;
    return bust ? `${base}?v=${bust}` : base;
});
let logoErrored=false;function logoError(e){ if(!logoErrored){ logoErrored=true; e.target.src='/assets/images/logo.png'; } }
</script>

<style scoped>
.login-box-logo{ height:64px; max-width:220px; object-fit:contain; }
.login-box-logo.larger{ height:90px; }
</style>