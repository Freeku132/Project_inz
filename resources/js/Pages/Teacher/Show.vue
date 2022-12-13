<template>

    <Head>
        <title> {{user.name}}</title>
    </Head>
    <div>
        <div class="flex flex-col md:flex-row md:justify-end">
            <div class="md:w-1/4 md:fixed left-1 z-40">
                <img
                    class="rounded-full h-56 w-56 mx-auto mt-12 border-b-4 border-l-4 border-default"
                    src="https://www.gravatar.com/avatar/3459098?s=200&d=mp"
                />
                <div class="justify-center items-center mt-2 flex flex-col space-y-6">
                    <p class="text-4xl font-bold m-4">
                        {{user.name}}
                    </p>

                    <!-- PHONE ICON -->
                    <!-- PHONE ICON -->
                    <!-- PHONE ICON -->
                    <InfoIcon :information="phoneNumber.Number"
                              :copyInfo="lang.get('teachersShow.number')"
                    >
                            <svg class="w-8 h-8" version="1.1" width="256" height="256" viewBox="0 0 256 256" xml:space="preserve">
                            <defs>
                            </defs>
                                <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)" >
                                    <path d="M 45 90 C 20.187 90 0 69.813 0 45 C 0 20.187 20.187 0 45 0 c 24.813 0 45 20.187 45 45 C 90 69.813 69.813 90 45 90 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(14,155,10); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                                    <path d="M 61.884 68.284 l 5.296 -2.671 c 2.519 -1.271 2.821 -4.747 0.559 -6.433 l -7.994 -5.959 c -1.206 -0.899 -2.828 -1.001 -4.137 -0.261 l -2.322 1.312 c -1.114 0.63 -2.478 0.671 -3.607 0.07 c -6.1 -3.247 -10.773 -7.921 -14.021 -14.021 c -0.601 -1.129 -0.56 -2.493 0.07 -3.607 l 1.312 -2.322 c 0.74 -1.309 0.637 -2.931 -0.261 -4.137 l -5.959 -7.994 c -1.686 -2.262 -5.163 -1.96 -6.433 0.559 l -2.671 5.296 c -1.248 2.475 -1.317 5.392 -0.146 7.904 c 6.872 14.734 17.676 25.538 32.41 32.41 C 56.492 69.602 59.409 69.533 61.884 68.284 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,255); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                                </g>
                            </svg>
                    </InfoIcon>



                    <!-- EMAIL ICON -->
                    <!-- EMAIL ICON -->
                    <!-- EMAIL ICON -->
                    <InfoIcon :information="user.email"
                              :copyInfo="lang.get('teachersShow.emailCopy')"
                    >
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                             viewBox="0 0 309.267 309.267" style="enable-background:new 0 0 309.267 309.267;" xml:space="preserve">
                            <g>
                                <path style="fill:#F4B459;" d="M28.994,256.112h251.279c16.014,0,28.994-12.989,28.994-28.994V82.149
                                    c0-16.014-12.98-28.994-28.994-28.994H28.994C12.989,53.155,0,66.135,0,82.149v144.969C0,243.123,12.989,256.112,28.994,256.112z"
                                />
                                <g>
                                    <path style="fill:#E8AB54;" d="M9.723,248.622l120.943-93.07L9.984,60.413C3.914,65.729,0,73.441,0,82.149v144.969
                                        C0,235.69,3.798,243.316,9.723,248.622z M299.486,60.578l-125.417,95.245l125.301,92.944c6.031-5.306,9.897-12.999,9.897-21.649
                                        V82.149C309.267,73.538,305.44,65.893,299.486,60.578z"/>
                                </g>
                                <path style="fill:#FADAAC;" d="M305.44,241.257L199.825,156.75l106.881-86.44c-2.378-5.267-6.253-9.665-11.105-12.69
                                    L159.079,166.83L18.865,55.069c-5.644,2.117-10.486,5.866-13.83,10.776l112.892,90.548L6.495,245.181
                                    c3.798,4.716,8.978,8.196,14.99,9.819l110.399-87.32l6.736,5.432c11.308,11.646,29.641,11.646,40.959,0l6.272-5.074l107.267,84.942
                                    C298.327,250.39,302.589,246.312,305.44,241.257z"/>
                            </g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
                    </InfoIcon>

                    <button v-if="can.createEvent" @click="create = !create" class="bg-page w-11/12 hover:bg-page2 p-2 rounded m-4">{{ lang.get('teachersShow.addEvents')}}</button>

                    <Link class="bg-page p-2 rounded m-4 no-underline w-11/12 text-default text-center hover:bg-page2" v-if="can.createEvent" :href="route('event-teacher.index', user.id)">
                        {{lang.get('teachersShow.eventsList')}}
                    </Link>
                </div>
            </div>

            <div class="border-l-2 border-default pl-2 mt-8 min-h-screen md:mt-0 font-semibold md:w-3/4">

                <div v-if="can.teacher" class="">

                    <div v-if="create">
                        <div class="z-50 fixed inset-0 w-full h-screen flex items-center justify-center bg-bg-semi-75">
                            <div class="md:w-2/3 flex flex-col bg-page rounded-xl m-4 p-4">
                                <div class="text-right">
                                    <button type="button" class="text-right rounded px-2 bg-red-500" @click="create = !create">x</button>
                                </div>
                                <FreeEventForm :lang="lang"/>
                            </div>
                        </div>
                    </div>

                <EventsCal :user="user" :events="events" :currentDate="currentDate" :lang="lang"/>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import Layout from '@/Shared/Layout.vue'

export default {
    layout: Layout
}
</script>

<script setup>
import InfoIcon from "@/Components/InfoIcon.vue";
import EventsCal from "@/Components/EventsCal.vue";
import FreeEventForm from "@/Components/FreeEventForm.vue"
import {ref} from "vue";
import {Link} from "@inertiajs/inertia-vue3";
import Lang from "lang.js";
import teacherShow from "../../../../lang/teachersShow.json";

let props = defineProps({
    user : Object,
    events: Object,
    currentDate: String,
    can: Object,
})

const phoneNumber = {
    Number: '999-998-997'
}
let create = ref(false);

let lang = ref(new Lang({
    messages: teacherShow
}));
let chosenLang = ref(localStorage.getItem('lang') || 'en');
lang.value.setLocale(chosenLang)

</script>

<style scoped>

</style>
