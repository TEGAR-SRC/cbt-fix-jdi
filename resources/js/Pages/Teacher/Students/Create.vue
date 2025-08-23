<template>
  <Head>
    <title>Tambah Siswa - Guru</title>
  </Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row">
      <div class="col-md-12">
  <Link :href="`${basePath}/students`" class="btn btn-md btn-primary border-0 shadow mb-3"><i class="fa fa-long-arrow-alt-left me-2"></i> Kembali</Link>
        <div class="card border-0 shadow">
          <div class="card-body">
            <h5><i class="fa fa-user-plus"></i> Tambah Siswa</h5>
            <hr>
            <form @submit.prevent="submit">
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-4">
                    <label>Nama Lengkap</label>
                    <input type="text" class="form-control" v-model="form.name" placeholder="Nama siswa" />
                    <div v-if="errors.name" class="alert alert-danger mt-2">{{ errors.name }}</div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-4">
                    <label>NISN</label>
                    <input type="text" class="form-control" v-model="form.nisn" placeholder="NISN" />
                    <div v-if="errors.nisn" class="alert alert-danger mt-2">{{ errors.nisn }}</div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="mb-4">
                    <label>Jenis Kelamin</label>
                    <select class="form-select" v-model="form.gender">
                      <option value="L">Laki-laki</option>
                      <option value="P">Perempuan</option>
                    </select>
                    <div v-if="errors.gender" class="alert alert-danger mt-2">{{ errors.gender }}</div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-4">
                    <label>Kelas</label>
                    <select class="form-select" v-model="form.classroom_id">
                      <option v-for="c in classrooms" :key="c.id" :value="c.id">{{ c.title }}</option>
                    </select>
                    <div v-if="errors.classroom_id" class="alert alert-danger mt-2">{{ errors.classroom_id }}</div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="mb-4">
                    <label>Password</label>
                    <input type="password" class="form-control" v-model="form.password" />
                    <div v-if="errors.password" class="alert alert-danger mt-2">{{ errors.password }}</div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-4">
                    <label>Konfirmasi Password</label>
                    <input type="password" class="form-control" v-model="form.password_confirmation" />
                  </div>
                </div>
              </div>

              <button type="submit" class="btn btn-md btn-primary border-0 shadow me-2">Simpan</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import StaffLayout from '../../../Layouts/Staff.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';

export default {
  layout: StaffLayout,
  components: { Head, Link },
  props: { errors: Object, classrooms: Array },
  setup() {
    const form = reactive({ name: '', nisn: '', gender: 'L', classroom_id: '', password: '', password_confirmation: '' });
    const basePath = `/${(window?.app?.page?.url || window.location.pathname).split('/')[1] || 'teacher'}`;
    const submit = () => { router.post(`${basePath}/students`, { ...form }); };
    return { form, submit, basePath };
  }
}
</script>

<style></style>
