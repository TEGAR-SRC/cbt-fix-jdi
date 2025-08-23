<template>
  <Head><title>Tambah Orang Tua - Admin</title></Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row">
      <div class="col-md-12">
        <Link href="/admin/guardians" class="btn btn-md btn-primary border-0 shadow mb-3"><i class="fa fa-long-arrow-alt-left me-2"></i> Kembali</Link>
        <div class="card border-0 shadow">
          <div class="card-body">
            <h5><i class="fa fa-user-plus"></i> Tambah Orang Tua</h5><hr>
            <form @submit.prevent="submit">
              <div class="row g-3">
                <div class="col-md-4"><label class="form-label">Nama</label><input v-model="form.name" class="form-control" required /></div>
                <div class="col-md-4"><label class="form-label">Email</label><input v-model="form.email" type="email" class="form-control" /></div>
                <div class="col-md-4"><label class="form-label">Telepon</label><input v-model="form.phone" class="form-control" /></div>
              </div>
              <div class="mt-4">
                <button class="btn btn-primary">Simpan</button>
                <button class="btn btn-warning ms-2" type="reset">Reset</button>
              </div>
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
export default {
  layout: LayoutAdmin,
  components: { Head, Link },
  setup(){
    const form = { name:'', email:'', phone:'' }
    const submit = () => router.post('/admin/guardians', form)
    return { form, submit }
  }
}
</script>
