<template>
    <form @submit.prevent="createEvent">
        <div class="flex flex-col w-1/3 bg-page rounded-xl m-4 p-4" >
            <label>Data</label>
            <input type="date" v-model="form.date" class="bg-page text-default">
            <div class=" font-semibold text-red-500" v-if="form.errors.day">{{form.errors.day}}</div>
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
            <div class="flex items-center mt-2">
                <label>Semester</label>
                <input type="checkbox" class="text-default ml-5 border border-gray-300" v-model="form.semester" >
            </div>
            <div class=" font-semibold text-red-500" v-if="form.errors.semester">{{form.errors.semester}}</div>
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
            <button type="submit" class="bg-page2 mt-2 rounded-md p-2 mx-auto" >CREATE</button>
        </div>
    </form>
</template>

<script setup>
import {useForm} from "@inertiajs/inertia-vue3";



let form = useForm({
    'day' : '',
    'date' : '',
    'startTime' : '',
    'endTime' : '',
    'semester' : '',
    'week' : '',
    'room' : '',
})

let createEvent = () =>{
    form.post('/event/create');
}

</script>

<style scoped>

</style>
