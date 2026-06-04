<template>
    <router-view />
</template>

<script setup>
defineProps({
    collecteId: { type: String, required: true },
})
</script>
