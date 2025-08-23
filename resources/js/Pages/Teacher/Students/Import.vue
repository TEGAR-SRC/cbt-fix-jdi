<template>
  <Head>
    <title>Import Siswa - Guru</title>
  </Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row">
      <div class="col-md-12">
  <Link :href="`${basePath}/students`" class="btn btn-md btn-primary border-0 shadow mb-3"><i class="fa fa-long-arrow-alt-left me-2"></i> Kembali</Link>
        <div class="card border-0 shadow">
          <div class="card-body">
            <h5><i class="fa fa-file-excel"></i> Import Siswa (Excel)</h5>
            <hr>
            <form @submit.prevent="submit">
              <div class="mb-4">
                <input type="file" class="form-control" @change="onFileChange" />
                <div v-if="errors.file" class="alert alert-danger mt-2">{{ errors.file }}</div>
              </div>
              <button type="submit" class="btn btn-md btn-primary border-0 shadow me-2">Upload</button>
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
import { ref } from 'vue';

export default {
  layout: StaffLayout,
  components: { Head, Link },
  props: { errors: Object },
  setup() {
    const file = ref(null);
    const onFileChange = (e) => { file.value = e.target.files[0]; };
    const basePath = `/${(window?.app?.page?.url || window.location.pathname).split('/')[1] || 'teacher'}`;
    const submit = () => {
      const formData = new FormData();
      formData.append('file', file.value);
      router.post(`${basePath}/students/import`, formData);
    };
    return { file, onFileChange, submit, basePath };
  }
}
</script>

<style></style>
