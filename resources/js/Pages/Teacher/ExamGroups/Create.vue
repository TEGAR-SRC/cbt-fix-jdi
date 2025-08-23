<template>
  <Head>
    <title>Enrolle Siswa - Guru</title>
  </Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row">
      <div class="col-md-12">
  <Link :href="`${basePath}/exam-sessions/${exam_session.id}`" class="btn btn-md btn-primary border-0 shadow mb-3"><i class="fa fa-long-arrow-alt-left me-2"></i> Kembali</Link>
        <div class="card border-0 shadow">
          <div class="card-body">
            <h5><i class="fa fa-user-plus"></i> Enrolle Siswa</h5>
            <hr>
            <form @submit.prevent="submit">
              <div class="table-responsive mb-4">
                <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                  <thead class="thead-dark">
                    <tr class="border-0">
                      <th class="border-0 rounded-start" style="width:5%">
                        <input type="checkbox" v-model="form.allSelected" @change="selectAll" />
                      </th>
                      <th class="border-0">Nama Siswa</th>
                      <th class="border-0">Kelas</th>
                      <th class="border-0">Jenis Kelamin</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="student of students" :key="student.id">
                      <td>
                        <input type="checkbox" v-model="form.student_id" :id="student.id" :value="student.id" number :checked="form.allSelected" />
                      </td>
                      <td>{{ student.name }}</td>
                      <td class="text-center">{{ student.classroom.title }}</td>
                      <td class="text-center">{{ student.gender }}</td>
                    </tr>
                  </tbody>
                </table>
                <div v-if="errors.student_id" class="alert alert-danger mt-2">{{ errors.student_id }}</div>
              </div>
              <button type="submit" class="btn btn-md btn-primary border-0 shadow me-2">Simpan</button>
              <button type="reset" class="btn btn-md btn-warning border-0 shadow">Reset</button>
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
import Swal from 'sweetalert2';

export default {
  layout: StaffLayout,
  components: { Head, Link },
  props: { errors: Object, exam: Object, exam_session: Object, students: Array },
  setup(props) {
    const form = reactive({ exam_id: props.exam.id, student_id: [], allSelected: false });
    const selectAll = () => { form.student_id = form.allSelected ? props.students.map(s => s.id) : []; };
    const basePath = `/${(window?.app?.page?.url || window.location.pathname).split('/')[1] || 'teacher'}`;
    const submit = () => {
      router.post(`${basePath}/exam-sessions/${props.exam_session.id}/enrolle/store`, { exam_id: form.exam_id, student_id: form.student_id }, {
        onSuccess: () => Swal.fire({ title: 'Success!', text: 'Enrolle Siswa Berhasil Disimpan!.', icon: 'success', timer: 2000, showConfirmButton: false }),
      });
    };
    return { form, selectAll, submit, basePath };
  }
}
</script>

<style></style>
