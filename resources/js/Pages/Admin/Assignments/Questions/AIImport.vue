<template>
  <Head>
    <title>AI Import Soal Tugas - Aplikasi Ujian Online</title>
  </Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row">
      <div class="col-md-12">
        <Link :href="`/admin/assignments/${assignment.id}/questions`" class="btn btn-md btn-primary border-0 shadow mb-3" type="button"><i class="fa fa-long-arrow-alt-left me-2"></i> Kembali</Link>
        <div class="card border-0 shadow">
          <div class="card-body">
            <h5><i class="fa fa-robot"></i> Import Soal Otomatis (AI)</h5>
            <hr>
            <div v-if="errorMsg" class="alert alert-danger">{{ errorMsg }}</div>
            <form v-if="!items.length" @submit.prevent="generate">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Kategori / Topik Soal</label>
                  <input v-model="form.category" type="text" class="form-control" placeholder="mis. Pecahan kelas 5, Sistem Pencernaan" required />
                </div>
                <div class="col-md-3">
                  <label class="form-label">Jumlah Soal</label>
                  <input v-model.number="form.count" type="number" min="1" max="50" class="form-control" />
                </div>
                <div class="col-md-3">
                  <label class="form-label">Tingkat (opsional)</label>
                  <select v-model="form.difficulty" class="form-select">
                    <option value="">(Tidak ditentukan)</option>
                    <option value="mudah">Mudah</option>
                    <option value="sedang">Sedang</option>
                    <option value="sulit">Sulit</option>
                  </select>
                </div>
              </div>
              <div class="mt-4">
                <button class="btn btn-md btn-primary border-0 shadow" :disabled="loading" type="submit">
                  <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                  Generate dari AI
                </button>
              </div>
            </form>

            <div v-else>
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div><strong>Editor Soal (AI/Manual)</strong> — Total: {{ items.length }}</div>
                <div class="btn-group">
                  <button class="btn btn-secondary btn-sm" :disabled="page===1" @click="prev">Prev</button>
                  <span class="btn btn-outline-secondary btn-sm disabled">Hal {{ page }} / {{ totalPages }}</span>
                  <button class="btn btn-secondary btn-sm" :disabled="page===totalPages" @click="next">Next</button>
                </div>
              </div>

              <div v-for="(it, idx) in paged" :key="idx" class="mb-4 p-3 border rounded">
                <div class="form-check form-switch mb-2">
                  <input class="form-check-input" type="checkbox" v-model="it.include" />
                  <label class="form-check-label">Sertakan saat simpan</label>
                </div>
                <label class="form-label fw-semibold">Pertanyaan</label>
                <textarea class="form-control mb-2" rows="3" v-model="it.question"></textarea>
                <div class="row g-2 align-items-end">
                  <div class="col-md-6" v-for="(opt, oi) in it.options" :key="oi">
                    <label class="form-label">Opsi {{ label(oi+1) }}</label>
                    <input class="form-control" v-model="it.options[oi]" />
                  </div>
                </div>
                <div class="mt-2">
                  <label class="form-label">Jawaban Benar</label>
                  <select v-model.number="it.answer" class="form-select w-auto d-inline-block ms-2">
                    <option v-for="n in 5" :key="n" :value="n">{{ label(n) }}</option>
                  </select>
                  <button class="btn btn-outline-danger btn-sm ms-2" @click="removeItem((page-1)*pageSize + idx)">Hapus</button>
                </div>
              </div>

              <div class="mt-2 mb-3">
                <button class="btn btn-outline-primary btn-sm" @click="addItem">+ Tambah Soal Kosong</button>
              </div>

              <div class="mt-3">
                <button class="btn btn-success" :disabled="saving" @click="confirmImport">
                  <span v-if="saving" class="spinner-border spinner-border-sm me-2"></span>
                  Simpan Soal Tugas
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import AdminLayout from '../../../../Layouts/Admin.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive, ref, computed, watch } from 'vue';
import Swal from 'sweetalert2';

export default {
  layout: AdminLayout,
  components: { Head, Link },
  props: { assignment: Object, preview: Array, form: Object, error: String },
  setup(props){
    const form = reactive({ category: props.form?.category||'', count: props.form?.count||5, difficulty: props.form?.difficulty||'' });
    const loading = ref(false);
    const items = ref((props.preview||[]).map(n=>({ include:true, options:n.options||['','','','',''], answer:n.answer||1, ...n })));
    const errorMsg = ref(props.error||'');
    watch(()=>props.preview, val=>{ items.value=(val||[]).map(n=>({ include:true, options:n.options||['','','','',''], answer:n.answer||1, ...n })); page.value=1; });
    watch(()=>props.error, v=> errorMsg.value=v||'');
    const page = ref(1); const pageSize=5; const totalPages = computed(()=> Math.max(1, Math.ceil(items.value.length / pageSize))); const paged = computed(()=> items.value.slice((page.value-1)*pageSize, page.value*pageSize));
    const prev=()=>{ if(page.value>1) page.value--; }; const next=()=>{ if(page.value<totalPages.value) page.value++; };
    const generate=()=>{ loading.value=true; router.post(`/admin/assignments/${props.assignment.id}/questions/ai-import/generate`, form, { onSuccess: (pageResp)=> { loading.value=false; const err=pageResp?.props?.error; if(err){ errorMsg.value=err; Swal.fire({ title:'Gagal', text:err, icon:'error', timer:2500, showConfirmButton:false }); } else { Swal.fire({ title:'Berhasil', text:'Soal berhasil digenerate. Silakan edit sebelum simpan.', icon:'success', timer:1800, showConfirmButton:false }); } }, onError:()=> { loading.value=false; errorMsg.value='Terjadi kesalahan saat generate.'; Swal.fire({ title:'Gagal', text:'Terjadi kesalahan saat generate.', icon:'error' }); }, onFinish:()=> loading.value=false }); };
    const saving=ref(false);
    const confirmImport=()=>{ const cleaned = items.value.map(it=>({ include: !!it.include, question:(it.question||'').trim(), options:Array.isArray(it.options)? it.options.map(o=>(o||'').trim()).slice(0,5):[], answer:Number(it.answer)||1 })); saving.value=true; router.post(`/admin/assignments/${props.assignment.id}/questions/ai-import/confirm`, { items: cleaned }, { preserveScroll:true, onSuccess:()=>{ saving.value=false; Swal.fire({ title:'Berhasil', text:'Soal disimpan.', icon:'success', timer:2000, showConfirmButton:false }); }, onError:()=>{ saving.value=false; Swal.fire({ title:'Gagal', text:'Gagal menyimpan. Cek isian.', icon:'error' }); }, onFinish:()=> saving.value=false }); };
    const label = (n)=> ['A','B','C','D','E'][n-1] || n;
    const addItem=()=> { items.value.push({ include:true, question:'', options:['','','','',''], answer:1 }); page.value=totalPages.value; };
    const removeItem=(i)=> { items.value.splice(i,1); if(page.value>totalPages.value) page.value=totalPages.value; };
    return { form, loading, generate, items, page, paged, totalPages, prev, next, saving, confirmImport, label, addItem, removeItem, errorMsg, pageSize };
  }
}
</script>
<style></style>
