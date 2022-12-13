<template>

    <Head>
        <title>Admin Panel</title>
    </Head>
    <div class="flex md:flex-row flex-col min-h-screen">
        <SideBar/>
        <div class="border-l-2 w-full text-xl font-semibold border-default">
            <p>
                Strona w trakcie tworzenia
            </p>
            <p>
                Page under construction
            </p>
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

</script>

<style scoped>

</style>
