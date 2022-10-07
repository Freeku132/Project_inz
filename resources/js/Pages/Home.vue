<template>

    <main class="mx-auto">
        <h1 class="">
            Hello world
        </h1>
    </main>

</template>

<script>
import Layout from "@/Shared/Layout.vue"
export default {
    layout: Layout,
}


// export default {
//     data() {
//         return {
//             themes:
//                 {
//                     'theme-light': 'white',
//                     'theme-dark': 'black',
//                 },
//             selectedTheme: 'theme-light'
//         };
//     },
//     created() {
//         this.selectedTheme = localStorage.getItem('theme') || "theme-light";
//     },
//     watch:{
//         selectedTheme(){
//             document.body.className = document.body.className.replace(/theme-\w+/, this.selectedTheme);
//             localStorage.setItem('theme', this.selectedTheme);
//         }
//     }
// }

</script>

<script setup>
</script>

<style>
</style>
