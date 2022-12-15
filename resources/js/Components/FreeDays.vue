<template>
    <div class="flex flex-col bg-page rounded-xl m-4 p-4 space-y-1">

        Free days:

        <div v-for="day in freeDays" class="flex flex-row space-x-2">
            <div>{{day.date}} </div>
            <button @click="deleteDay(day)" type="button" class="text-right rounded px-2 bg-red-500">{{lang.get('adminPanelSemester.delete')}}</button>
        </div>
        <div>
            <input v-model="freeDayForm.date" type="date" class="md:w-1/6 bg-page rounded-md">
            <button @click="addFreeDay" type="button" class="bg-page2 px-3 py-2 rounded-md text-default hover:bg-page3 hover:text-default2 ml-4" :disabled="freeDayForm.processing">{{lang.get('adminPanelSemester.add')}}</button>
        </div>
        <div class=" font-semibold text-red-500" v-if="freeDayForm.errors.date">{{lang.get('adminPanelSemester.add_error')}}</div>


    </div>

    <div v-show="showDeleteDay">
        <div class="z-50 fixed inset-0 w-full h-screen flex items-center justify-center bg-bg-semi-75">
            <div class="md:w-1/3 flex flex-col bg-page3 rounded-xl m-2 p-2">
                <div class="flex flex-col bg-page rounded-xl m-2 p-2">
                    <div class="text-xl text-center">
                        {{lang.get('adminPanelSemester.delete_info')}}
                        <p class="font-bold">
                            {{deleteDayForm.day.date}}
                        </p>
                    </div>
                </div>

                <div class="flex flex-row justify-between mx-auto w-3/4 md:w-1/2">
                    <button type="button" class="text-right rounded px-2 bg-red-500" @click="acceptDelete">{{lang.get('adminPanelSemester.delete')}}</button>
                    <button type="button" class="text-right rounded px-2 bg-page" @click="showDeleteDay = false">{{lang.get('adminPanelSemester.cancel')}}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

import {useForm} from "@inertiajs/inertia-vue3";
import {ref} from "vue";

let props = defineProps({
    freeDays : Object,
    lang: Object
})

let freeDayForm = useForm({
    'date' : '',
})

let addFreeDay = () =>{
    if(freeDayForm != null) {
        freeDayForm.submit('post', route('adminPanel.semester.freedays'), {
            preserveScroll: true,
            preserveState: true,
        })
    }
}

let showDeleteDay = ref(false);

let deleteDayForm = useForm({
    day: Object
})

let deleteDay = (day) => {
    showDeleteDay.value = true;
    deleteDayForm.day = day
}

let acceptDelete = () => {
    deleteDayForm.submit('delete', route('adminPanel.semester.freedays.delete', deleteDayForm.day), {
        preserveState: true,
        preserveScroll: true,
    })
    showDeleteDay.value = false;
}

</script>

<style scoped>

</style>
