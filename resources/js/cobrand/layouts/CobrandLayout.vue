<script setup>
import FictifBanner from "../components/FictifBanner.vue";
import CobrandHeader from "../components/CobrandHeader.vue";
import BackgroundMotifs from "../components/BackgroundMotifs.vue";

defineProps({
    current: { type: String, default: null },
});
</script>

<template>
    <div
        class="relative flex min-h-screen flex-col bg-beige-50 font-sans text-light-primary antialiased"
    >
        <template v-if="!['accueil', 'prevention'].includes(current)">
            <div
                class="pointer-events-none absolute inset-0 z-0 bg-[url('/images/cobrand/quizz/motif-tuile-blanc.png')] bg-repeat opacity-50"
                aria-hidden="true"
            ></div>

            <BackgroundMotifs />
        </template>

        <FictifBanner class="relative z-30" />

        <div class="sticky top-0 z-30">
            <CobrandHeader :current="current" />
        </div>

        <main class="relative z-10 flex-1">
            <slot />
        </main>
    </div>
</template>
