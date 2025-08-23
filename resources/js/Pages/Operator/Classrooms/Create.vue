<template>
  <Head>
    <title>Tambah Kelas - Operator</title>
  </Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row">
      <div class="col-md-12">
        <Link href="/operator/classrooms" class="btn btn-md btn-primary border-0 shadow mb-3">
          <i class="fa fa-long-arrow-alt-left me-2"></i> Kembali
        </Link>
        <div class="card border-0 shadow">
          <div class="card-body">
            <h5><i class="fa fa-school"></i> Tambah Kelas</h5>
            <hr>
            <form @submit.prevent="submit">
              <div class="mb-4">
                <label>Nama Kelas</label>
                <input type="text" class="form-control" v-model="form.title" placeholder="cth: XII IPA 1" />
                <div v-if="errors.title" class="alert alert-danger mt-2">{{ errors.title }}</div>
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
import OperatorLayout from '../../../Layouts/Operator.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';

export default {
  layout: OperatorLayout,
  components: { Head, Link },
  props: { errors: Object },
  setup() {
    const form = reactive({ title: '' });
    
    const submit = () => {
      router.post('/operator/classrooms', { ...form });
    };
    
    return { form, submit };
  }
}
</script>

<style></style>
