<!-- Metriques -->
<script setup>
import { ref, computed, onMounted, watch } from "vue";
import DashboardLayout from "../layouts/DashboardLayout.vue";
import { useCollectes } from "../composables/useCollectes.js";
import { useFetchApi } from "@/composables/api/useFetchApi";

const props = defineProps({
    allerVers: { type: Function, required: true },
});

const { listeCollectes, chargerCollectes } = useCollectes();
const { fetchApi } = useFetchApi();

// Filtres
const anneesSelectionnees = ref([]);
const entrepriseFiltre = ref("");

const anneesDisponibles = computed(() => {
    const set = new Set();
    for (const c of listeCollectes.value) {
        if (c.date_debut) set.add(c.date_debut.slice(0, 4));
    }
    return [...set].sort((a, b) => b - a); // plus récente en premier
});

const entreprisesDisponibles = computed(() => {
    const parId = new Map();
    for (const c of listeCollectes.value) {
        if (c.entreprise.id != null && !parId.has(c.entreprise.id)) {
            parId.set(c.entreprise.id, c.entreprise.nom);
        }
    }
    return Array.from(parId, ([id, nom]) => ({ id, nom }));
});

const contexteNom = ref(null);
const titrePage = computed(() =>
    contexteNom.value
        ? `Analytics - ${contexteNom.value}`
        : "Analytics - toutes les entreprises",
);

const totalCollectes = ref(0);
const totalInscrits = ref(0);
const entreprisesDistinctes = ref(0);
const donneesEvolution = ref([]);
const tauxRemplissageMoyen = ref(0);
const collectesRecurrentes = ref(0);
const topEntreprises = ref([]);
const nbDemandesContact = ref(0);
const kpiQuiz = ref({
    tauxCompletion: 0,
    tauxEliminationP1: 0,
    tauxClicOnedoc: 0,
});
const questionsQuiz = ref([]);
const kpiPrevention = ref({ tempsMoyenScrollytelling: "0s", tauxRebond: 0 });

const questionPrincipaleNonEligibilite = computed(() => {
    if (!questionsQuiz.value.length) return { label: "", mauvaises: 0 };
    return questionsQuiz.value.reduce(
        (max, q) => (q.mauvaises > max.mauvaises ? q : max),
        questionsQuiz.value[0],
    );
});

async function chargerStats() {
    try {
        const params = new URLSearchParams();
        if (entrepriseFiltre.value)
            params.set("company_id", entrepriseFiltre.value);
        for (const a of anneesSelectionnees.value) params.append("years[]", a);
        const qs = params.toString();
        const stats = await fetchApi({
            url: qs ? `/analytics-stats?${qs}` : "/analytics-stats",
        });

        contexteNom.value = stats.contexte?.entreprise_nom ?? null;

        totalCollectes.value = stats.groupe_a.total_collectes;
        totalInscrits.value = stats.groupe_a.total_inscrits;
        entreprisesDistinctes.value = stats.groupe_a.entreprises_distinctes;
        donneesEvolution.value = stats.groupe_a.evolution;

        tauxRemplissageMoyen.value = stats.groupe_b.taux_remplissage_moyen;
        collectesRecurrentes.value = stats.groupe_b.collectes_recurrentes;
        topEntreprises.value = stats.groupe_b.top_entreprises;
        nbDemandesContact.value = stats.groupe_b.demandes_contact;

        kpiQuiz.value = {
            tauxCompletion: stats.groupe_c.taux_completion,
            tauxEliminationP1: stats.groupe_c.taux_elimination_p1,
            tauxClicOnedoc: stats.groupe_c.taux_clic_onedoc,
        };

        questionsQuiz.value = stats.groupe_d.questions;

        kpiPrevention.value = {
            tempsMoyenScrollytelling: stats.groupe_e.temps_moyen,
            tauxRebond: stats.groupe_e.taux_rebond,
        };
    } catch (e) {
        console.error("Erreur de chargement des statistiques", e);
    }
}

function parseFiltersFromHash() {
    const qi = window.location.hash.indexOf("?");
    if (qi === -1) return;
    const params = new URLSearchParams(window.location.hash.slice(qi + 1));
    const cid = params.get("company_id");
    if (cid) entrepriseFiltre.value = Number(cid);
    const years = params.getAll("years[]");
    if (years.length) anneesSelectionnees.value = years;
}

