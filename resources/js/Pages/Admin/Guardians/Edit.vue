<template>
  <Head><title>Edit Orang Tua - Admin</title></Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row">
      <div class="col-md-12">
        <Link href="/admin/guardians" class="btn btn-md btn-primary border-0 shadow mb-3"><i class="fa fa-long-arrow-alt-left me-2"></i> Kembali</Link>
        <div class="card border-0 shadow">
          <div class="card-body">
            <h5><i class="fa fa-user"></i> Edit Orang Tua</h5><hr>
            <form @submit.prevent="submit">
              <div class="row g-3">
                <div class="col-md-4"><label class="form-label">Nama</label><input v-model="form.name" class="form-control" required /></div>
                <div class="col-md-4"><label class="form-label">Email</label><input v-model="form.email" type="email" class="form-control" /></div>
                <div class="col-md-4"><label class="form-label">Telepon</label><input v-model="form.phone" class="form-control" /></div>
              </div>
              <div class="mt-4">
                <h6 class="fw-semibold">Relasi Siswa</h6>
                <div class="table-responsive">
                  <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                    <thead><tr><th style="width:5%"><input type="checkbox" v-model="allSelected" @change="toggleAll" /></th><th>Nama</th><th>Kelas</th></tr></thead>
                    <tbody>
                      <tr v-for="s in students" :key="s.id">
                        <td><input type="checkbox" v-model="form.student_ids" :value="s.id" /></td>
                        <td>{{ s.name }}</td>
                        <td>{{ s.classroom?.title }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="mt-4">
                <button class="btn btn-primary">Update</button>
              </div>
            </form>

            <hr>
            <h5><i class="fa fa-user-shield"></i> Akun Login Orang Tua</h5>
            <div v-if="guardian.user">
              <form @submit.prevent="updateAccount" class="mt-3">
                <div class="row g-3">
                  <div class="col-md-4"><label class="form-label">Nama</label><input v-model="acc.name" class="form-control" required /></div>
                  <div class="col-md-4"><label class="form-label">Email</label><input v-model="acc.email" type="email" class="form-control" required /></div>
                  <div class="col-md-4"><label class="form-label">Password (opsional)</label><input v-model="acc.password" type="password" class="form-control" placeholder="Kosongkan jika tidak ganti" /></div>
                </div>
                <div class="mt-3">
                  <button class="btn btn-primary">Update Akun</button>
                  <button class="btn btn-outline-danger ms-2" @click.prevent="deleteAccount">Hapus Akun</button>
                </div>
              </form>
            </div>
            <div v-else>
              <form @submit.prevent="createAccount" class="mt-3">
                <div class="row g-3">
                  <div class="col-md-4"><label class="form-label">Nama</label><input v-model="acc.name" class="form-control" required /></div>
                  <div class="col-md-4"><label class="form-label">Email</label><input v-model="acc.email" type="email" class="form-control" required /></div>
                  <div class="col-md-4"><label class="form-label">Password</label><input v-model="acc.password" type="password" class="form-control" required /></div>
                </div>
                <div class="mt-3"><button class="btn btn-success">Buat Akun</button></div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import LayoutAdmin from '../../../Layouts/Admin.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive, ref, onMounted } from 'vue';
export default {
  layout: LayoutAdmin,
  components: { Head, Link },
  props: { guardian: Object, students: Array },
  setup(props){
    const form = reactive({
      name: props.guardian.name,
      email: props.guardian.email,
      phone: props.guardian.phone,
      student_ids: (props.guardian.students || []).map(s=>s.id)
    })
    const allSelected = ref(false)
    const toggleAll = () => {
      form.student_ids = allSelected.value ? props.students.map(s=>s.id) : []
    }
    const submit = () => router.put(`/admin/guardians/${props.guardian.id}`, form)
  const acc = reactive({ name: props.guardian.user?.name || props.guardian.name || '', email: props.guardian.user?.email || '', password: '' })
  const createAccount = () => router.post(`/admin/guardians/${props.guardian.id}/account`, acc)
  const updateAccount = () => router.put(`/admin/guardians/${props.guardian.id}/account`, acc)
  const deleteAccount = () => router.delete(`/admin/guardians/${props.guardian.id}/account`)
    onMounted(()=>{})
  return { form, submit, allSelected, toggleAll, acc, createAccount, updateAccount, deleteAccount }
  }
}
</script>
