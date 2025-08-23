<template>
  <Head>
    <title>Edit Kelas - Guru</title>
  </Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row">
      <div class="col-md-12">
  <Link :href="`${basePath}/classrooms`" class="btn btn-md btn-primary border-0 shadow mb-3"><i class="fa fa-long-arrow-alt-left me-2"></i> Kembali</Link>
        <div class="card border-0 shadow">
          <div class="card-body">
            <h5><i class="fa fa-school"></i> Edit Kelas</h5>
            <hr>
            <form @submit.prevent="submit">
              <div class="mb-4">
                <label>Nama Kelas</label>
                <input type="text" class="form-control" v-model="form.title" />
                <div v-if="errors.title" class="alert alert-danger mt-2">{{ errors.title }}</div>
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
import StaffLayout from '../../../Layouts/Staff.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';

export default {
  layout: StaffLayout,
  components: { Head, Link },
  props: { errors: Object, classroom: Object },
  setup(props) {
    const form = reactive({ title: props.classroom.title });
  const basePath = `/${(window?.app?.page?.url || window.location.pathname).split('/')[1] || 'teacher'}`;
  const submit = () => { router.put(`${basePath}/classrooms/${props.classroom.id}`, { ...form }); };
  return { form, submit, basePath };
  }
}
</script>

<style></style>
