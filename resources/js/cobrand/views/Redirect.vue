<script setup>
import { onMounted } from "vue";
import { useCobrandSession } from "../composables/useCobrandSession";
import { useQuizStore } from "../composables/useQuizStore";

const { onedocUrl, trackQuiz } = useCobrandSession();
const { eligible } = useQuizStore();

onMounted(() => {
    if (!eligible.value) {
        window.location.hash = "#/quiz";
        return;
    }
    trackQuiz({ event_type: "quiz_completed" });
});

function trackOnedoc() {
    trackQuiz({ event_type: "onedoc_clicked" });
}
</script>

<template>
    <div class="mx-auto max-w-3xl px-6 py-24 text-center">
        <h1 class="text-h2 font-bold text-violet-950">
            Réservez votre créneau
        </h1>
        <p class="mt-4 text-violet-900">
            Vous pouvez maintenant choisir votre créneau de don sur Onedoc.
        </p>

        <a
            v-if="onedocUrl"
            :href="onedocUrl"
            target="_blank"
            rel="noopener"
            @click="trackOnedoc"
            class="mt-8 inline-block rounded-full bg-violet-900 px-8 py-3 text-regular text-white transition-colors hover:bg-violet-800"
        >
            Prendre rendez-vous sur Onedoc
        </a>
        <p v-else class="mt-8 text-violet-900">
            Chargement du lien d'inscription…
        </p>
    </div>
</template>
