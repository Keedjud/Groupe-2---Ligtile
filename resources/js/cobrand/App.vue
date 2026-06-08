<script setup>
import { watch } from "vue";
import { useHashRoute } from "@/composables/router";
import { initSession, useCobrandSession } from "./composables/useCobrandSession";

import Accueil    from "./views/Accueil.vue";
import Prevention from "./views/Prevention.vue";
import Quiz       from "./views/Quiz.vue";
import Redirect   from "./views/Redirect.vue";

const props = defineProps({
    collecteId: { type: String, required: true },
});

const { theme } = useCobrandSession();
initSession(props.collecteId);

// Injection des couleurs de l'entreprise comme variables CSS sur :root.
// Toutes les vues cobrand peuvent ensuite utiliser var(--cobrand-primary) etc.
watch(
    theme,
    (t) => {
        if (!t) return;
        const style = document.documentElement.style;
        // Normalize keys: drop trailing _color or -color, convert underscores to hyphens
        const normalize = (k) => {
            let s = k.toString();
            s = s.replace(/_color$/i, '');
            s = s.replace(/-color$/i, '');
            s = s.replace(/_+/g, '-');
            return s;
        };

        if (typeof t === 'string') {
            style.setProperty('--cobrand-primary', t);
        } else {
            Object.entries(t).forEach(([key, val]) => {
                const nk = normalize(key);
                if (val === null || val === undefined) return;
                style.setProperty(`--cobrand-${nk}`, val);
            });
        }
    },
    { immediate: true },
);

const routes = [
    { hash: "#/accueil",     key: "accueil",     component: Accueil },
    { hash: "#/prevention",  key: "prevention",  component: Prevention },
    { hash: "#/quiz",        key: "quiz",         component: Quiz },
    { hash: "#/inscription", key: "inscription",  component: Redirect },
];

const { currentComponent } = useHashRoute(routes);
</script>

<template>
    <component :is="currentComponent" />
</template>
