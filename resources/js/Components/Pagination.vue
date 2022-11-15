<template>
    <div class="mt-5 space-x-2">
        <Link
            :class="{'shadow-lg bg-page2 text-xl' : 'http://localhost' + $page.url === link.url || $page.url  === link.url,
              }"
            class="border border-default bg-page rounded hover:bg-page2 no-underline text-default px-1 m-1"
            :href="link.url"
            v-html="link.label"
        />

    </div>
</template>

<script setup>
import { Link } from "@inertiajs/inertia-vue3";

let props = defineProps({
    link : Object,
})
//
// if(props.link.label === '&laquo; Previous') {
//     props.link.label = 'Prev'
// }
// if(props.link.label === 'Next &raquo;') {
//     props.link.label = 'Next'
// }


</script>

<style scoped>

</style>
