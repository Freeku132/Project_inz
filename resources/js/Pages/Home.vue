<template>
    <div class="mt-20 w-2/3 mx-auto text-default">
        <div class="flex flex-col md:flex-row justify-between  p-3">
        <h1 class="text-3xl font-semibold">All teachers</h1>
        <input v-model="search" type="text" placeholder="Search..." class="rounded-xl bg-page">
        </div>
        <table class="w-full border-collapse block mx-auto md:table">
            <thead class="block md:table-header-group">
            <tr class="border border-grey-500 md:border-none block md:table-row absolute top-full md:top-auto left-full md:left-auto  md:relative ">
                <th class="bg-page2 p-2  font-bold md:border md:border-default text-left block md:table-cell">Name</th>
                <th class="bg-page2 p-2  font-bold md:border md:border-default text-left block md:table-cell">Email</th>
                <th class="bg-page2 p-2  font-bold md:border md:border-default text-left block md:table-cell">Actions</th>
            </tr>
            </thead>
            <tbody class="block md:table-row-group">

            <tr v-for="user in users.data" class="bg-page border border-grey-500 md:border-none block md:table-row">
                <td class="p-2 md:border md:border-default text-left block md:table-cell"><span class="inline-block w-1/3 md:hidden font-bold">Name</span>

                    <Link :href="'/profile/'+user.id">{{user.name}}</Link>
                </td>
                <td class="p-2 md:border md:border-default text-left block md:table-cell"><span class="inline-block w-1/3 md:hidden font-bold">Email</span>{{user.email}}</td>
                <td class="p-2 md:border md:border-default text-left md:text-center block md:table-cell">
                    <span class="inline-block  w-1/3 md:hidden font-bold">Actions</span>
                    Numer telefonu czy coś? / Link do profilu?
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="flex justify-center">
<!--        <Pagination :link="users.prev_page_url">Prev</Pagination>-->
        <div v-for="link in users.links">
        <Pagination :link="link"/>
        </div>
<!--        <Pagination :link="users.next_page_url">Next</Pagination>-->
    </div>

</template>

<script>
import Layout from "@/Shared/Layout.vue"
export default {
    layout: Layout,
}



</script>

<script setup>
import {Link} from "@inertiajs/inertia-vue3";
import {ref, watch} from "vue";
import {debounce} from "lodash/function";
import {Inertia} from "@inertiajs/inertia";
import {defineAsyncComponent} from "vue";

let props = defineProps({
    users: Object,
    filters : Object,

})

let search = ref(props.filters.search);

watch(search, debounce( (value) => {
    Inertia.get('/', {
        search: value,
    }, {
        replace: true,
        preserveState : true,
    });
}, 300));

let Pagination = defineAsyncComponent( () => {
    return import("@/Components/Pagination.vue");
} )


</script>

<style>

</style>
