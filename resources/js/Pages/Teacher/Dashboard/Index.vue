<template>
    <Head>
        <title>Dashboard Guru - CBT AI</title>
    </Head>
    <StaffLayout>
        <div class="container-fluid mb-5 mt-5">
            <div class="row">
                <!-- Card: Mapel -->
                <div class="col-12 col-sm-6 col-xl-3 mb-4">
                    <div class="card border-0 shadow">
                        <div class="card-body">
                            <div class="row d-block d-xl-flex align-items-center">
                                <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                                    <div class="icon-shape icon-shape-info rounded me-4 me-sm-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-bookmarks icon icon-md" viewBox="0 0 16 16">
                                            <path d="M2 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L7 13.101l-4.223 2.815A.5.5 0 0 1 2 15.5V4zm2-1a1 1 0 0 0-1 1v10.566l3.723-2.482a.5.5 0 0 1 .554 0L11 14.566V4a1 1 0 0 0-1-1H4z" />
                                            <path d="M4.268 1H12a1 1 0 0 1 1 1v11.768l.223.148A.5.5 0 0 0 14 13.5V2a2 2 0 0 0-2-2H6a2 2 0 0 0-1.732 1z" />
                                        </svg>
                                    </div>
                                    <div class="d-sm-none">
                                        <h2 class="h5">Mapel</h2>
                                        <h3 class="fw-extrabold mb-1">{{ subject || '-' }}</h3>
                                    </div>
                                </div>
                                <div class="col-12 col-xl-7 px-xl-0">
                                    <div class="d-none d-sm-block">
                                        <h2 class="h5">Mapel</h2>
                                        <h3 class="fw-extrabold mb-1">{{ subject || '-' }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Ujian Saya -->
                <div class="col-12 col-sm-6 col-xl-3 mb-4">
                    <div class="card border-0 shadow">
                        <div class="card-body">
                            <div class="row d-block d-xl-flex align-items-center">
                                <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                                    <div class="icon-shape icon-shape-tertiary rounded me-4 me-sm-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-pencil-square icon icon-md" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                        </svg>
                                    </div>
                                    <div class="d-sm-none">
                                        <h2 class="h5">Ujian Saya</h2>
                                        <h3 class="fw-extrabold mb-1">{{ exams.length }}</h3>
                                    </div>
                                </div>
                                <div class="col-12 col-xl-7 px-xl-0">
                                    <div class="d-none d-sm-block">
                                        <h2 class="h5">Ujian Saya</h2>
                                        <h3 class="fw-extrabold mb-1">{{ exams.length }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Ujian -->
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card border-0 shadow">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="fw-semibold">Daftar Ujian Sesuai Mapel</div>
                            </div>
                            <div v-if="subject && !lessonExists" class="alert alert-warning border-0 shadow-sm">Mata pelajaran "{{ subject }}" belum ada di menu Lesson. Buat Lesson dengan judul yang sama agar data muncul.</div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Judul Ujian</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Tanggal Dibuat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="exam in exams" :key="exam.id">
                                            <td class="fw-semibold">{{ exam.title }}</td>
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
    </StaffLayout>
</template>

<script>
import StaffLayout from '../../../Layouts/Staff.vue'
export default {
    props: {
        subject: String,
    exams: { type: Array, default: () => [] },
    lessonExists: { type: Boolean, default: true }
    },
    components: { StaffLayout }
}
</script>

<style>
</style>
