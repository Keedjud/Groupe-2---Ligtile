<script setup>
defineProps({
    title: { type: String, required: true },
    centered: { type: Boolean, default: false },
});
</script>

<template>
    <div
        class="flex flex-col gap-3"
        :class="centered ? 'items-center text-center' : 'items-start'"
    >
        <h2
            class="text-h2 font-semibold text-[color:var(--prevention-accent)] lg:text-h1"
        >
            {{ title }}
        </h2>
        <span
            class="h-[3px] w-[216px] max-w-full rounded-full bg-[var(--prevention-accent)]"
            aria-hidden="true"
        ></span>
    </div>
</template>
