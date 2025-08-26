<template>
    <Head>
        <title>Dashboard Siswa - Aplikasi Ujian Online</title>
    </Head>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success border-0 shadow">
                Selamat Datang <strong>{{ auth.student.name }}</strong>
            </div>
        </div>
    </div>
    <div class="row" v-if="exam_groups.length > 0">
        <div class="col-md-6" v-for="(data, index) in exam_groups" :key="index">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <h5>{{ data.exam_group.exam.title }}</h5>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0 rounded">
                            <thead>
                                <tr>
                                    <td class="fw-bold">Mata Pelajaran</td>
                                    <td>{{ data.exam_group.exam.lesson.title }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Kelas</td>
                                    <td>{{ data.exam_group.student.classroom.title }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Sesi</td>
                                    <td>{{ data.exam_group.exam_session.title }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Mulai</td>
                                    <td>{{ data.exam_group.exam_session.start_time }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Selesai</td>
                                    <td>{{ data.exam_group.exam_session.end_time }}</td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    
                    <!-- cek waktu selesai -->
                    <div v-if="data.grade.end_time == null">

                        <!-- cek apakah ujian sudah dimulai, tapi waktu masih ada -->
                        <div v-if="examTimeRangeChecker(data.exam_group.exam_session.start_time, data.exam_group.exam_session.end_time)">

                            <div v-if="data.grade.start_time == null">
                                <Link :href="`/student/exam-confirmation/${data.exam_group.id}`" class="btn btn-md btn-success border-0 shadow w-100 mt-2 text-white">Kerjakan</Link>
                            </div>

                            <div v-else>
                                <Link :href="`/student/exam/${data.exam_group.id}/1`" class="btn btn-md btn-info border-0 shadow w-100 mt-2">Lanjut Kerjakan</Link>
                            </div>

                        </div>

                        <div v-else>

                            <!-- ujian belum mulai-->
                            <div v-if="examTimeStartChecker(data.exam_group.exam_session.start_time)">
                                <button class="btn btn-md btn-gray-700 border-0 shadow w-100 mt-2" disabled>Belum Mulai</button>
                            </div>

                            <!-- ujian terlewat -->
                            <div v-if="examTimeEndChecker(data.exam_group.exam_session.end_time)">
                                <button class="btn btn-md btn-danger border-0 shadow w-100 mt-2" disabled>Waktu Terlewat</button>
                            </div>

                        </div>

                    </div>

                    <div v-else>
                        <button class="btn btn-md btn-danger border-0 shadow w-100 mt-2" disabled>Sudah Dikerjakan</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row" v-else>
        <div class="col-md-12">
            <div class="alert alert-danger border-0 shadow">
                <i class="fa fa-info-circle"></i> Tidak ada ujian yang tersedia
            </div>
        </div>
    </div>

    <!-- Assignments Section -->
    <div class="row mt-4" v-if="assignments.length">
        <div class="col-md-12 mb-2"><h5 class="fw-bold">Tugas Harian</h5></div>
        <div class="col-md-6 mb-3" v-for="a in assignments" :key="a.id">
            <div class="card border-0 shadow h-100">
                <div class="card-body">
                    <h6 class="mb-1">{{ a.title }}</h6>
                    <small class="text-muted">{{ a.lesson?.title }} • {{ a.classroom?.title }}</small>
                    <p class="mt-2 mb-2 small" style="max-height:3.5em; overflow:hidden; white-space:pre-line;">{{ a.description }}</p>
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <span class="badge bg-info" v-if="a.due_at">Deadline: {{ a.due_at }}</span>
                        <span class="badge bg-success" v-else>Terbit</span>
                        <template v-if="!a.submission">
                            <Link :href="`/student/assignments/${a.id}/start`" method="post" as="button" class="btn btn-sm btn-primary">Mulai</Link>
                        </template>
                        <template v-else-if="!a.submission.finished_at">
                            <Link :href="`/student/assignments/${a.id}/start`" class="btn btn-sm btn-info">Lanjut</Link>
                            <Link :href="`/student/assignments/${a.id}/finish`" method="post" as="button" class="btn btn-sm btn-outline-danger" confirm="Selesai?">Selesai</Link>
                        </template>
                        <span v-else class="badge bg-secondary">Selesai ({{ a.submission.score }})</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" v-else>
        <div class="col-md-12"><div class="alert alert-light border-0 shadow-sm">Tidak ada tugas harian.</div></div>
    </div>

    <!-- Tryouts Section -->
    <div class="row mt-4" v-if="tryouts.length">
        <div class="col-md-12 mb-2"><h5 class="fw-bold">Tryout</h5></div>
        <div class="col-md-6 mb-3" v-for="t in tryouts" :key="t.id">
            <div class="card border-0 shadow h-100">
                <div class="card-body">
                    <h6 class="mb-1">{{ t.title }}</h6>
                    <small class="text-muted">{{ t.lesson?.title }} • {{ t.classroom?.title }}</small>
                    <p class="mt-2 mb-2 small" style="max-height:3.5em; overflow:hidden; white-space:pre-line;">{{ t.description }}</p>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <span class="badge bg-info" v-if="t.start_at">Mulai: {{ t.start_at }}</span>
                        <span class="badge bg-secondary" v-if="t.end_at">Selesai: {{ t.end_at }}</span>
                        <span class="badge bg-primary" v-if="t.duration_minutes">Durasi: {{ t.duration_minutes }}m</span>
                        <template v-if="!t.attempt">
                            <Link :href="`/student/tryouts/${t.id}/start`" method="post" as="button" class="btn btn-sm btn-primary ms-auto">Mulai</Link>
                        </template>
                        <template v-else-if="!t.attempt.finished_at">
                            <Link :href="`/student/tryouts/${t.id}/start`" class="btn btn-sm btn-info ms-auto">Lanjut</Link>
                            <Link :href="`/student/tryouts/${t.id}/finish`" method="post" as="button" class="btn btn-sm btn-outline-danger" confirm="Selesai?">Selesai</Link>
                        </template>
                        <span v-else class="badge bg-secondary ms-auto">Selesai ({{ t.attempt.score }})</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" v-else>
        <div class="col-md-12"><div class="alert alert-light border-0 shadow-sm">Tidak ada tryout.</div></div>
    </div>
</template>

<script>
    //import layout student
    import LayoutStudent from '../../../Layouts/Student.vue';

    //import Link from Inertia
    import {
        Link
    } from '@inertiajs/vue3';

    export default {

        //layout
        layout: LayoutStudent,

        //register components
        components: {
            Link,
        },

        //register props
        props: {
            exam_groups: Array,
            assignments: Array,
            tryouts: Array,
            auth: Object
        }

    }

</script>

<style>

</style>