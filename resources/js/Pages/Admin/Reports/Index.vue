<template>
    <Head>
        <title>Laporan Nilai Ujian - Aplikasi Ujian Online</title>
    </Head>
    <div class="container-fluid mb-5 mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow mb-4">
                    <div class="card-body">
                        <h5><i class="fa fa-filter"></i> Filter Laporan Nilai</h5>
                        <hr>
                        <form @submit.prevent="filter">
                            
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Tipe</label>
                                    <select class="form-select" v-model="form.type">
                                        <option value="exam">Ujian</option>
                                        <option value="assignment">Tugas Harian</option>
                                        <option value="tryout">Tryout</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ selectLabel }}</label>
                                    <select v-if="form.type==='exam'" class="form-select" v-model="form.exam_id">
                                        <option v-for="(exam, index) in exams" :key="index" :value="exam.id">{{ exam.title }} — Kelas : {{ exam.classroom.title }} — Pelajaran : {{ exam.lesson.title }}</option>
                                    </select>
                                    <select v-else-if="form.type==='assignment'" class="form-select" v-model="form.assignment_id">
                                        <option v-for="(a, i) in assignments" :key="i" :value="a.id">{{ a.title }} — Kelas : {{ a.classroom.title }} — Pelajaran : {{ a.lesson.title }}</option>
                                    </select>
                                    <select v-else class="form-select" v-model="form.tryout_id">
                                        <option v-for="(t, i) in tryouts" :key="i" :value="t.id">{{ t.title }} — Kelas : {{ t.classroom.title }} — Pelajaran : {{ t.lesson.title }}</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label d-block">&nbsp;</label>
                                    <button type="submit" class="btn btn-md btn-primary border-0 shadow w-100"> <i class="fa fa-filter"></i> Filter</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

                <div v-if="results.length > 0" class="card border-0 shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9 col-12">
                                <h5 class="mt-2"><i class="fa fa-chart-line"></i> Laporan Nilai {{ titleType }}</h5>
                            </div>
                            <div class="col-md-3 col-12">
                                <a :href="exportUrl" target="_blank" class="btn btn-success btn-md border-0 shadow w-100 text-white" :class="{disabled: !canExport}"><i class="fa fa-file-excel"></i> DOWNLOAD EXCEL</a>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                                <thead class="thead-dark">
                                    <tr class="border-0">
                                        <th style="width:5%">No.</th>
                                        <th v-if="form.type==='exam'">Ujian</th>
                                        <th v-else-if="form.type==='assignment'">Tugas</th>
                                        <th v-else>Tryout</th>
                                        <th v-if="form.type==='exam'">Sesi</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Pelajaran</th>
                                        <th v-if="form.type!=='exam'">Benar</th>
                                        <th v-if="form.type!=='exam'">Total Soal</th>
                                        <th>Nilai</th>
                                    </tr>
                                </thead>
                                <div class="mt-2"></div>
                                <tbody>
                                    <tr v-for="(r, index) in results" :key="index">
                                        <td class="text-center">{{ index + 1 }}</td>
                                        <td v-if="form.type==='exam'">{{ r.exam?.title || '-' }}</td>
                                        <td v-else-if="form.type==='assignment'">{{ r.assignment?.title || '-' }}</td>
                                        <td v-else>{{ r.tryout?.title || '-' }}</td>
                                        <td v-if="form.type==='exam'">{{ r.exam_session ? r.exam_session.title : '-' }}</td>
                                        <td>{{ r.student?.name || '-' }}</td>
                                        <td class="text-center">{{ form.type==='exam'? (r.exam?.classroom?.title || '-') : form.type==='assignment'? (r.assignment?.classroom?.title || '-') : (r.tryout?.classroom?.title || '-') }}</td>
                                        <td>{{ form.type==='exam'? (r.exam?.lesson?.title || '-') : form.type==='assignment'? (r.assignment?.lesson?.title || '-') : (r.tryout?.lesson?.title || '-') }}</td>
                                        <td v-if="form.type!=='exam'" class="text-center">{{ r.total_correct ?? '-' }}</td>
                                        <td v-if="form.type!=='exam'" class="text-center">{{ r.total_questions ?? '-' }}</td>
                                        <td class="fw-bold text-center">{{ form.type==='exam'? (r.grade ?? '-') : (r.score ?? '-') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    //import layout Admin
    import LayoutAdmin from '../../../Layouts/Admin.vue';

    //import Head from Inertia
    import {
        Head,
        router
    } from '@inertiajs/vue3';

    //import reactive from vue
    import { reactive, computed } from 'vue';

    export default {

        //layout
        layout: LayoutAdmin,

        //register components
        components: {
            Head,
        },

        //props
        props: {
            errors: Object,
            exams: Array,
            assignments: Array,
            tryouts: Array,
            results: Array,
            type: String,
            exam_id: [String, Number, null],
            assignment_id: [String, Number, null],
            tryout_id: [String, Number, null],
        },

        //inisialisasi composition API
        setup() {

            //define state
            const url = new URL(document.location);
            const form = reactive({
                type: url.searchParams.get('type') || (window.initialType || 'exam'),
                exam_id: url.searchParams.get('exam_id') || null,
                assignment_id: url.searchParams.get('assignment_id') || null,
                tryout_id: url.searchParams.get('tryout_id') || null,
            });

             //define methods filter
            const filter = () => {

                //HTTP request
                router.get('/admin/reports/filter', {...form});

            }

            const exportUrl = computed(()=>{
                const base = '/admin/reports/export?type='+form.type;
                if(form.type==='exam' && form.exam_id) return base+'&exam_id='+form.exam_id;
                if(form.type==='assignment' && form.assignment_id) return base+'&assignment_id='+form.assignment_id;
                if(form.type==='tryout' && form.tryout_id) return base+'&tryout_id='+form.tryout_id;
                return '#';
            });
            const titleType = computed(()=> form.type==='exam'? 'Ujian': form.type==='assignment'? 'Tugas Harian': 'Tryout');
            const selectLabel = computed(()=> titleType.value);
            const canExport = computed(()=> (form.type==='exam' && form.exam_id) || (form.type==='assignment' && form.assignment_id) || (form.type==='tryout' && form.tryout_id));

            //return
            return {
                form,
                filter,
                exportUrl,
                titleType,
                selectLabel,
                canExport
            }

        }

    }

</script>

<style>

</style>