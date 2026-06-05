<script setup>
import CobrandLayout from "./layouts/CobrandLayout.vue";
import { useHashRoute } from "@/composables/router";
import { initSession } from "./composables/useCobrandSession";

import Accueil from "./views/Accueil.vue";
import Prevention from "./views/Prevention.vue";
import Quiz from "./views/Quiz.vue";
import Redirect from "./views/Redirect.vue";

const props = defineProps({
    cobrandToken: { type: String, required: true },
});

initSession(props.cobrandToken);

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
