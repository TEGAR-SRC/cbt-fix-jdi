<template>
  <Head><title>Edit Soal Tryout</title></Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row"><div class="col-md-12">
      <Link :href="`/admin/tryouts/${tryout.id}/questions`" class="btn btn-md btn-primary border-0 shadow mb-3" type="button"><i class="fa fa-long-arrow-alt-left me-2"></i> Kembali</Link>
      <div class="card border-0 shadow"><div class="card-body">
        <h5><i class="fa fa-pencil-alt"></i> Edit Soal Tryout</h5><hr>
        <form @submit.prevent="submit">
          <div class="mb-3">
            <label class="form-label">Pertanyaan</label>
            <textarea class="form-control" rows="3" v-model="form.question"></textarea>
            <div v-if="errors.question" class="text-danger small">{{ errors.question }}</div>
          </div>
          <div class="row">
            <div class="col-md-6" v-for="n in 5" :key="n">
              <div class="mb-3">
                <label>Opsi {{ String.fromCharCode(64+n) }}</label>
                <input class="form-control" v-model="form[`option_${n}`]" />
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label>Jawaban Benar</label>
            <select class="form-select w-auto" v-model="form.answer">
              <option value="">(Kosong)</option>
              <option v-for="n in 5" :key="n" :value="String(n)">Opsi {{ String.fromCharCode(64+n) }}</option>
            </select>
          </div>
          <div class="mb-3">
            <label>Urutan (opsional)</label>
            <input type="number" class="form-control w-auto" v-model.number="form.order" min="0" />
          </div>
          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary" :disabled="processing">Update</button>
          </div>
        </form>
      </div></div>
    </div></div>
  </div>
</template>
<script>
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';
import AdminLayout from '../../../../Layouts/Admin.vue';
export default { layout: AdminLayout, components:{Head,Link}, props:{ tryout:Object, question:Object, errors:Object }, setup(props){
  const form = reactive({ question:props.question.question||'', option_1:props.question.option_1||'', option_2:props.question.option_2||'', option_3:props.question.option_3||'', option_4:props.question.option_4||'', option_5:props.question.option_5||'', answer:props.question.answer||'', order:props.question.order||0 });
  const processing = ref(false);
  const submit=()=>{ processing.value=true; router.post(`/admin/tryouts/${props.tryout.id}/questions/${props.question.id}`, { ...form, _method:'PUT' }, { onFinish:()=>processing.value=false }); };
  return { form, submit, processing };
}}
</script>
<style></style>
