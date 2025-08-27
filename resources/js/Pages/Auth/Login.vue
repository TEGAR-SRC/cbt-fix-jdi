<template>
    <Head title="Login Administrator - Aplikasi Ujian Online" />
    <div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-500 position-relative overflow-hidden">
        <div class="text-center mb-4">
            <img :src="brandLogo" @error="logoError" alt="Logo" class="login-box-logo larger mb-3" />
            <h3 class="h5 fw-bold mb-0">SELAMAT DATANG</h3>
        </div>
        <form @submit.prevent="submit" class="mt-4">
            <div class="form-group mb-4">
                <label for="email">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-envelope"></i></span>
                    <input type="email" class="form-control" v-model="form.email" placeholder="Email Address">
                </div>
                <div v-if="form.errors.email" class="alert alert-danger mt-2">{{ form.errors.email }}</div>
            </div>
            <div class="form-group mb-4">
                <label for="password">Password</label>
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon2"><i class="fa fa-lock"></i></span>
                    <input type="password" placeholder="Password" class="form-control" v-model="form.password">
                </div>
                <div v-if="form.errors.password" class="alert alert-danger mt-2">{{ form.errors.password }}</div>
            </div>
            <div class="d-flex justify-content-between align-items-top mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="remember">
                    <label class="form-check-label mb-0" for="remember">Remember me</label>
                </div>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-gray-800" :disabled="form.processing">{{ form.processing ? 'Loading...' : 'LOGIN' }}</button>
            </div>
        </form>
    </div>
</template>

<script setup>
import LayoutAuth from '../../Layouts/Auth.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
defineOptions({ layout: LayoutAuth });
const form = useForm({ email:'', password:'' });
// POST endpoint tetap /login (hanya halaman GET yang dipindah ke /secure-admin)
function submit(){ form.post('/login'); }
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