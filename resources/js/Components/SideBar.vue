<template>
    <div class="lg:w-1/6  md:w-1/5 shadow-3xl p-2 space-y-2">
        <div>Menu:</div>
        <div class="flex flex-col">
            <SideBarLinks :name="'users'" :lang="lang">
                <Link  :href="route('adminPanel.users')"
                       class="bg-page m-1 p-1 rounded no-underline text-default text-sm hover:bg-page2 "
                       :class="route().current('adminPanel.users')? 'bg-page3' : 'bg-page'">
                    {{lang.get('sideBar.manageUser')}}
                </Link>
            </SideBarLinks>
        </div>
        <div class="flex flex-col">
            <SideBarLinks :name="'events'"  :lang="lang">
                <Link
                    :href="route('adminPanel.events')"
                    class="bg-page m-1 p-1 rounded no-underline text-default text-sm hover:bg-page2"
                    :class="route().current('adminPanel.events')? 'bg-page3' : 'bg-page'"
                >
                    {{lang.get('sideBar.manageEvents')}}
                </Link>
            </SideBarLinks>
        </div>
        <div class="flex flex-col">
            <SideBarLinks :name="'semester'"  :lang="lang">
                <Link :href="route('adminPanel.semester')"
                      class="bg-page m-1 p-1 rounded no-underline text-default text-sm hover:bg-page2 "
                      :class="route().current('adminPanel.semester')? 'bg-page3' : 'bg-page'">
                    {{lang.get('sideBar.semesterConfig')}}
                </Link>
            </SideBarLinks>

        </div>
    </div>
</template>

<script setup>
import SideBarLinks from './SideBarLinks.vue'
import {Link} from "@inertiajs/inertia-vue3";
import {ref} from "vue";
import Lang from "lang.js";
import sideBar from "../../../lang/sideBar.json";

let lang = ref(new Lang({
    messages: sideBar
}));
let chosenLang = ref(localStorage.getItem('lang') || 'en');
lang.value.setLocale(chosenLang)


</script>

<style scoped>

</style>
