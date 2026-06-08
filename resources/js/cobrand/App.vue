<script setup>
import { watch } from "vue";
import { useHashRoute } from "@/composables/router";
import { initSession, useCobrandSession } from "./composables/useCobrandSession";

import CobrandLayout from "./layouts/CobrandLayout.vue";
import Accueil from "./views/Accueil.vue";
import Prevention from "./views/Prevention.vue";
import Quiz from "./views/Quiz.vue";
import Redirect from "./views/Redirect.vue";

const props = defineProps({
    cobrandToken: { type: String, required: true },
});

const { theme } = useCobrandSession();
initSession(props.cobrandToken);

watch(
    theme,
    (t) => {
        if (!t) return;
        const style = document.documentElement.style;
        Object.entries(t).forEach(([key, val]) => {
            style.setProperty(`--cobrand-${key.replace(/_/g, "-")}`, val);
        });
    },
    { immediate: true },
);

const routes = [
    { hash: "#/accueil", key: "accueil", component: Accueil },
    { hash: "#/prevention", key: "prevention", component: Prevention },
    { hash: "#/quiz", key: "quiz", component: Quiz },
    { hash: "#/inscription", key: "inscription", component: Redirect },
];

const { currentComponent, currentRoute } = useHashRoute(routes);
</script>

<template>
    <CobrandLayout :current="currentRoute.key">
        <component :is="currentComponent" />
    </CobrandLayout>
</template>
