<script setup>
import { watch } from "vue";
import { useHashRoute } from "@/composables/router";
import { initSession, useCobrandSession } from "./composables/useCobrandSession";

import CobrandLayout        from "./layouts/CobrandLayout.vue";
import Accueil              from "./views/Accueil.vue";
import Prevention           from "./views/Prevention.vue";
import Quiz                 from "./views/Quiz.vue";
import CollecteIndisponible from "./views/CollecteIndisponible.vue";

const props = defineProps({
    collecteId: { type: String, required: true },
});

const { theme, sessionLoading, sessionError } = useCobrandSession();
initSession(props.collecteId);

watch(
    theme,
    (t) => {
        if (!t) return;
        const style = document.documentElement.style;
        Object.entries(t).forEach(([key, val]) => {
            if (val === null || val === undefined) return;
            style.setProperty(`--cobrand-${key.replace(/_/g, "-")}`, val);
        });
    },
    { immediate: true },
);

const routes = [
    { hash: "#/accueil",    key: "accueil",    component: Accueil },
    { hash: "#/prevention", key: "prevention", component: Prevention },
    { hash: "#/quiz",       key: "quiz",       component: Quiz },
];

const { currentComponent, currentRoute } = useHashRoute(routes);
</script>

<template>
    <div aria-live="polite" aria-atomic="true" class="sr-only">
        <span v-if="sessionLoading">Chargement en cours…</span>
    </div>

    <CollecteIndisponible v-if="sessionError" />
    <CobrandLayout v-else-if="!sessionLoading" :current="currentRoute.key">
        <component :is="currentComponent" />
    </CobrandLayout>
</template>
