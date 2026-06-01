<!-- Page Analytics avec KPI et graphiques -->
<script setup>
import { ref, computed } from 'vue'
import DashboardLayout from '../layouts/DashboardLayout.vue'
import { useCollectes }  from '../composables/useCollectes.js'

const props = defineProps({
  allerVers: { type: Function, required: true },
})

const { listeCollectes } = useCollectes()

// Sélecteur de période
const periodeChoisie = ref('annee') // 'mois' | 'trimestre' | 'annee'
const entrepriseFiltre = ref('') // '' = toutes

const entreprisesDisponibles = computed(() => {
  const noms = new Set(listeCollectes.value.map(c => c.entreprise.nom))
  return ['', ...Array.from(noms)]
})

// Filtre les collectes selon la période et l'entreprise sélectionnées
const collectesFiltrees = computed(() => {
  let liste = listeCollectes.value
  if (entrepriseFiltre.value) {
    liste = liste.filter(c => c.entreprise.nom === entrepriseFiltre.value)
  }
  return liste
})

// Groupe A : Vue d'ensemble
const totalCollectes   = computed(() => collectesFiltrees.value.length)
const totalInscrits    = computed(() => collectesFiltrees.value.reduce((s, c) => s + (c.nb_inscrits || 0), 0))
const entreprisesDistinctes = computed(() => new Set(collectesFiltrees.value.map(c => c.entreprise.nom)).size)

// Données évolution temporelle (par année) pour le graphique
const donneesEvolution = computed(() => {
  const parAnnee = {}
  collectesFiltrees.value.forEach(c => {
    const annee = c.date_debut?.slice(0, 4) ?? 'N/A'
    if (!parAnnee[annee]) parAnnee[annee] = { collectes: 0, inscrits: 0 }
    parAnnee[annee].collectes++
    parAnnee[annee].inscrits += c.nb_inscrits || 0
  })
  return Object.entries(parAnnee)
    .sort(([a], [b]) => a.localeCompare(b))
    .map(([annee, v]) => ({ annee, ...v }))
})

// Graphique barres SVG
const graphiqueH    = 180
const graphiqueW    = 520
const paddingG      = 40
const paddingB      = 30
const largeurBarre  = computed(() => {
  const nb = donneesEvolution.value.length
  if (!nb) return 30
  return Math.min(50, Math.floor((graphiqueW - paddingG) / nb - 10))
})
const maxInscrits   = computed(() => Math.max(...donneesEvolution.value.map(d => d.inscrits), 1))
const barres        = computed(() =>
  donneesEvolution.value.map((d, i) => {
    const nb   = donneesEvolution.value.length
    const espa = (graphiqueW - paddingG) / nb
    const x    = paddingG + i * espa + (espa - largeurBarre.value) / 2
    const h    = Math.round((d.inscrits / maxInscrits.value) * (graphiqueH - paddingB - 10))
    const y    = graphiqueH - paddingB - h
    return { ...d, x, y, h, barre: largeurBarre.value }
  })
)

// Groupe B : Engagement entreprises
// Mock : taux de remplissage basé sur nb_inscrits / nb_employes (approx)
const tauxRemplissageMoyen = computed(() => {
  const vals = collectesFiltrees.value.map(c => {
    const cap = Math.min(c.entreprise.nb_employes * 0.1, 200) // 10% des effectifs ou 200 max
    return Math.min(100, Math.round((c.nb_inscrits / cap) * 100))
  })
  if (!vals.length) return 0
  return Math.round(vals.reduce((a, b) => a + b, 0) / vals.length)
})

const collectesRecurrentes = computed(() => {
  const compteur = {}
  collectesFiltrees.value.forEach(c => {
    compteur[c.entreprise.nom] = (compteur[c.entreprise.nom] || 0) + 1
  })
  return Object.values(compteur).filter(v => v > 2).length
})

const topEntreprises = computed(() => {
  const compteur = {}
  collectesFiltrees.value.forEach(c => {
    if (!compteur[c.entreprise.nom]) compteur[c.entreprise.nom] = { inscrits: 0, collectes: 0 }
    compteur[c.entreprise.nom].inscrits  += c.nb_inscrits || 0
    compteur[c.entreprise.nom].collectes += 1
  })
  return Object.entries(compteur)
    .map(([nom, v]) => ({ nom, ...v }))
    .sort((a, b) => b.collectes - a.collectes)
    .slice(0, 5)
})

// Demandes recues depuis le site public (pas de source en base)
const nbDemandesContact = ref(14)

