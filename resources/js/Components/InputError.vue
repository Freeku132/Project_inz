<script setup>
defineProps(['message']);
</script>

<template>
    <div v-show="message">
        <p class="text-md  text-red-600">
            {{ message }}
        </p>
    </div>
</template>
