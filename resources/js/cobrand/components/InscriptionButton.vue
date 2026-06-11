<script setup>
defineProps({
    label: { type: String, default: "S'inscrire" },
    ratio: { type: Number, default: 0 },
    enabled: { type: Boolean, default: false },
});

const emit = defineEmits(["click"]);
</script>

<template>
    <button
        type="button"
        :disabled="!enabled"
        @click="emit('click')"
        class="relative h-[58px] w-full max-w-[344px] overflow-hidden rounded-full border border-black text-h5 font-medium transition-colors lg:w-[344px]"
        :style="{
            backgroundColor: enabled
                ? 'var(--cobrand-primary-on-light, var(--color-violet-900))'
                : 'color-mix(in srgb, var(--cobrand-primary-on-light, var(--color-violet-900)) 35%, white)',
        }"
        :class="enabled ? 'cursor-pointer' : 'cursor-not-allowed'"
    >
        <span
            class="pointer-events-none absolute inset-x-0 bottom-0 z-0 transition-[height] duration-700"
            :style="{ height: ratio * 100 + '%', backgroundColor: '#d41f1f' }"
        >
            <span
                v-if="ratio > 0"
                class="absolute inset-x-0 top-0 h-3 -translate-y-[55%]"
                style="
                    background-image: url(&quot;/images/cobrand/quizz/vaguelette.svg&quot;);
                    background-size: 100% 100%;
                    background-repeat: no-repeat;
                "
            ></span>
        </span>

        <span
            class="absolute inset-0 z-10 flex items-center justify-center"
            :style="{             color: (enabled && ratio > 0) ? '#ffffff' : enabled ? 'var(--cobrand-primary-text, #ffffff)' : '#ffffff'
 }"

            >{{ label }}</span
        >
    </button>
</template>
