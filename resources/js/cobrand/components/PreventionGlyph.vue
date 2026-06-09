<script setup>
defineProps({
    name: { type: String, required: true },
});
</script>

<template>
    <svg
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.8"
        stroke-linecap="round"
        stroke-linejoin="round"
        class="h-1/2 w-1/2"
        aria-hidden="true"
    >
        <template v-if="name === 'drop'">
            <path
                d="M12 3c2.8 3.3 5.5 6.6 5.5 9.7a5.5 5.5 0 0 1-11 0C6.5 9.6 9.2 6.3 12 3z"
            />
        </template>
        <template v-else-if="name === 'music'">
            <circle cx="7" cy="17.5" r="2.2" />
            <circle cx="17" cy="15.5" r="2.2" />
            <path d="M9.2 17.5V6l10-2v9.5" />
        </template>
        <template v-else-if="name === 'chat'">
            <path d="M4.5 5h15v10h-9l-4 3.5V15h-2z" />
        </template>
        <template v-else-if="name === 'clock'">
            <circle cx="12" cy="12" r="8.5" />
            <path d="M12 7.5V12l3 2" />
        </template>
        <template v-else-if="name === 'shield'">
            <path d="M12 3l7 2.5v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9v-5z" />
            <path d="M9 12l2 2 4-4.5" />
        </template>
    </svg>
</template>
