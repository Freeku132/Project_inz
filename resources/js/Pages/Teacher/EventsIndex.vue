<template>
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
                <div class="overflow-x-auto relative">
                    <table class="w-full text-xl text-left text-default">
                        <tr class="text-default uppercase bg-page">
                            <th scope="col" class="py-3 px-6">
                                Date
                            </th>
                            <th scope="col" class="py-3 px-6">
                                Topic
                            </th>
                            <th scope="col" class="py-3 px-6">
                                Student
                            </th>
                            <th scope="col" class="py-3 px-6">
                                Class
                            </th>
                            <th scope="col" class="py-3 px-6">

                            </th>
                        </tr>
                        <TableComponent v-for="event in events.data" :event="event" :user="props.user"/>
                    </table>

                    <div class="items-center mt-10 mb-40 text-2xl text-center justify-center flex flex-row space-x-8">
                        <Link :href="events.prev_page_url">Prev</Link>
                        <p>{{events.current_page}}</p>
                        <Link :href="events.next_page_url">Next</Link>
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

let props = defineProps({
    user: Object,
    events: Object
})

</script>

<style scoped>
.free {background-color: forestgreen;}
.claim {background-color: darkorange;}
.busy {background-color: gray;}
.cancelled {background-color: red;}
</style>
