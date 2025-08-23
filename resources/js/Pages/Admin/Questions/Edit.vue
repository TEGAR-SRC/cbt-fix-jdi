<template>
    <Head>
        <title>Edit Soal Ujian - Aplikasi Ujian Online</title>
    </Head>
    <div class="container-fluid mb-5 mt-5">
        <div class="row">
            <div class="col-md-12">
                <Link :href="`/admin/exams/${exam.id}`" class="btn btn-md btn-primary border-0 shadow mb-3" type="button"><i class="fa fa-long-arrow-alt-left me-2"></i> Kembali</Link>
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h5><i class="fa fa-question-circle"></i> Edit Soal Ujian</h5>
                        <hr>
                        <form @submit.prevent="submit">

                            <div class="table-responsive mb-4">
                                <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                                    <tbody>
                                        <tr>
                                            <td style="width:20%" class="fw-bold">Soal</td>
                                            <td>
                                                <Editor 
                                                    :api-key="TinyMCEApiKey" 
                                                    v-model="form.question" 
                                                    :init="{
                                                        menubar: false,
                                                        plugins: 'lists link image emoticons',
                                                        toolbar: 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons'
                                                    }"
                                                />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:20%" class="fw-bold">Gambar (opsional)</td>
                                            <td>
                                                <input type="file" class="form-control" accept="image/*" @change="onImageChange" />
                                                <div class="mt-2" v-if="imagePreview || question.image_path">
                                                    <img :src="imagePreview || (`/storage/${question.image_path}`)" alt="Preview" style="max-height: 160px;" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:20%" class="fw-bold">Audio (opsional)</td>
                                            <td>
                                                <input type="file" class="form-control" accept="audio/*" @change="onAudioChange" />
                                                <div class="mt-2" v-if="audioPreview || question.audio_path">
                                                    <audio :src="audioPreview || (`/storage/${question.audio_path}`)" controls></audio>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:20%" class="fw-bold">Video (opsional)</td>
                                            <td>
                                                <input type="file" class="form-control" accept="video/*" @change="onVideoChange" />
                                                <div class="mt-2" v-if="videoPreview || question.video_path">
                                                    <video :src="videoPreview || (`/storage/${question.video_path}`)" controls style="max-width: 100%; max-height: 260px;"></video>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:20%" class="fw-bold">Pilihan A</td>
                                            <td>
                                                <Editor 
                                                    :api-key="TinyMCEApiKey" 
                                                    v-model="form.option_1" 
                                                    :init="{
                                                        height: 130,
                                                        menubar: false,
                                                        plugins: 'lists link image emoticons',
                                                        toolbar: 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons'
                                                    }"
                                                />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:20%" class="fw-bold">Pilihan B</td>
                                            <td>
                                                <Editor 
                                                    :api-key="TinyMCEApiKey" 
                                                    v-model="form.option_2" 
                                                    :init="{
                                                        height: 130,
                                                        menubar: false,
                                                        plugins: 'lists link image emoticons',
                                                        toolbar: 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons'
                                                    }"
                                                />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:20%" class="fw-bold">Pilihan C</td>
                                            <td>
                                                <Editor 
                                                    :api-key="TinyMCEApiKey" 
                                                    v-model="form.option_3" 
                                                    :init="{
                                                        height: 130,
                                                        menubar: false,
                                                        plugins: 'lists link image emoticons',
                                                        toolbar: 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons'
                                                    }"
                                                />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:20%" class="fw-bold">Pilihan D</td>
                                            <td>
                                                <Editor 
                                                    :api-key="TinyMCEApiKey" 
                                                    v-model="form.option_4" 
                                                    :init="{
                                                        height: 130,
                                                        menubar: false,
                                                        plugins: 'lists link image emoticons',
                                                        toolbar: 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons'
                                                    }"
                                                />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:20%" class="fw-bold">Pilihan E</td>
                                            <td>
                                                <Editor 
                                                    :api-key="TinyMCEApiKey"  
                                                    v-model="form.option_5" 
                                                    :init="{
                                                        height: 130,
                                                        menubar: false,
                                                        plugins: 'lists link image emoticons',
                                                        toolbar: 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons'
                                                    }"
                                                />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:20%" class="fw-bold">Jawaban Benar</td>
                                            <td>
                                                <select class="form-control" v-model="form.answer">
                                                    <option value="1">A</option>
                                                    <option value="2">B</option>
                                                    <option value="3">C</option>
                                                    <option value="4">D</option>
                                                    <option value="5">E</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <button type="submit" class="btn btn-md btn-primary border-0 shadow me-2">Simpan</button>
                            <button type="reset" class="btn btn-md btn-warning border-0 shadow">Reset</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    //import layout
    import LayoutAdmin from '../../../Layouts/Admin.vue';

    //import Heade and Link from Inertia
    import {
        Head,
        Link,
        router
    } from '@inertiajs/vue3';

    //import reactive from vue
    import { reactive, ref } from 'vue';

    //import sweet alert2
    import Swal from 'sweetalert2';

    //import tinyMCE
    import Editor from '@tinymce/tinymce-vue';

    export default {

        //layout
        layout: LayoutAdmin,

        //register components
        components: {
            Head,
            Link,
            Editor,
        },

        //props
        props: {
            errors: Object,
            exam: Object,
            question: Object,
            TinyMCEApiKey: String,
        },

        //inisialisasi composition API
        setup(props) {

            //define form with reactive
            const form = reactive({
                question: props.question.question,
                image: null,
                audio: null,
                video: null,
                option_1: props.question.option_1,
                option_2: props.question.option_2,
                option_3: props.question.option_3,
                option_4: props.question.option_4,
                option_5: props.question.option_5,
                answer: props.question.answer,
            });

            const imagePreview = ref(null);
            const audioPreview = ref(null);
            const videoPreview = ref(null);

            const onImageChange = (e) => {
                const file = e.target.files[0];
                form.image = file || null;
                imagePreview.value = file ? URL.createObjectURL(file) : null;
            };

            const onAudioChange = (e) => {
                const file = e.target.files[0];
                form.audio = file || null;
                audioPreview.value = file ? URL.createObjectURL(file) : null;
            };

            const onVideoChange = (e) => {
                const file = e.target.files[0];
                form.video = file || null;
                videoPreview.value = file ? URL.createObjectURL(file) : null;
            };

            //method "submit"
            const submit = () => {

                //send data to server
                const fd = new FormData();
                fd.append('_method', 'PUT');
                fd.append('question', form.question);
                if (form.image) fd.append('image', form.image);
                if (form.audio) fd.append('audio', form.audio);
                if (form.video) fd.append('video', form.video);
                fd.append('option_1', form.option_1);
                fd.append('option_2', form.option_2);
                fd.append('option_3', form.option_3);
                fd.append('option_4', form.option_4);
                fd.append('option_5', form.option_5);
                fd.append('answer', form.answer);

                router.post(`/admin/exams/${props.exam.id}/questions/${props.question.id}/update`, fd, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Soal Ujian Berhasil Dipdate!.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    },
                });

            }

            //return
            return {
                form,
                imagePreview,
                audioPreview,
                videoPreview,
                onImageChange,
                onAudioChange,
                onVideoChange,
                submit,
            }

        }

    }

</script>

<style>

</style>