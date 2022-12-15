<template>

    <Head>
        <title>Admin Panel</title>
    </Head>
    <div class="flex md:flex-row flex-col min-h-screen">
        <SideBar/>
        <div class="border-l-2 w-full border-default">
            <div>
                <div class="m-5 flex flex-col rounded-xl bg-page2">
                    <div class="flex flex-col bg-page rounded-xl m-4 p-4">
                        <button
                            @click="showForm = true"
                            class="text-right mx-auto mr-2 bg-page2 px-3 py-2 rounded-md text-default hover:bg-page3 hover:text-default2">
                            {{lang.get('adminPanelSemester.set_new_semester')}}</button>

                        <label>{{lang.get('adminPanelSemester.start_date')}}</label>
                        <input v-model="props.startDate" type="date" class="rounded-md bg-page" disabled>

                        <label>{{lang.get('adminPanelSemester.end_date')}}</label>
                        <input v-model="props.endDate" type="date" class="rounded-md bg-page" disabled>
                    </div>

                    <div v-if="showForm">
                        <div class="z-50 fixed inset-0 w-full h-screen flex items-center justify-center bg-bg-semi-75">
                            <div class="md:w-2/3 flex flex-col bg-page3 rounded-xl m-4 p-4">
                                <div class="text-right">
                                    <button type="button" class="text-right rounded px-2 bg-red-500" @click="showForm = false">x</button>
                                </div>
                                <div class="flex flex-col bg-page rounded-xl m-4 p-4">
                                    <label>{{lang.get('adminPanelSemester.semester_years')}}</label>
                                    <input v-model="semesterForm.years" type="text" class="rounded-md bg-page">

                                    <label>{{lang.get('adminPanelSemester.start_date')}}</label>
                                    <input v-model="semesterForm.startDate" type="date" class="rounded-md bg-page">

                                    <label>{{lang.get('adminPanelSemester.end_date')}}</label>
                                    <input v-model="semesterForm.endDate" type="date" class="rounded-md bg-page">

                                    <button
                                        @click="setNewSemester()"
                                        class="text-right mx-auto mr-2 mt-2 bg-page2 px-3 py-2 rounded-md text-default hover:bg-page3 hover:text-default2">
                                        {{lang.get('adminPanelSemester.set')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 bg-page rounded-xl m-4 p-4">
                        <div v-for="week in weeks">
                            <WeekSymbolForm :week="week.startDate" :number="week.week_number" :designation="week.designation" :lang="lang"/>
                        </div>
                    </div>



                    <FreeDays :free-days="freeDays" :lang="lang"/>


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
import SideBar from "@/Components/SideBar.vue"
import {useForm} from "@inertiajs/inertia-vue3";
import WeekSymbolForm from "@/Components/WeekSymbolForm.vue";
import {ref} from "vue";
import FreeDays from "@/Components/FreeDays.vue";
import Lang from "lang.js";
import adminPanelSemester from "../../../../lang/adminPanelSemester.json";

let props = defineProps({
    startDate: String,
    endDate: String,
    weeks: Object,
    freeDays: Object
})

let lang = ref(new Lang({
    messages: adminPanelSemester
}));
let chosenLang = ref(localStorage.getItem('lang') || 'en');
lang.value.setLocale(chosenLang)

let showForm = ref(false);

let semesterForm = useForm({
    years: '',
    startDate: '',
    endDate: '',
})

let setNewSemester = () => {
    if (semesterForm.startDate != null && semesterForm.endDate != null) {
        semesterForm.submit('post', route('adminPanel.semester.store'), {

        })
    }
}


</script>

<style scoped>

</style>
