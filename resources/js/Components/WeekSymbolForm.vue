<template>
    <div class="flex flex-col">
        <label class="mr-2 "> {{lang.get('adminPanelSemester.week')}}: {{number}} - {{week}}</label>
        <select class="w-1/2 bg-page rounded-md" @change="submit(week)" v-model="weekForm.designation">
            <option value="Null">{{lang.get('adminPanelSemester.null')}}</option>
            <option value="A">A</option>
            <option value="B">B</option>
        </select>
    </div>
</template>

<script setup>
import {useForm} from "@inertiajs/inertia-vue3";



let props = defineProps({
    week: String,
    number: Number,
    designation: String,
    lang: Object
})

let weekForm = useForm({
    number: props.number,
    designation: props.designation,
    date: '',

})

let submit = (week) =>{
    weekForm.date = week;
    console.log(weekForm.designation);
    console.log(weekForm.date);

    weekForm.submit('post', route('adminPanel.semester.weekdesignations'), {
        preserveState:true,
        preserveScroll:true,
    })
}


</script>

<style scoped>

</style>
