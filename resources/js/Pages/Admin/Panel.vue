<template>

    <Head>
        <title>Admin Panel</title>
    </Head>
    <div class="flex md:flex-row flex-col min-h-screen">
        <SideBar/>
        <div class="border-l-2 w-full text-xl font-semibold border-default">

            <div class=" p-4 w-2/3">
                {{lang.get('adminPanel.info')}}
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
import {ref} from "vue";
import Lang from "lang.js";
import adminPanel from "../../../../lang/adminPanel.json";

let lang = ref(new Lang({
    messages: adminPanel
}));
let chosenLang = ref(localStorage.getItem('lang') || 'en');
lang.value.setLocale(chosenLang)

</script>

<style scoped>

</style>
