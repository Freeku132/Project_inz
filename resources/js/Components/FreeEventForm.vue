<template>
    <form @submit.prevent="createEvent">
        <div class="flex flex-col bg-page rounded-xl m-4 p-4" >
            <label>Day</label>
            <select v-model="form.day" class="bg-page text-default">
                <option value="1">Monday</option>
                <option value="2">Tuesday</option>
                <option value="3">Wednesday</option>
                <option value="4">Thursday</option>
                <option value="5">Friday</option>
                <option value="6">Saturday</option>
                <option value="7">Sunday</option>
            </select>
            <div class=" font-semibold text-red-500" v-if="form.errors.day">{{form.errors.day}}</div>
            <label>Start Time</label>
            <input type="time" v-model="form.startTime" class="bg-page text-default">
            <div class=" font-semibold text-red-500" v-if="form.errors.startTime">{{form.errors.startTime}}</div>
            <label>End Time</label>
            <input type="time" v-model="form.endTime" class="bg-page text-default">
            <div class=" font-semibold text-red-500" v-if="form.errors.endTime">{{form.errors.endTime}}</div>
            <label>Week</label>
            <select v-model="form.week" class="bg-page text-default">
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="A/B">A/B</option>
            </select>
            <div class=" font-semibold text-red-500" v-if="form.errors.week">{{form.errors.week}}</div>
            <label>Room</label>
            <input type="text" v-model="form.room" class="bg-page text-default">
            <div class=" font-semibold text-red-500" v-if="form.errors.room">{{form.errors.room}}</div>
            <button type="submit" class="bg-page2 mt-2 rounded-md p-2 mx-auto disabled:bg-page" :disabled="form.processing" >CREATE</button>
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
        }
    });
}

</script>

<style scoped>

</style>
