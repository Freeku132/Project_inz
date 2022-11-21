<template>

    <div class="flex flex-col items-center">
    <div
        class="w-8 "
    >

        <slot/>

    </div>
    <Component
        is="Button"
        @click="copy"
        class="px-4 font-semibold  mt-2">
        {{information}}
        <p v-show="copied">{{ props.copyInfo }}</p>
    </Component>
    </div>
</template>

<script setup>
import {useClipboard} from '@/Composables/useClipboard';
import {ref} from "vue";


let block = ref(null);

let {copy, copied, supported} = useClipboard(props.information);

let props = defineProps({
    copyInfo: String,
    information : String,
    visibility: Boolean,
})

</script>

<style scoped>

</style>
