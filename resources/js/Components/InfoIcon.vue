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
        <p v-show="copied">{{iconName.charAt(0).toUpperCase() + iconName.slice(1)}} was copied</p>
    </Component>
    </div>
</template>

<script setup>
import {useClipboard} from '@/Composables/useClipboard';
import {ref} from "vue";


let block = ref(null);

let {copy, copied, supported} = useClipboard(props.information);

let props = defineProps({
    iconName: String,
    information : String,
    visibility: Boolean,
})

</script>

<style scoped>

</style>
