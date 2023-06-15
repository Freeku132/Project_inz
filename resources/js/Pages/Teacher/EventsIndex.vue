<template>
    <Head>
        <title> {{ lang.get('teachersEventsIndex.title') }} </title>
    </Head>
    <div>
        <div class="flex flex-col md:flex-row md:justify-end">
            <div class="md:w-1/4 md:fixed left-1 z-40">
                <img
                    class="rounded-full h-56 w-56 mx-auto mt-12 border-b-4 border-l-4 border-default"
                    src="https://www.gravatar.com/avatar/3459098?s=200&d=mp"
                />
                <div class="justify-center items-center mt-2 flex flex-col space-y-6">
                    <p class="text-4xl font-bold m-4">
                        {{user.name}}
                    </p>

                </div>
            </div>

            <div class="border-l-2 border-default pl-2 mt-8 min-h-screen md:mt-0 font-semibold md:w-3/4">

                <div class="flex text-xl m-5">
                    <Link :href=" route('teachers.show' , user.id)"
                          class=" bg-page px-2 py-1 rounded-md hover:bg-page2 text-default no-underline">
                        {{ lang.get('teachersEventsIndex.back')}}</Link>
                </div>

                <div class="relative m-5 rounded-xl">
                    <table class="w-full text-xl text-left text-default ">
                        <tr class="text-default uppercase bg-page ">
                            <th scope="col" class="py-3 px-6">
                                {{ lang.get('table.date') }}
                            </th>
                            <th scope="col" class="py-3 px-6">
                                {{ lang.get('table.topic')}}
                            </th>
                            <th scope="col" class="py-3 px-6">
                                {{ lang.get('table.student')}}
                            </th>

                            <th scope="col" class="py-3 px-6">
                                <Dropdown align="right" class="mr-5" width="48">
                                    <template #trigger>
                                <span class="inline-flex rounded-md ">
                                    <button type="button" class="inline-flex uppercase font-bold items-center px-3 py-2 border border-transparent leading-4 font-medium rounded-md text-default bg-page hover:text-default2 focus:outline-none transition ease-in-out duration-150">
                                        {{category}}
                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink @click="changeCategory('all')" class="bg-page text-default uppercase font-bold" as="button">
                                            {{lang.get('table.category.all')}}
                                        </DropdownLink>
                                        <DropdownLink @click="changeCategory('accepted')" class="bg-page text-default uppercase font-bold" as="button">
                                            {{lang.get('table.category.accepted')}}
                                        </DropdownLink>
                                        <DropdownLink @click="changeCategory('busy')" class="bg-page text-default  uppercase font-bold" as="button">
                                            {{lang.get('table.category.busy')}}
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </th>
                            <th scope="col" class="py-3 px-6">
                                {{ lang.get('table.action') }}
                            </th>
                        </tr>
                        <TableComponent v-for="event in events.data" :event="event" :user="props.user" :filters="filters" :selected="props.selected" :category="props.category" :lang="lang"/>
                    </table>
                </div>

                <div class="flex justify-center">
                    <div v-for="link in events.links">
                        <Pagination :link="link"/>
                    </div>
                </div>
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

import TableComponent from "@/Components/TableComponent.vue";
import {Link} from "@inertiajs/inertia-vue3";
import {defineAsyncComponent, ref} from "vue";
import Dropdown from "@/Components/Dropdown.vue"
import DropdownLink from "@/Components/DropdownLink.vue"
import {Inertia} from "@inertiajs/inertia";
import Lang from "lang.js";
import teachersEventsIndex from "../../../../lang/teachersEventsIndex.json";

let props = defineProps({
    user: Object,
    events: Object,
    filters: Object,
    selected: Object,
    category: String,
})



let Pagination = defineAsyncComponent( () => {
    return import("@/Components/Pagination.vue");
} )


let changeCategory = (value) => {
    Inertia.get(route('event-teacher.index', props.user), {
        selected: props.selected,
        category: value
    })
}


let lang = ref(new Lang({
    messages: teachersEventsIndex
}));

let chosenLang = ref(localStorage.getItem('lang') || 'en');

lang.value.setLocale(chosenLang);
lang.value.setFallback(chosenLang);

let category = props.category ? lang.value.get('table.category.'+props.category) : lang.value.get('table.category.all');

</script>

<style scoped>
.free {background-color: forestgreen;}
.claim {background-color: darkorange;}
.busy {background-color: gray;}
.cancelled {background-color: red;}
</style>
