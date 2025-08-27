<template>
  <Head><title>Settings - Branding</title></Head>
  <div class="container-fluid py-4">
    <div class="mb-4">
      <h4 class="fw-semibold mb-1">Branding</h4>
      <div class="text-muted">Atur nama situs, nama CBT, dan logo sekolah</div>
    </div>
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <form @submit.prevent="submit" enctype="multipart/form-data">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nama Website</label>
              <input type="text" class="form-control" v-model="form.site_name" placeholder="cth: UJIAN ONLINE" />
            </div>
            <div class="col-md-6">
              <label class="form-label">Nama CBT</label>
              <input type="text" class="form-control" v-model="form.cbt_name" placeholder="cth: CBT AI" />
            </div>
            <div class="col-md-6">
              <label class="form-label">Logo Sekolah</label>
              <input ref="file" type="file" accept="image/*" class="form-control" @change="onFile" />
              <div class="form-text">PNG/JPG, maks 2MB</div>
            </div>
            <div class="col-md-6" v-if="preview || settings.school_logo">
              <label class="form-label">Pratinjau</label>
              <div class="p-2 border rounded bg-white d-inline-block">
                <img :src="preview || settings.school_logo" alt="logo" style="height:60px"/>
              </div>
              <div class="form-check mt-2" v-if="settings.school_logo && !preview">
                <input class="form-check-input" type="checkbox" v-model="form.remove_logo" id="removeLogoChk">
                <label class="form-check-label" for="removeLogoChk">Hapus logo</label>
              </div>
            </div>
          </div>
          <div class="mt-4 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
</template>

<script>
import LayoutAdmin from '../../../Layouts/Admin.vue'
import { Head, router } from '@inertiajs/vue3'
import { reactive, ref } from 'vue'

export default {
  layout: LayoutAdmin,
  components: { Head },
  props: { settings: Object },
  setup(props){
    const form = reactive({
      site_name: props.settings.site_name || '',
      cbt_name: props.settings.cbt_name || '',
      logo: null,
      remove_logo: false,
    })
    const preview = ref('')
    const file = ref(null)

    const onFile = (e) => {
      const f = e.target.files[0]
      form.logo = f
      if (f) preview.value = URL.createObjectURL(f)
    }

    const submit = () => {
      const data = new FormData()
      data.append('site_name', form.site_name)
      data.append('cbt_name', form.cbt_name)
  if (form.logo) data.append('logo', form.logo)
  if (form.remove_logo) data.append('remove_logo', '1')
      router.post('/admin/settings', data)
    }

    return { form, submit, onFile, file, preview }
  }
}
</script>

<style>
</style>