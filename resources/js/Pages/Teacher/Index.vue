<template>
    <Head>
        <title>{{ lang.get('teachersIndex.title')}} </title>
    </Head>


    <TeachersTable :users="props.users" :filters="props.filters" :routing="route('teachers.index')" :lang="lang" />


</template>

<script>
import Layout from "@/Shared/Layout.vue"
export default {
    layout: Layout,
}



</script>

<script setup>
import {ref} from "vue";
import Lang from "lang.js";
import teacherIndex from "../../../../lang/teachersIndex.json";
import TeachersTable from "@/Components/TeachersTable.vue";

let props = defineProps({
    users: Object,
    filters : Object,
})


let lang = ref(new Lang({
    messages: teacherIndex
}));
let chosenLang = ref(localStorage.getItem('lang') || 'en');
lang.value.setLocale(chosenLang)
</script>

<style>

</style>
