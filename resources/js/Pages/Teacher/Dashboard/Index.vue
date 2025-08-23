<template>
    <Head>
        <title>Dashboard Guru - CBT AI</title>
    </Head>
    <TeacherLayout>
        <div class="container-fluid py-4 dashboard-root">
            <div class="mb-4">
                <h3 class="fw-semibold mb-1">Selamat datang, Guru!</h3>
                <div class="text-muted">Dashboard guru - CBT AI</div>
                <div v-if="subject" class="mt-1 small text-muted">Mata pelajaran: <span class="fw-semibold">{{ subject }}</span></div>
            </div>
            <div v-if="subject && !lessonExists" class="alert alert-warning border-0 shadow-sm">Mata pelajaran "{{ subject }}" belum ada di menu Lesson. Buat Lesson dengan judul yang sama agar data muncul.</div>
            <div class="row g-3 mb-4">
                <div class="col-md-12">
                    <div class="card border-0 shadow-sm h-100 stat-card">
                        <div class="card-body">
                            <div class="fw-semibold mb-1">Daftar Ujian Sesuai Mapel</div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Judul Ujian</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Tanggal Dibuat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="exam in exams" :key="exam.id">
                                            <td>{{ exam.title }}</td>
                                            <td>{{ exam.lesson }}</td>
                                            <td>{{ exam.created_at }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </TeacherLayout>
</template>

<script>
import TeacherLayout from '../../../Layouts/Teacher.vue'
export default {
    props: {
        subject: String,
    exams: { type: Array, default: () => [] },
    lessonExists: { type: Boolean, default: true }
    },
    components: {
        TeacherLayout
    }
}
</script>

<style>
.dashboard-root {
    background: rgb(248,250,252);
}
.card {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 2px 16px 0 rgba(32,64,122,.10);
    padding: 22px 26px;
    font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
}
.table thead th {
    background: #f3f4f6;
    color: #232a3b;
    border-bottom: 1px solid #e0e3e8;
    font-weight: 600;
}
.table tbody td {
    color: #232a3b;
    background: #fff;
}
.table-hover tbody tr:hover {
    background: #f3f4f6;
}
</style>
