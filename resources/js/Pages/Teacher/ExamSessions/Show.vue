<template>
  <Head>
    <title>Detail Sesi Ujian - Guru</title>
  </Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row">
      <div class="col-md-12">
  <Link :href="`${basePath}/exam-sessions`" class="btn btn-md btn-primary border-0 shadow mb-3" type="button"><i class="fa fa-long-arrow-alt-left me-2"></i> Kembali</Link>
        <div class="card border-0 shadow">
          <div class="card-body">
            <h5><i class="fas fa-stopwatch"></i> Detail Sesi</h5>
            <hr>
            <div class="row">
              <div class="col-md-6">
                <ul class="list-unstyled">
                  <li><strong>Ujian:</strong> {{ exam_session.exam.title }}</li>
                  <li><strong>Kelas:</strong> {{ exam_session.exam.classroom.title }}</li>
                  <li><strong>Pelajaran:</strong> {{ exam_session.exam.lesson.title }}</li>
                  <li><strong>Nama Sesi:</strong> {{ exam_session.title }}</li>
                  <li><strong>Mulai:</strong> {{ exam_session.start_time }}</li>
                  <li><strong>Selesai:</strong> {{ exam_session.end_time }}</li>
                </ul>
              </div>
              <div class="col-md-6 text-end">
                <Link :href="`${basePath}/exam-sessions/${exam_session.id}/enrolle/create`" class="btn btn-md btn-primary border-0 shadow me-2"><i class="fa fa-user-plus"></i> Enrolle Siswa</Link>
              </div>
            </div>

            <div class="table-responsive mt-3">
              <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                <thead class="thead-dark">
                  <tr class="border-0">
                    <th class="border-0 rounded-start">Siswa</th>
                    <th class="border-0">Kelas</th>
                    <th class="border-0 rounded-end" style="width:10%">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="eg in exam_session.exam_groups.data" :key="eg.id">
                    <td>{{ eg.student.name }}</td>
                    <td>{{ eg.student.classroom.title }}</td>
                    <td class="text-center">
                      <button @click.prevent="destroy(eg.id)" class="btn btn-sm btn-danger border-0"><i class="fa fa-trash"></i></button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <Pagination :links="exam_session.exam_groups.links" align="end" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import StaffLayout from '../../../Layouts/Staff.vue';
import Pagination from '../../../Components/Pagination.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

export default {
  layout: StaffLayout,
  components: { Head, Link, Pagination },
  props: { exam_session: Object },
  setup(props) {
    const basePath = `/${(window?.app?.page?.url || window.location.pathname).split('/')[1] || 'teacher'}`;
    const destroy = (exam_group_id) => {
      Swal.fire({ title: 'Apakah Anda yakin?', text: 'Data tidak dapat dikembalikan!', icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Ya, hapus!' })
        .then((result) => { if (result.isConfirmed) { router.delete(`${basePath}/exam-sessions/${props.exam_session.id}/enrolle/${exam_group_id}/destroy`); } });
    };
    return { destroy, basePath };
  }
}
</script>

<style></style>
