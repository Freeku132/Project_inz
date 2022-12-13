<template>
    <nav class="md:sticky top-0 z-40 flex md:flex-row flex-col border-b backdrop-blur border-default justify-between p-5 supports-backdrop-blur:bg-white/60 bg-transparent items-center">
        <!--        <nav class=" sticky top-0 z-40 w-full backdrop-blur flex-none transition-colors duration-500 lg:z-50 lg:border-b lg:border-slate-900/10 dark:border-slate-50/[0.06] bg-white/95 supports-backdrop-blur:bg-white/60 dark:bg-transparent">-->
        <div>
            <h1 class="h1 text-default ">{{ lang.get('nav.title') }}</h1>
        </div>

        <div class="flex flex-col md:flex-row md:space-x-4 text-default text-sm xl:text-xl md:text-center px-4">
            <Link class="text-default" :href="route('home') " >    {{ lang.get('nav.home') }}</Link>
            <Link class="text-default" :href="route('teachers.index')" > {{ lang.get('nav.teachers') }}</Link>
            <Link class="text-default" href="#" > {{ lang.get('nav.about') }}</Link>
            <Link class="text-default" href="/profile" > {{ lang.get('nav.profile') }}</Link>
            <Link v-if="$page.props.auth.admin" class="text-default" :href="route('adminPanel')" > Admin Panel</Link>
        </div>

        <div class="items-center flex">

        <ThemeSwitcher />

            <Dropdown align="right" class="mr-5" width="42">
                <template #trigger>
                    <span class="inline-flex rounded-md ">
                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-default bg-page hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            {{ chosenLang.toString().toUpperCase() }}

                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </span>
                </template>

                <template #content>
                    <DropdownLink @click="selectLang('pl')" class="bg-page  text-default" method="post" as="button">
                        PL
                    </DropdownLink>
                    <DropdownLink @click="selectLang('en')" class="bg-page text-default" method="post" as="button">
                        EN
                    </DropdownLink>
                </template>
            </Dropdown>

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

                    <DropdownLink v-if="$page.props.auth.user.role_id === 2" :href="route('teachers.show', $page.props.auth.user)" class="bg-page text-default" method="get" as="button">
                        {{ lang.get('nav.profile') }}
                    </DropdownLink>
                    <DropdownLink v-if="$page.props.auth.user.role_id === 2" :href="route('event-teacher.index', $page.props.auth.user)" class="bg-page text-default" method="get" as="button">
                        {{ lang.get('nav.eventList') }}
                    </DropdownLink>
                    <DropdownLink :href="route('logout')" class="bg-page text-default" method="post" as="button">
                        {{ lang.get('nav.logout') }}
                    </DropdownLink>
                </template>
            </Dropdown>
        </div>
        <div v-else>
            <Link :href="route('login')" class="text-sm text-default dark:text-gray-500 underline"> {{ lang.get('nav.login') }}</Link>

            <Link :href="route('register')" class="ml-4 text-sm text-default dark:text-gray-500 underline"> {{ lang.get('nav.register') }}</Link>
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
import Lang from "lang.js";
import messages from "/lang/messages.json"
import {ref} from "vue";


var lang = ref(new Lang({
    messages: messages
}));

let chosenLang = ref(localStorage.getItem('lang') || 'en');
lang.value.setLocale(chosenLang)
lang.value.setFallback(chosenLang)

let selectLang = (value) => {
    localStorage.setItem('lang', value);
    location.reload()
}

</script>

<style scoped>

</style>
