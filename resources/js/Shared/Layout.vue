<template>
    <nav class="md:sticky top-0 z-40 flex md:flex-row flex-col border-b backdrop-blur border-default justify-between p-5 supports-backdrop-blur:bg-white/60 bg-transparent items-center">
        <!--        <nav class=" sticky top-0 z-40 w-full backdrop-blur flex-none transition-colors duration-500 lg:z-50 lg:border-b lg:border-slate-900/10 dark:border-slate-50/[0.06] bg-white/95 supports-backdrop-blur:bg-white/60 dark:bg-transparent">-->
        <div>
            <h1 class="h1 text-default ">Page Title</h1>
        </div>

        <div class="flex flex-col md:flex-row md:space-x-8 text-default">
            <Link class="text-default" :href="route('teachers.index')" > All Teachers</Link>
            <Link class="text-default" href="#" > About Project</Link>
            <Link class="text-default" href="/profile" > Author Profile</Link>
            <Link class="text-default" :href="route('dashboard') " > Dashboard</Link>
        </div>

        <div class="items-center flex">
        <ThemeSwitcher />
        <div v-if="$page.props.auth.user" >
            <Dropdown align="right" width="48">
                <template #trigger>
                    <span class="inline-flex rounded-md ">
                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-default bg-page hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            {{ $page.props.auth.user.name }}

                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </span>
                </template>

                <template #content>
                    <DropdownLink :href="route('logout')" class="bg-page text-default" method="post" as="button">
                        Log Out
                    </DropdownLink>
                </template>
            </Dropdown>
        </div>
        <div v-else>
            <Link :href="route('login')" class="text-sm text-default dark:text-gray-500 underline">Log in</Link>

            <Link :href="route('register')" class="ml-4 text-sm text-default dark:text-gray-500 underline">Register</Link>
        </div>
        </div>
    </nav>

    <slot/>

</template>

<script setup>
import ThemeSwitcher from "@/Components/ThemeSwitcher.vue";
import {Link} from "@inertiajs/inertia-vue3";
import Dropdown from "@/Components/Dropdown.vue"
import DropdownLink from "@/Components/DropdownLink.vue"


</script>

<style scoped>

</style>
