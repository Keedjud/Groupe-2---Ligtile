<script setup>
const props = defineProps({
    label: { type: String, required: true },
    variant: { type: String, default: "oui" },
    state: { type: String, default: "active" },
});

const emit = defineEmits(["click"]);

function colorClass() {
    if (props.state === "active") {
        return props.variant === "non"
            ? "bg-rouge-400 text-white"
            : "bg-vert-400 text-white";
    }
    return props.variant === "non"
        ? "bg-rouge-200 text-white/70"
        : "bg-vert-200 text-white/70";
}
</script>

<template>
    <button
        type="button"
        :disabled="state === 'disabled'"
        @click="emit('click')"
        class="min-w-[64px] rounded-full px-4 py-1.5 text-small font-medium transition-colors duration-300 disabled:cursor-not-allowed lg:min-w-[96px] lg:px-7 lg:py-2 lg:text-regular"
        :class="colorClass()"
    >
        {{ label }}
    </button>
</template>
