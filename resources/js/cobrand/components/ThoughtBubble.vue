<script setup>
import { computed } from "vue";

const props = defineProps({
    reason: { type: String, required: true },
    mandatory: { type: Boolean, default: false },
    side: { type: String, default: "left" },
});

const note = computed(() =>
    props.mandatory
        ? "Vous n'êtes plus éligible."
        : "Vous êtes toujours éligible.",
);
</script>

<template>
    <div
        class="flex w-[300px] flex-col"
        :class="side === 'right' ? 'items-end' : 'items-start'"
    >
        <div
            class="w-full rounded-2xl bg-beige-200 p-4 shadow-[0_4px_4px_rgba(0,0,0,0.10)]"
        >
            <p class="text-small leading-snug text-violet-950">{{ reason }}</p>
            <p
                class="mt-2 text-small font-semibold leading-snug"
                :class="mandatory ? 'text-rouge-600' : 'text-vert-600'"
            >
                {{ note }}
            </p>
            <a
                href="#/prevention"
                class="mt-3 inline-block rounded-full bg-violet-900 px-5 py-2 text-small text-white transition-colors hover:bg-violet-800"
            >
                En savoir +
            </a>
        </div>

        <span
            class="mt-1 h-3.5 w-3.5 rounded-full bg-beige-200"
            :class="side === 'right' ? 'mr-6' : 'ml-6'"
        ></span>
        <span
            class="mt-1 h-2.5 w-2.5 rounded-full bg-beige-200"
            :class="side === 'right' ? 'mr-3' : 'ml-3'"
        ></span>
        <span class="mt-1 h-1.5 w-1.5 rounded-full bg-beige-200"></span>
    </div>
</template>
