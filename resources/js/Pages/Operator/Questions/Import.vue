<template>
  <Head>
    <title>Import Soal Ujian - CBT AI</title>
  </Head>
  <OperatorLayout>
    <div class="container-fluid mb-5 mt-4">
      <div class="row">
        <div class="col-md-12">
          <Link :href="`/operator/exams/${exam.id}`" class="btn btn-md btn-primary border-0 shadow mb-3 me-3" type="button">
            <i class="fa fa-long-arrow-alt-left me-2"></i> Kembali
          </Link>
          <a href="/assets/excel/questions.xls" target="_blank" class="btn btn-md btn-success border-0 shadow mb-3 text-white" type="button">
            <i class="fa fa-file-excel me-2"></i> Contoh Format
          </a>
          <div class="card border-0 shadow">
            <div class="card-body">
              <h5><i class="fa fa-question-circle"></i> Import Soal</h5>
              <hr />
              <form @submit.prevent="submit">
                <div class="mb-4">
                  <label>File Excel</label>
                  <input type="file" class="form-control" @input="form.file = $event.target.files[0]" />
                  <div v-if="errors?.file" class="alert alert-danger mt-2">{{ errors.file }}</div>
                  <div v-if="errors?.[0]" class="alert alert-danger mt-2">{{ errors[0] }}</div>
                </div>
                <button type="submit" class="btn btn-md btn-primary border-0 shadow me-2">Upload</button>
                <button type="reset" class="btn btn-md btn-warning border-0 shadow">Reset</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </OperatorLayout>
  
</template>
<script>
import OperatorLayout from '../../../Layouts/Operator.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { reactive } from 'vue'
export default {
  components: { OperatorLayout, Head, Link },
  props: { exam: Object, errors: Object },
  setup(props){
    const form = reactive({ file: '' })
    const submit = () => {
      router.post(`/operator/exams/${props.exam.id}/questions/import`, { file: form.file }, { forceFormData: true })
    }
    return { form, submit }
  }
}
</script>

<style></style>