// Groupe C : Performance quiz (pas de table quiz en base pour le moment)
const kpiQuiz = {
  tauxCompletion:      72,
  tauxEliminationP1:   38,
  tauxClicOnedoc:      51,
}

// Groupe D : Quiz par question
const questionsQuiz = [
  { label: 'Q1 – Âge',             bonnes: 88, mauvaises: 12, skip: 2  },
  { label: 'Q2 – Poids',           bonnes: 72, mauvaises: 28, skip: 5  },
  { label: 'Q3 – Santé générale',  bonnes: 65, mauvaises: 35, skip: 12 },
  { label: 'Q4 – Médicaments',     bonnes: 60, mauvaises: 40, skip: 18 },
  { label: 'Q5 – Voyages récents', bonnes: 78, mauvaises: 22, skip: 9  },
]
const questionPrincipaleNonEligibilite = computed(() =>
  questionsQuiz.reduce((max, q) => q.mauvaises > max.mauvaises ? q : max, questionsQuiz[0])
)

// Groupe E : Engagement page prévention
const kpiPrevention = {
  tempsMoyenScrollytelling: '2m 18s',
  tauxRebond:               61,
}
</script>

<template>
  <DashboardLayout vueCourante="analytics" :allerVers="allerVers">

    <h1 class="mb-6 font-sans text-h2 font-bold text-texte-secondary">Analytics</h1>

    <!-- Sélecteurs de filtre -->
    <div class="mb-6 flex flex-wrap items-end gap-4">
      <div class="flex flex-col gap-1">
        <label class="font-sans text-small font-semibold text-violet-950">Période</label>
        <select v-model="periodeChoisie"
          class="rounded-lg bg-white px-3 py-2 font-sans text-small shadow-[0_0_4px_rgba(0,0,0,0.15)] outline-none focus:ring-2 focus:ring-violet-400">
          <option value="mois">Mois</option>
          <option value="trimestre">Trimestre</option>
          <option value="annee">Année</option>
        </select>
      </div>
      <div class="flex flex-col gap-1">
        <label class="font-sans text-small font-semibold text-violet-950">Entreprise</label>
        <select v-model="entrepriseFiltre"
          class="rounded-lg bg-white px-3 py-2 font-sans text-small shadow-[0_0_4px_rgba(0,0,0,0.15)] outline-none focus:ring-2 focus:ring-violet-400">
          <option value="">Toutes</option>
          <option v-for="nom in entreprisesDisponibles.filter(n => n)" :key="nom" :value="nom">{{ nom }}</option>
        </select>
      </div>
    </div>

    <!-- Groupe A : Vue d'ensemble -->
    <section class="mb-8">
      <h2 class="mb-3 font-sans text-h4 font-bold text-violet-900">A. Vue d'ensemble opérationnelle</h2>

      <!-- KPI cards rangée -->
      <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="flex flex-col items-start gap-1 rounded-[20px] bg-white p-5 shadow-[0_0_8px_rgba(104,23,100,0.10)]">
          <span class="font-sans text-h1 font-bold text-violet-900">{{ totalCollectes }}</span>
          <span class="font-sans text-small text-violet-700">Collectes organisées</span>
        </div>
        <div class="flex flex-col items-start gap-1 rounded-[20px] bg-white p-5 shadow-[0_0_8px_rgba(104,23,100,0.10)]">
          <span class="font-sans text-h1 font-bold text-violet-900">{{ totalInscrits.toLocaleString('fr-CH') }}</span>
          <span class="font-sans text-small text-violet-700">Inscrits cumulés</span>
        </div>
        <div class="flex flex-col items-start gap-1 rounded-[20px] bg-white p-5 shadow-[0_0_8px_rgba(104,23,100,0.10)]">
          <span class="font-sans text-h1 font-bold text-violet-900">{{ entreprisesDistinctes }}</span>
          <span class="font-sans text-small text-violet-700">Entreprises touchées</span>
        </div>
      </div>

      <!-- Grand graphique évolution temporelle (SVG) -->
      <div class="overflow-x-auto rounded-[20px] bg-white p-4 shadow-[0_0_8px_rgba(104,23,100,0.10)]">
        <h3 class="mb-3 font-sans text-regular font-semibold text-violet-800">
          Évolution des inscrits par année
        </h3>
        <div v-if="donneesEvolution.length === 0" class="py-8 text-center font-sans text-small text-violet-400">
          Aucune donnée disponible.
        </div>
        <svg v-else :width="graphiqueW" :height="graphiqueH" class="block" role="img" aria-label="Graphique évolution inscrits">
          <!-- Axe X -->
          <line :x1="paddingG" :y1="graphiqueH - paddingB" :x2="graphiqueW" :y2="graphiqueH - paddingB"
            stroke="#6D276F" stroke-width="1.5" />
          <!-- Axe Y -->
          <line :x1="paddingG" y1="5" :x2="paddingG" :y2="graphiqueH - paddingB"
            stroke="#6D276F" stroke-width="1.5" />
          <!-- Barres -->
          <g v-for="b in barres" :key="b.annee">
            <rect
              :x="b.x" :y="b.y"
              :width="b.barre" :height="b.h"
              fill="rgba(104,23,100,0.70)"
              rx="3"
            >
              <title>{{ b.annee }} : {{ b.inscrits }} inscrits</title>
            </rect>
            <!-- Label année -->
            <text
              :x="b.x + b.barre / 2"
              :y="graphiqueH - paddingB + 16"
              text-anchor="middle"
              font-family="DM Sans, sans-serif"
              font-size="11"
              fill="#361136"
            >{{ b.annee }}</text>
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
            >{{ b.inscrits }}</text>
          </g>
          <!-- Label axe Y -->
          <text x="2" y="14" font-family="DM Sans, sans-serif" font-size="10" fill="#7f7f7f">
            Inscrits
          </text>
        </svg>
      </div>
    </section>

    <!-- Groupe B : Engagement entreprises -->
    <section class="mb-8">
      <h2 class="mb-3 font-sans text-h4 font-bold text-violet-900">B. Engagement entreprises</h2>
      <div class="mb-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="flex flex-col items-start gap-1 rounded-[20px] bg-white p-5 shadow-[0_0_8px_rgba(104,23,100,0.10)]">
          <span class="font-sans text-h1 font-bold text-violet-900">{{ tauxRemplissageMoyen }}&nbsp;%</span>
          <span class="font-sans text-small text-violet-700">Taux de remplissage moyen</span>
          <span class="font-sans text-small italic text-violet-400">Cible &gt; 75 %</span>
        </div>
        <div class="flex flex-col items-start gap-1 rounded-[20px] bg-white p-5 shadow-[0_0_8px_rgba(104,23,100,0.10)]">
          <span class="font-sans text-h1 font-bold text-violet-900">{{ collectesRecurrentes }}</span>
          <span class="font-sans text-small text-violet-700">Entreprises récurrentes (&gt;2 collectes)</span>
        </div>
        <div class="flex flex-col items-start gap-1 rounded-[20px] bg-white p-5 shadow-[0_0_8px_rgba(104,23,100,0.10)]">
          <span class="font-sans text-h1 font-bold text-violet-900">{{ nbDemandesContact }}</span>
          <span class="font-sans text-small text-violet-700">Demandes de collecte reçues</span>
          <span class="font-sans text-small italic text-violet-400">Suivi annuel</span>
        </div>
      </div>

      <!-- Top entreprises (tableau) -->
      <div class="overflow-x-auto rounded-[20px] bg-white p-4 shadow-[0_0_8px_rgba(104,23,100,0.10)]">
        <h3 class="mb-3 font-sans text-regular font-semibold text-violet-800">Top entreprises contributrices</h3>
        <table class="w-full font-sans text-small">
          <thead>
            <tr class="border-b border-violet-100 text-left text-violet-700">
              <th class="pb-2 pr-4">Entreprise</th>
              <th class="pb-2 pr-4 text-right">Collectes</th>
              <th class="pb-2 text-right">Inscrits totaux</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(e, i) in topEntreprises" :key="e.nom"
              class="border-b border-violet-50 last:border-0"
              :class="i % 2 === 0 ? 'bg-violet-50/30' : ''">
              <td class="py-2 pr-4 font-medium text-violet-950">{{ e.nom }}</td>
              <td class="py-2 pr-4 text-right text-violet-800">{{ e.collectes }}</td>
              <td class="py-2 text-right text-violet-800">{{ e.inscrits.toLocaleString('fr-CH') }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- Groupe C : Performance quiz -->
    <section class="mb-8">
      <h2 class="mb-1 font-sans text-h4 font-bold text-violet-900">C. Performance du quiz</h2>
      <p class="mb-3 font-sans text-small text-violet-500 italic">
        Données de test, a brancher quand le suivi du quiz sera en place.
      </p>
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="flex flex-col items-start gap-1 rounded-[20px] bg-white p-5 shadow-[0_0_8px_rgba(104,23,100,0.10)]">
          <span class="font-sans text-h1 font-bold text-violet-900">{{ kpiQuiz.tauxCompletion }}&nbsp;%</span>
          <span class="font-sans text-small text-violet-700">Taux de complétion du quiz</span>
        </div>
        <div class="flex flex-col items-start gap-1 rounded-[20px] bg-white p-5 shadow-[0_0_8px_rgba(104,23,100,0.10)]">
          <span class="font-sans text-h1 font-bold text-violet-900">{{ kpiQuiz.tauxEliminationP1 }}&nbsp;%</span>
          <span class="font-sans text-small text-violet-700">Taux d'élimination P1</span>
          <span class="font-sans text-small italic text-violet-400">Cible &lt; 60 %</span>
        </div>
        <div class="flex flex-col items-start gap-1 rounded-[20px] bg-white p-5 shadow-[0_0_8px_rgba(104,23,100,0.10)]">
          <span class="font-sans text-h1 font-bold text-violet-900">{{ kpiQuiz.tauxClicOnedoc }}&nbsp;%</span>
          <span class="font-sans text-small text-violet-700">Taux de clic « Prendre RDV »</span>
          <span class="font-sans text-small italic text-violet-400">Cible &gt; 40 %</span>
        </div>
      </div>
    </section>

    <!-- Groupe D : Quiz par question -->
    <section class="mb-8">
      <h2 class="mb-1 font-sans text-h4 font-bold text-violet-900">D. Performance par question</h2>
      <p class="mb-3 font-sans text-small text-violet-500 italic">
        Données de test, a brancher quand le suivi du quiz sera en place.
      </p>
      <div class="mb-3 rounded-[20px] bg-white p-4 shadow-[0_0_8px_rgba(104,23,100,0.10)]">
        <p class="mb-3 font-sans text-small font-semibold text-violet-700">
          Principale cause de non-éligibilité : {{ questionPrincipaleNonEligibilite.label }}
          ({{ questionPrincipaleNonEligibilite.mauvaises }}&nbsp;% mauvaises réponses)
        </p>
        <table class="w-full font-sans text-small">
          <thead>
            <tr class="border-b border-violet-100 text-left text-violet-700">
              <th class="pb-2 pr-3">Question</th>
              <th class="pb-2 pr-3 text-right">Bonnes %</th>
              <th class="pb-2 pr-3 text-right">Mauvaises %</th>
              <th class="pb-2 text-right">Skip %</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(q, i) in questionsQuiz" :key="q.label"
              class="border-b border-violet-50 last:border-0"
              :class="i % 2 === 0 ? 'bg-violet-50/30' : ''">
              <td class="py-2 pr-3 font-medium text-violet-950">{{ q.label }}</td>
              <td class="py-2 pr-3 text-right text-vert-600">{{ q.bonnes }}&nbsp;%</td>
              <td class="py-2 pr-3 text-right text-rouge-600">{{ q.mauvaises }}&nbsp;%</td>
              <td class="py-2 text-right text-violet-500">{{ q.skip }}&nbsp;%</td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- Groupe E : Engagement page prévention -->
    <section class="mb-8">
      <h2 class="mb-1 font-sans text-h4 font-bold text-violet-900">E. Engagement page prévention</h2>
      <p class="mb-3 font-sans text-small text-violet-500 italic">
        Données de test, a brancher quand le suivi sera en place.
      </p>
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="flex flex-col items-start gap-1 rounded-[20px] bg-white p-5 shadow-[0_0_8px_rgba(104,23,100,0.10)]">
          <span class="font-sans text-h1 font-bold text-violet-900">{{ kpiPrevention.tempsMoyenScrollytelling }}</span>
          <span class="font-sans text-small text-violet-700">Temps moyen sur la page scrollytelling</span>
          <span class="font-sans text-small italic text-violet-400">Cible &gt; 2 min</span>
        </div>
        <div class="flex flex-col items-start gap-1 rounded-[20px] bg-white p-5 shadow-[0_0_8px_rgba(104,23,100,0.10)]">
          <span class="font-sans text-h1 font-bold text-violet-900">{{ kpiPrevention.tauxRebond }}&nbsp;%</span>
          <span class="font-sans text-small text-violet-700">Taux de rebond page co-brandée</span>
          <span class="font-sans text-small italic text-violet-400">Cible &lt; 70 %</span>
        </div>
      </div>
    </section>



  </DashboardLayout>
</template>