function syncHashFilters() {
    const params = new URLSearchParams();
    if (entrepriseFiltre.value) params.set("company_id", String(entrepriseFiltre.value));
    for (const y of anneesSelectionnees.value) params.append("years[]", y);
    const qs = params.toString();
    window.history.replaceState(null, "", qs ? `#/analytics?${qs}` : "#/analytics");
}

onMounted(async () => {
    parseFiltersFromHash();
    if (!listeCollectes.value.length) {
        await chargerCollectes();
    }
    await chargerStats();
});

watch(entrepriseFiltre, () => { syncHashFilters(); chargerStats(); });
watch(anneesSelectionnees, () => { syncHashFilters(); chargerStats(); });

// Graphique barres SVG
const graphiqueH = 180;
const graphiqueW = 520;
const paddingG = 40;
const paddingB = 30;
const largeurBarre = computed(() => {
    const nb = donneesEvolution.value.length;
    if (!nb) return 30;
    return Math.min(50, Math.floor((graphiqueW - paddingG) / nb - 10));
});
const maxInscrits = computed(() =>
    Math.max(...donneesEvolution.value.map((d) => d.inscrits), 1),
);
const barres = computed(() =>
    donneesEvolution.value.map((d, i) => {
        const nb = donneesEvolution.value.length;
        const espa = (graphiqueW - paddingG) / nb;
        const x = paddingG + i * espa + (espa - largeurBarre.value) / 2;
        const h = Math.round(
            (d.inscrits / maxInscrits.value) * (graphiqueH - paddingB - 10),
        );
        const y = graphiqueH - paddingB - h;
        return { ...d, x, y, h, barre: largeurBarre.value };
    }),
);
</script>

