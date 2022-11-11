<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex items-center px-4 py-2 bg-page border border-transparent rounded-md font-semibold text-xs text-default uppercase tracking-widest hover:bg-page2 active:bg-page3 focus:outline-none focus:border-focus focus:shadow-outline-gray transition ease-in-out duration-150">
        <slot />
    </button>
</template>
