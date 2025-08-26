<template>
  <Head>
    <title>Hasil Ujian - Aplikasi Ujian Online</title>
  </Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row">
      <div class="col-12">
        <div class="card border-0 shadow mb-4">
          <div class="card-body">
            <h5><i class="fa fa-filter"></i> Filter Hasil Ujian</h5>
            <hr />
            <form @submit.prevent="apply">
              <div class="row g-3 align-items-end">
                <div class="col-md-3">
                  <label class="form-label">Tipe</label>
                  <select v-model="form.type" class="form-select">
                    <option value="exam">Ujian</option>
                    <option value="assignment">Tugas Harian</option>
                    <option value="tryout">Tryout</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">{{ selectLabel }}</label>
                  <select v-if="form.type==='exam'" v-model="form.exam_id" class="form-select">
                    <option :value="null" disabled>Pilih Ujian</option>
                    <option v-for="e in exams" :key="e.id" :value="e.id">{{ e.title }} — Kelas: {{ e.classroom.title }} — Pelajaran: {{ e.lesson.title }}</option>
                  </select>
                  <select v-else-if="form.type==='assignment'" v-model="form.assignment_id" class="form-select">
                    <option :value="null" disabled>Pilih Tugas</option>
                    <option v-for="a in assignments" :key="a.id" :value="a.id">{{ a.title }} — Kelas: {{ a.classroom.title }} — Pelajaran: {{ a.lesson.title }}</option>
                  </select>
                  <select v-else v-model="form.tryout_id" class="form-select">
                    <option :value="null" disabled>Pilih Tryout</option>
                    <option v-for="t in tryouts" :key="t.id" :value="t.id">{{ t.title }} — Kelas: {{ t.classroom.title }} — Pelajaran: {{ t.lesson.title }}</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <button class="btn btn-primary w-100"><i class="fa fa-filter"></i> Terapkan</button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <div v-if="results.length" class="card border-0 shadow">
          <div class="card-body">
            <div class="row mb-2">
              <div class="col-md-9"><h5 class="mt-2"><i class="fa fa-list"></i> Daftar Hasil ({{ titleType }})</h5></div>
              <div class="col-md-3">
                <a :href="exportUrl" target="_blank" class="btn btn-success w-100 text-white"><i class="fa fa-file-excel"></i> Download Excel</a>
              </div>
            </div>
            <hr />
            <div class="table-responsive">
              <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                <thead class="thead-dark">
                  <tr>
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
                <tbody>
                  <tr v-for="(r,i) in results" :key="i">
                    <td class="text-center">{{ i+1 }}</td>
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
import LayoutAdmin from '../../../Layouts/Admin.vue';
import {Head, router} from '@inertiajs/vue3';
import {reactive, computed} from 'vue';
export default {
  layout: LayoutAdmin,
  components:{Head},
  props:{
    type:String,
    exams:Array,
    assignments:Array,
    tryouts:Array,
    results:Array,
    exam_id:[String,Number,null],
    assignment_id:[String,Number,null],
    tryout_id:[String,Number,null]
  },
  setup(props){
    const form = reactive({
      type: props.type || 'exam',
      exam_id: props.exam_id || null,
      assignment_id: props.assignment_id || null,
      tryout_id: props.tryout_id || null,
    });
    const apply = ()=>{
      router.get('/admin/results', {...form}, {preserveScroll:true, preserveState:true});
    };
    const exportUrl = computed(()=>{
      const base = '/admin/results/export?type='+form.type;
      if(form.type==='exam' && form.exam_id) return base+'&exam_id='+form.exam_id;
      if(form.type==='assignment' && form.assignment_id) return base+'&assignment_id='+form.assignment_id;
      if(form.type==='tryout' && form.tryout_id) return base+'&tryout_id='+form.tryout_id;
      return '#';
    });
    const titleType = computed(()=> form.type==='exam' ? 'Ujian' : form.type==='assignment' ? 'Tugas Harian' : 'Tryout');
    const selectLabel = computed(()=> titleType.value);
    return {form, apply, exportUrl, titleType, selectLabel};
  }
}
</script>

<style scoped>
</style>
