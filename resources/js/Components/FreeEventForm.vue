<template>
    <form @submit.prevent="createEvent">
        <p class="mx-8 uppercase text-xl">{{ lang.get('freeForm.addNew') }}</p>
        <div class="flex flex-col bg-page2 rounded-xl m-4 p-4" >
            <label>{{ lang.get('freeForm.day') }}</label>
            <select v-model="form.day" class="bg-page text-default" required>
                <option value="1">{{ lang.get('freeForm.monday') }}</option>
                <option value="2">{{ lang.get('freeForm.tuesday') }}</option>
                <option value="3">{{ lang.get('freeForm.wednesday') }}</option>
                <option value="4">{{ lang.get('freeForm.thursday') }}</option>
                <option value="5">{{ lang.get('freeForm.friday') }}</option>
                <option value="6">{{ lang.get('freeForm.saturday') }}</option>
                <option value="7">{{ lang.get('freeForm.sunday') }}</option>
            </select>
            <div class=" font-semibold text-red-500" v-if="form.errors.day">{{ lang.get('errors.day') }}</div>

            <label>{{ lang.get('freeForm.startTime') }}</label>
            <input type="time" v-model="form.startTime" class="bg-page text-default" required>
            <div class=" font-semibold text-red-500" v-if="form.errors.startTime">{{ lang.get('errors.startNew') }}</div>

            <label>{{ lang.get('freeForm.endTime') }}</label>
            <input type="time" v-model="form.endTime" class="bg-page text-default" required>
            <div class=" font-semibold text-red-500" v-if="form.errors.endTime">{{ lang.get('errors.endNew') }}</div>

            <label>{{ lang.get('freeForm.week') }}</label>
            <select v-model="form.week" class="bg-page text-default" required>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="A/B">A/B</option>
            </select>
            <div class=" font-semibold text-red-500" v-if="form.errors.week">{{ lang.get('errors.week') }}</div>

            <label>{{ props.lang.get('freeForm.room') }}</label>
            <input type="text" v-model="form.room" class="bg-page text-default" required>
            <div class=" font-semibold text-red-500" v-if="form.errors.room">{{ lang.get('errors.room') }}</div>

            <button type="submit" class="bg-page3 mt-2 rounded-md p-2 mx-auto disabled:bg-page2" :disabled="form.processing" > {{lang.get('freeForm.create')}} </button>
        </div>
    </form>
</template>

<script setup>
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import {useToast} from "vue-toastification";




let form = useForm({
    'day' : '',
    'startTime' : '',
    'endTime' : '',
    'week' : '',
    'room' : '',
})
const toast = useToast();


let createEvent = () =>{
    form.post('/teachers/event/store', {
        onSuccess: () => {
            toast.success(usePage().props.value.flash.success_message, {})
        },
        onError: () =>{
            toast.error(props.lang.get('errors.'+usePage().props.value.errors.semester_error), {})

        }
    });
}
let props = defineProps({
    lang: Object
})


</script>

<style scoped>

</style>
