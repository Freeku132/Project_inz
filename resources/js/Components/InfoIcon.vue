<template>
    <section

    >
    <Component
        is="Button"
        class="w-8"  @click="visibility = !visibility"
        @keydown.esc.prevent="visibility = false"
    >
        <slot/>
    </Component>
    <Component
        @focusout="visibility = false"
        is="Button"
        @click="copy"
        v-if="visibility"
        class="absolute left-48 md:left-44 bg-container px-4 border-2 border-container font-semibold rounded-xl   md:mt-9 mt-10">
        {{information}}
        <p v-show="copied">{{iconName}} was copied</p>
    </Component>
    </section>
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
