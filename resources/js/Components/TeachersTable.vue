<template>
    <div class="mt-20 w-2/3 mx-auto text-default">
        <div class="flex flex-col md:flex-row justify-between  p-3">
            <h1 class="text-3xl font-semibold">    {{ lang.get('teachersIndex.header')}}</h1>
            <input v-model="search" id="search" type="text" :placeholder="lang.get('teachersIndex.search')" class="rounded-xl bg-page max-h-10">
        </div>
        <table class="w-full border-collapse block mx-auto md:table">
            <thead class="block md:table-header-group">
            <tr class="border border-grey-500 hidden md:static md:border-none block md:table-row absolute top-full md:top-auto left-full md:left-auto  md:relative ">
                <th class="bg-page2 p-2  font-bold md:border md:border-default text-left block md:table-cell">    {{ lang.get('teachersIndex.name')}}</th>
                <th class="bg-page2 p-2  font-bold md:border md:border-default text-left block md:table-cell">    {{ lang.get('teachersIndex.email')}}</th>
                <th class="bg-page2 p-2  font-bold md:border md:border-default text-left block md:table-cell">    {{ lang.get('teachersIndex.others')}}</th>
            </tr>
            </thead>
            <tbody class="block md:table-row-group">

            <tr v-for="user in users.data" class="bg-page border border-grey-500 md:border-none block md:table-row">
                <td class="p-2 md:border md:border-default text-left block md:table-cell"><span class="inline-block w-1/3 md:hidden font-bold">{{ lang.get('teachersIndex.name')}}</span>

                    <Link :href="route('teachers.show', user)">{{user.name}}</Link>
                </td>
                <td class="p-2 md:border md:border-default text-left block md:table-cell"><span class="inline-block w-1/3 md:hidden font-bold">{{ lang.get('teachersIndex.email')}}</span>{{user.email}}</td>
                <td class="p-2 md:border md:border-default text-left md:text-center block md:table-cell">
                    <span class="inline-block  w-1/3 md:hidden font-bold">{{ lang.get('teachersIndex.others')}}</span>
                    Numer telefonu czy coś? / Link do profilu?
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="flex justify-center">
        <div v-for="link in users.links">

            <Pagination :link="link"/>

        </div>
    </div>
</template>

<script setup>
import {defineAsyncComponent, ref, watch} from "vue";

import {debounce} from "lodash/function";
import {Inertia} from "@inertiajs/inertia";
import {Link} from "@inertiajs/inertia-vue3";


let props = defineProps({
    users: Object,
    filters : Object,
    routing: String,
    lang: Object

})
let Pagination = defineAsyncComponent( () => {
    return import("@/Components/Pagination.vue");
} )

let search = ref(props.filters.search);

watch(search, debounce( (value) => {
    Inertia.get(props.routing, {
        search: value,
    }, {
        replace: true,
        preserveState : true,
    });
}, 300));


</script>

<style scoped>

</style>
