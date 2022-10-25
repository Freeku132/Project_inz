<template>
    <nav class="md:sticky top-0 z-40 flex md:flex-row flex-col border-b backdrop-blur border-default justify-between p-5 supports-backdrop-blur:bg-white/60 bg-transparent items-center">
        <!--        <nav class=" sticky top-0 z-40 w-full backdrop-blur flex-none transition-colors duration-500 lg:z-50 lg:border-b lg:border-slate-900/10 dark:border-slate-50/[0.06] bg-white/95 supports-backdrop-blur:bg-white/60 dark:bg-transparent">-->
        <div>
            <h1 class="h1 text-default ">Page Title</h1>
        </div>

        <div class="flex flex-col md:flex-row md:space-x-8 text-default">
            <Link href="/" > Main Page</Link>
            <Link href="#" > About Project</Link>
            <Link href="/profile" > Author Profile</Link>
        </div>

        <ThemeSwitcher />

    </nav>

    <slot/>

</template>

<script setup>
import ThemeSwitcher from "@/Components/ThemeSwitcher.vue";
import {Link} from "@inertiajs/inertia-vue3";


</script>

<style scoped>

</style>
