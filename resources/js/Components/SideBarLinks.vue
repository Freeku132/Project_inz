<template>
    <div class="flex flex-col">
        <button type="button" @click="show = !show" class="bg-page font-bold p-2 rounded no-underline w-full text-default text-center hover:bg-page2 uppercase" >
           {{lang.get('sideBar.'+name)}}
        </button>
        <div v-if="show" class="flex flex-col w-11/12 mt-2">
            <slot/>
        </div>
    </div>
</template>

<script setup>

import {ref} from "vue";
import {usePage} from "@inertiajs/inertia-vue3";

let props = defineProps({
    name: String,
    lang: Object,
})

let show = ref(false);


if (route().current('adminPanel.'+props.name)){
    show.value = true
} else {
    show.value = false
}



</script>

<style scoped>

</style>