<template>
    <DashboardLayout vueCourante="analytics" :allerVers="allerVers">
        <div class="mb-6 flex flex-wrap items-center gap-3">
            <h1 class="font-sans text-h2 font-bold text-texte-secondary">
                {{ titrePage }}
            </h1>
            <button
                @click="allerVers('#/collectes')"
                class="rounded-[40px] bg-white px-4 py-1.5 font-sans text-small text-texte-secondary underline shadow-[0_0_4px_rgba(0,0,0,0.25)] transition-colors hover:bg-violet-50"
            >
                ← Retour aux collectes
            </button>
        </div>

        <!-- Filtres -->
        <div class="mb-6 flex flex-wrap items-start gap-4">
            <!-- Filtre années (multi-sélection par cases à cocher) -->
            <div class="flex flex-col gap-1">
                <label
                    class="font-sans text-small font-semibold text-violet-950"
                    >Années</label
                >
                <div
                    class="flex flex-wrap items-center gap-3 rounded-lg bg-white px-3 py-2 shadow-[0_0_4px_rgba(0,0,0,0.15)]"
                >
                    <span
                        v-if="!anneesDisponibles.length"
                        class="font-sans text-small italic text-violet-400"
                        >Aucune donnée</span
                    >
                    <label
                        v-for="a in anneesDisponibles"
                        :key="a"
                        class="flex cursor-pointer select-none items-center gap-1.5 font-sans text-small text-violet-900"
                    >
                        <input
                            type="checkbox"
                            :value="a"
                            v-model="anneesSelectionnees"
                            class="h-4 w-4 cursor-pointer rounded accent-violet-700"
                        />
                        {{ a }}
                    </label>
                </div>
                <!-- invisible plutôt que vide : garde la même hauteur de bloc dans tous les cas -->
                <span
                    class="font-sans text-xs italic text-violet-400"
                    :class="{ invisible: anneesSelectionnees.length }"
                    >Toutes les années affichées</span
                >
            </div>

            <!-- Filtre entreprise -->
            <div class="flex flex-col gap-1">
                <label
                    class="font-sans text-small font-semibold text-violet-950"
                    >Entreprise</label
                >
                <select
                    v-model="entrepriseFiltre"
                    class="rounded-lg bg-white px-3 py-2 font-sans text-small shadow-[0_0_4px_rgba(0,0,0,0.15)] outline-none focus:ring-2 focus:ring-violet-400"
                >
                    <option value="">Toutes</option>
                    <option
                        v-for="e in entreprisesDisponibles"
                        :key="e.id"
                        :value="e.id"
                    >
                        {{ e.nom }}
                    </option>
                </select>
            </div>
        </div>

        <!-- Groupe A : Vue d'ensemble -->
        <section class="mb-8">
            <h2 class="mb-3 font-sans text-h4 font-bold text-violet-900">
                A. Vue d'ensemble opérationnelle
            </h2>

            <!-- KPI cards rangée -->
            <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div
                    class="flex flex-col items-start gap-1 rounded-[20px] bg-white p-5 shadow-[0_0_8px_rgba(104,23,100,0.10)]"
                >
                    <span class="font-sans text-h1 font-bold text-violet-900">{{
                        totalCollectes
                    }}</span>
                    <span class="font-sans text-small text-violet-700"
                        >Collectes organisées</span
                    >
                </div>
                <div
                    class="flex flex-col items-start gap-1 rounded-[20px] bg-white p-5 shadow-[0_0_8px_rgba(104,23,100,0.10)]"
                >
                    <span class="font-sans text-h1 font-bold text-violet-900">{{
                        totalInscrits.toLocaleString("fr-CH")
                    }}</span>
                    <span class="font-sans text-small text-violet-700"
                        >Inscrits cumulés</span
                    >
                </div>
                <div
                    class="flex flex-col items-start gap-1 rounded-[20px] bg-white p-5 shadow-[0_0_8px_rgba(104,23,100,0.10)]"
                >
                    <span class="font-sans text-h1 font-bold text-violet-900">{{
                        entreprisesDistinctes
                    }}</span>
                    <span class="font-sans text-small text-violet-700"
                        >Entreprises touchées</span
                    >
                </div>
            </div>

            <!-- Grand graphique évolution temporelle (SVG) -->
            <div
                class="overflow-x-auto rounded-[20px] bg-white p-4 shadow-[0_0_8px_rgba(104,23,100,0.10)]"
            >
                <h3
                    class="mb-3 font-sans text-regular font-semibold text-violet-800"
                >
                    Évolution des inscrits par année
                </h3>
                <div
                    v-if="donneesEvolution.length === 0"
                    class="py-8 text-center font-sans text-small text-violet-400"
                >
                    Aucune donnée disponible.
                </div>
                <svg
                    v-else
                    :width="graphiqueW"
                    :height="graphiqueH"
                    class="block"
                    role="img"
                    aria-label="Graphique évolution inscrits"
                >
                    <!-- Axe X -->
                    <line
                        :x1="paddingG"
                        :y1="graphiqueH - paddingB"
                        :x2="graphiqueW"
                        :y2="graphiqueH - paddingB"
                        stroke="#6D276F"
                        stroke-width="1.5"
                    />
                    <!-- Axe Y -->
                    <line
                        :x1="paddingG"
                        y1="5"
                        :x2="paddingG"
                        :y2="graphiqueH - paddingB"
                        stroke="#6D276F"
                        stroke-width="1.5"
                    />
                    <!-- Barres -->
                    <g v-for="b in barres" :key="b.annee">
                        <rect
                            :x="b.x"
                            :y="b.y"
                            :width="b.barre"
                            :height="b.h"
                            fill="rgba(104,23,100,0.70)"
                            rx="3"
                        >
                            <title>
                                {{ b.annee }} : {{ b.inscrits }} inscrits
                            </title>
                        </rect>
                        <!-- Label année -->
                        <text
                            :x="b.x + b.barre / 2"
                            :y="graphiqueH - paddingB + 16"
                            text-anchor="middle"
                            font-family="DM Sans, sans-serif"
                            font-size="11"
                            fill="#361136"
                        >
                            {{ b.annee }}
                        </text>
                        <!-- Valeur au-dessus de la barre -->
                        <text
                            v-if="b.h > 14"
                            :x="b.x + b.barre / 2"
                            :y="b.y - 4"
                            text-anchor="middle"
                            font-family="DM Sans, sans-serif"
                            font-size="11"
                            fill="#681764"
                            font-weight="600"
                        >
                            {{ b.inscrits }}
                        </text>
                    </g>
                    <!-- Label axe Y -->
                    <text
                        x="2"
                        y="14"
                        font-family="DM Sans, sans-serif"
                        font-size="10"
                        fill="#7f7f7f"
                    >
                        Inscrits
                    </text>
                </svg>
            </div>
        </section>

        <!-- Groupe B : Engagement entreprises -->
        <section class="mb-8">
            <h2 class="mb-3 font-sans text-h4 font-bold text-violet-900">
                B. Engagement entreprises
            </h2>
            <div
                class="mb-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4"
            >
                <div
                    class="flex flex-col items-start gap-1 rounded-[20px] bg-white p-5 shadow-[0_0_8px_rgba(104,23,100,0.10)]"
                >
                    <span class="font-sans text-h1 font-bold text-violet-900"
                        >{{ tauxRemplissageMoyen }}&nbsp;%</span
                    >
                    <span class="font-sans text-small text-violet-700"
                        >Taux de remplissage moyen</span
                    >
                    <span class="font-sans text-small italic text-violet-400"
                        >Cible &gt; 75 %</span
                    >
                </div>
                <div
                    class="flex flex-col items-start gap-1 rounded-[20px] bg-white p-5 shadow-[0_0_8px_rgba(104,23,100,0.10)]"
                >
                    <span class="font-sans text-h1 font-bold text-violet-900">{{
                        collectesRecurrentes
                    }}</span>
                    <span class="font-sans text-small text-violet-700"
                        >Entreprises récurrentes (≥&nbsp;2 collectes)</span
                    >
                </div>
                <div
                    class="flex flex-col items-start gap-1 rounded-[20px] bg-white p-5 shadow-[0_0_8px_rgba(104,23,100,0.10)]"
                >
                    <span class="font-sans text-h1 font-bold text-violet-900">{{
                        nbDemandesContact
                    }}</span>
                    <span class="font-sans text-small text-violet-700"
                        >Demandes de collecte reçues</span
                    >
                    <span class="font-sans text-small italic text-violet-400"
                        >Suivi annuel</span
                    >
                </div>
            </div>

            <!-- Top entreprises (tableau) -->
            <div
                class="overflow-x-auto rounded-[20px] bg-white p-4 shadow-[0_0_8px_rgba(104,23,100,0.10)]"
            >
                <h3
                    class="mb-3 font-sans text-regular font-semibold text-violet-800"
                >
                    Top entreprises contributrices
                </h3>
                <table class="w-full font-sans text-small">
                    <thead>
                        <tr
                            class="border-b border-violet-100 text-left text-violet-700"
                        >
                            <th class="pb-2 pr-4">Entreprise</th>
                            <th class="pb-2 pr-4 text-right">Collectes</th>
                            <th class="pb-2 text-right">Inscrits totaux</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(e, i) in topEntreprises"
                            :key="e.nom"
                            class="border-b border-violet-50 last:border-0"
                            :class="i % 2 === 0 ? 'bg-violet-50/30' : ''"
                        >
                            <td class="py-2 pr-4 font-medium text-violet-950">
                                {{ e.nom }}
                            </td>
                            <td class="py-2 pr-4 text-right text-violet-800">
                                {{ e.collectes }}
                            </td>
                            <td class="py-2 text-right text-violet-800">
                                {{ e.inscrits.toLocaleString("fr-CH") }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Groupe C : Performance quiz -->
        <section class="mb-8">
            <h2 class="mb-1 font-sans text-h4 font-bold text-violet-900">
                C. Performance du quiz
            </h2>
            <p class="mb-3 font-sans text-small text-violet-500 italic">
                Données de test, a brancher quand le suivi du quiz sera en
                place.
            </p>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div
                    class="flex flex-col items-start gap-1 rounded-[20px] bg-white p-5 shadow-[0_0_8px_rgba(104,23,100,0.10)]"
                >
                    <span class="font-sans text-h1 font-bold text-violet-900"
                        >{{ kpiQuiz.tauxCompletion }}&nbsp;%</span
                    >
                    <span class="font-sans text-small text-violet-700"
                        >Taux de complétion du quiz</span
                    >
                </div>
                <div
                    class="flex flex-col items-start gap-1 rounded-[20px] bg-white p-5 shadow-[0_0_8px_rgba(104,23,100,0.10)]"
                >
                    <span class="font-sans text-h1 font-bold text-violet-900"
                        >{{ kpiQuiz.tauxEliminationP1 }}&nbsp;%</span
                    >
                    <span class="font-sans text-small text-violet-700"
                        >Taux d'élimination P1</span
                    >
                    <span class="font-sans text-small italic text-violet-400"
                        >Cible &lt; 60 %</span
                    >
                </div>
                <div
                    class="flex flex-col items-start gap-1 rounded-[20px] bg-white p-5 shadow-[0_0_8px_rgba(104,23,100,0.10)]"
                >
                    <span class="font-sans text-h1 font-bold text-violet-900"
                        >{{ kpiQuiz.tauxClicOnedoc }}&nbsp;%</span
                    >
                    <span class="font-sans text-small text-violet-700"
                        >Taux de clic « Prendre RDV »</span
                    >
                    <span class="font-sans text-small italic text-violet-400"
                        >Cible &gt; 40 %</span
                    >
                </div>
            </div>
        </section>

        <!-- Groupe D : Quiz par question -->
        <section class="mb-8">
            <h2 class="mb-1 font-sans text-h4 font-bold text-violet-900">
                D. Performance par question
            </h2>
            <p class="mb-3 font-sans text-small text-violet-500 italic">
                Données de test, a brancher quand le suivi du quiz sera en
                place.
            </p>
            <div
                class="mb-3 rounded-[20px] bg-white p-4 shadow-[0_0_8px_rgba(104,23,100,0.10)]"
            >
                <p
                    class="mb-3 font-sans text-small font-semibold text-violet-700"
                >
                    Principale cause de non-éligibilité :
                    {{ questionPrincipaleNonEligibilite.label }} ({{
                        questionPrincipaleNonEligibilite.mauvaises
                    }}&nbsp;% mauvaises réponses)
                </p>
                <table class="w-full font-sans text-small">
                    <thead>
                        <tr
                            class="border-b border-violet-100 text-left text-violet-700"
                        >
                            <th class="pb-2 pr-3">Question</th>
                            <th class="pb-2 pr-3 text-right">Bonnes %</th>
                            <th class="pb-2 pr-3 text-right">Mauvaises %</th>
                            <th class="pb-2 text-right">Skip %</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(q, i) in questionsQuiz"
                            :key="q.label"
                            class="border-b border-violet-50 last:border-0"
                            :class="i % 2 === 0 ? 'bg-violet-50/30' : ''"
                        >
                            <td class="py-2 pr-3 font-medium text-violet-950">
                                {{ q.label }}
                            </td>
                            <td class="py-2 pr-3 text-right text-vert-600">
                                {{ q.bonnes }}&nbsp;%
                            </td>
                            <td class="py-2 pr-3 text-right text-rouge-600">
                                {{ q.mauvaises }}&nbsp;%
                            </td>
                            <td class="py-2 text-right text-violet-500">
                                {{ q.skip }}&nbsp;%
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Groupe E : Engagement page prévention -->
        <section class="mb-8">
            <h2 class="mb-1 font-sans text-h4 font-bold text-violet-900">
                E. Engagement page prévention
            </h2>
            <p class="mb-3 font-sans text-small text-violet-500 italic">
                Données de test, a brancher quand le suivi sera en place.
            </p>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div
                    class="flex flex-col items-start gap-1 rounded-[20px] bg-white p-5 shadow-[0_0_8px_rgba(104,23,100,0.10)]"
                >
                    <span class="font-sans text-h1 font-bold text-violet-900">{{
                        kpiPrevention.tempsMoyenScrollytelling
                    }}</span>
                    <span class="font-sans text-small text-violet-700"
                        >Temps moyen sur la page scrollytelling</span
                    >
                    <span class="font-sans text-small italic text-violet-400"
                        >Cible &gt; 2 min</span
                    >
                </div>
                <div
                    class="flex flex-col items-start gap-1 rounded-[20px] bg-white p-5 shadow-[0_0_8px_rgba(104,23,100,0.10)]"
                >
                    <span class="font-sans text-h1 font-bold text-violet-900"
                        >{{ kpiPrevention.tauxRebond }}&nbsp;%</span
                    >
                    <span class="font-sans text-small text-violet-700"
                        >Taux de rebond page co-brandée</span
                    >
                    <span class="font-sans text-small italic text-violet-400"
                        >Cible &lt; 70 %</span
                    >
                </div>
            </div>
        </section>
    </DashboardLayout>
</template>
