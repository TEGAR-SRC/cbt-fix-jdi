<template>
    <Head>
        <title>Monitoring Ujian - Aplikasi Ujian Online</title>
    </Head>
    <div class="container-fluid mb-5 mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow mb-3">
                    <div class="card-body">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label">Sesi Ujian</label>
                                <select v-model="selectedSession" class="form-select">
                                    <option :value="''">Semua Sesi</option>
                                    <option v-for="s in sessions" :key="s.id" :value="s.id">{{ s.title }} — {{ s.exam?.lesson?.title }} / {{ s.exam?.title }}</option>
                                </select>
                            </div>
                            <div class="col-md-8 text-end">
                                <div class="d-inline-flex gap-2">
                                    <span class="badge bg-warning text-dark p-2">Belum Mulai: {{ stats.not_started }}</span>
                                    <span class="badge bg-info p-2">Sedang Ujian: {{ stats.in_progress }}</span>
                                    <span class="badge bg-success p-2">Selesai: {{ stats.finished }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                                <thead class="thead-dark">
                                <tr class="border-0">
                                    <th class="border-0 rounded-start">Siswa</th>
                                    <th class="border-0">Kelas</th>
                                    <th class="border-0">Mapel/Ujian</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0">Soal Aktif</th>
                                    <th class="border-0">Terakhir Aktivitas</th>
                                    <th class="border-0 rounded-end">Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="g in grades.data" :key="g.id">
                                    <td>{{ g.student?.name }}</td>
                                    <td>{{ g.student?.classroom?.title }}</td>
                                    <td>{{ g.exam?.lesson?.title }} / {{ g.exam?.title }}</td>
                                    <td>
                                        <span v-if="!g.start_time" class="badge bg-warning text-dark">Belum Mulai</span>
                                        <span v-else-if="g.start_time && !g.end_time" class="badge bg-info">Sedang Ujian</span>
                                        <span v-else class="badge bg-success">Selesai</span>
                                    </td>
                                    <td>{{ g.current_question ?? '-' }}</td>
                                    <td>{{ formatTime(g.last_activity_at) }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-secondary" @click="monitorDetail(g.id)">Monitor Detail</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <Pagination :links="grades.links" align="end" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LayoutAdmin from '../../../Layouts/Admin.vue';
import Pagination from '../../../Components/Pagination.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch, onMounted, onUnmounted } from 'vue';

export default {
    layout: LayoutAdmin,
    components: { Head, Link, Pagination },
    props: {
        sessions: Array,
        grades: Object,
        stats: Object,
        filters: Object,
    },
    setup(props) {
        const selectedSession = ref(props.filters?.exam_session_id || '');

        const applyFilter = () => {
            if (document.hidden) return; // don't refresh if tab not visible
            router.get('/admin/monitor', {
                exam_session_id: selectedSession.value || undefined,
            }, { preserveState: true, preserveScroll: true, replace: true });
        };

        const reloadData = () => {
            if (document.hidden) return;
            router.reload({ only: ['grades','stats'], preserveScroll: true });
        };

        watch(selectedSession, applyFilter);

    // auto-refresh every 2s
        let timer = null;
        onMounted(() => {
            timer = setInterval(() => reloadData(), 2000);
        });
        onUnmounted(() => {
            if (timer) clearInterval(timer);
        });

    const formatTime = (val) => val ? new Date(val).toLocaleString() : '-';

    const stopAutoRefresh = () => { if (timer) clearInterval(timer); };
    const monitorDetail = (id) => { stopAutoRefresh(); router.get(`/admin/monitor/${id}`); };

    return { selectedSession, formatTime, stopAutoRefresh, monitorDetail, router };
    }
}
</script>

<style>
</style>
