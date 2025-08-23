<template>
    <Head>
        <title>Edit Admin - Aplikasi Ujian Online</title>
    </Head>
    <div class="container-fluid mb-5 mt-5">
        <div class="row">
            <div class="col-md-12">
                <Link href="/admin/admins" class="btn btn-md btn-primary border-0 shadow mb-3" type="button"><i class="fa fa-long-arrow-alt-left me-2"></i> Kembali</Link>
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h5><i class="fa fa-user-shield"></i> Edit Admin</h5>
                        <hr>
                        <form @submit.prevent="submit">

                            <div class="mb-4">
                                <label>Nama</label> 
                                <input type="text" class="form-control" placeholder="Masukkan Nama" v-model="form.name">
                                <div v-if="errors.name" class="alert alert-danger mt-2">
                                    {{ errors.name }}
                                </div>
                            </div>

                            <div class="mb-4">
                                <label>Email</label> 
                                <input type="email" class="form-control" placeholder="Masukkan Email" v-model="form.email">
                                <div v-if="errors.email" class="alert alert-danger mt-2">
                                    {{ errors.email }}
                                </div>
                            </div>

                            <div class="mb-4">
                                <label>Password (kosongkan jika tidak diganti)</label> 
                                <input type="password" class="form-control" placeholder="Masukkan Password" v-model="form.password">
                                <div v-if="errors.password" class="alert alert-danger mt-2">
                                    {{ errors.password }}
                                </div>
                            </div>

                            <div class="mb-4">
                                <label>Konfirmasi Password</label> 
                                <input type="password" class="form-control" placeholder="Ulangi Password" v-model="form.password_confirmation">
                            </div>
                            
                            <button type="submit" class="btn btn-md btn-primary border-0 shadow me-2">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import LayoutAdmin from '../../../Layouts/Admin.vue';
    import { Head, Link, router } from '@inertiajs/vue3';
    import { reactive } from 'vue';
    import Swal from 'sweetalert2';

    export default {
        layout: LayoutAdmin,
        components: { Head, Link },
        props: { errors: Object, admin: Object },
        setup(props) {
            const form = reactive({ 
                name: props.admin.name, 
                email: props.admin.email, 
                password: '', 
                password_confirmation: '' 
            });

            const submit = () => {
                router.put(`/admin/admins/${props.admin.id}`, {
                    name: form.name,
                    email: form.email,
                    password: form.password,
                    password_confirmation: form.password_confirmation,
                }, {
                    onSuccess: () => {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Admin Berhasil Diupdate!.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    },
                });
            };

            return { form, submit };
        }
    }
</script>

<style>
</style>
