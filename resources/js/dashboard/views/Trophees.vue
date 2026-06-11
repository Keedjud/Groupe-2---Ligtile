<!-- Trophees — liste des podiums par année + formulaire création/édition -->
<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import DashboardLayout from '../layouts/DashboardLayout.vue'
import { useTrophees } from '../composables/useTrophees.js'
import { useCompanies } from '../composables/useCompanies.js'

defineProps({
  allerVers: { type: Function, required: true },
})

const { listePodiums, chargement, erreur, chargerPodiums, creerPodium, mettreAJourPodium, supprimerPodium } = useTrophees()
const { listeEntreprises, chargerEntreprises } = useCompanies()

const RANGS = [
  { rang: 1, label: 'Or',     classes: 'bg-yellow-50 text-yellow-800' },
  { rang: 2, label: 'Argent', classes: 'bg-gray-100 text-gray-600'    },
  { rang: 3, label: 'Bronze', classes: 'bg-orange-50 text-orange-700' },
]

// État formulaire
const formulaireVisible = ref(false)
const modeEdition = ref(false)
const anneeEdition = ref(null)
const champAnnee = ref('')
const champsRangs = ref(RANGS.map(() => ({ texte: '', idSelectionne: null, ouvert: false })))
const erreurFormulaire = ref({})
const erreurServeur = ref('')

// Confirmation suppression : stocke l'année en attente
const confirmeSuppression = ref(null)

onMounted(async () => {
  await chargerPodiums()
  await chargerEntreprises()
})

// Autocomplete : filtre côté client, 20 résultats max
// Seules les entreprises participant aux trophées ET ayant au moins une collecte sont éligibles.
const entreprisesEligibles = computed(() =>
  listeEntreprises.value.filter(e => e.participe_trophee && e.nb_collectes >= 1)
)

const suggestionsParRang = computed(() =>
  champsRangs.value.map(champ => {
    const q = champ.texte.trim().toLowerCase()
    if (!q) return entreprisesEligibles.value.slice(0, 20)
    return entreprisesEligibles.value.filter(e => e.nom.toLowerCase().includes(q)).slice(0, 20)
  })
)

function ouvrirCreation() {
  formulaireVisible.value = true
  modeEdition.value = false
  anneeEdition.value = null
  champAnnee.value = ''
  champsRangs.value = RANGS.map(() => ({ texte: '', idSelectionne: null, ouvert: false }))
  erreurFormulaire.value = {}
  erreurServeur.value = ''
}

function ouvrirEdition(podium) {
  formulaireVisible.value = true
  modeEdition.value = true
  anneeEdition.value = podium.year
  champAnnee.value = String(podium.year)
  champsRangs.value = RANGS.map(r => {
    const t = podium.trophees.find(t => t.rank === r.rang)
    return {
      texte:         t?.company?.name || '',
      idSelectionne: t?.company?.id   || null,
      ouvert:        false,
    }
  })
  erreurFormulaire.value = {}
  erreurServeur.value = ''
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

function annuler() {
  formulaireVisible.value = false
}

function selectionnerEntreprise(rang, entreprise) {
  const i = rang - 1
  champsRangs.value[i].texte         = entreprise.nom
  champsRangs.value[i].idSelectionne = entreprise.id
  champsRangs.value[i].ouvert        = false
}

function fermerDropdown(rang) {
  setTimeout(() => { champsRangs.value[rang - 1].ouvert = false }, 150)
}

const aDesErreurs = computed(() => Object.keys(erreurFormulaire.value).length > 0)

function valider() {
  const e = {}
  const annee = Number(champAnnee.value)

  if (!champAnnee.value || isNaN(annee) || annee < 2000 || annee > 2100) {
    e.annee = 'Année invalide (entre 2000 et 2100).'
  } else if (!modeEdition.value && listePodiums.value.some(p => p.year === annee)) {
    e.annee = `Un podium existe déjà pour ${annee}. Utilisez le bouton "Modifier" sur la ligne correspondante.`
  }

  RANGS.forEach((r, i) => {
    if (!champsRangs.value[i].idSelectionne) {
      e[`rang${r.rang}`] = `Sélectionnez une entreprise pour la place ${r.label}.`
    }
  })

  const ids = champsRangs.value.map(c => c.idSelectionne).filter(Boolean)
  if (ids.length === 3 && new Set(ids).size < 3) {
    e.doublons = 'Les trois entreprises doivent être différentes.'
  }

  erreurFormulaire.value = e
  return Object.keys(e).length === 0
}

// Re-validation réactive après une 1re tentative : retire le surlignage dès qu'un champ est corrigé.
watch(
  () => [champAnnee.value, ...champsRangs.value.map(c => c.idSelectionne)],
  () => { if (Object.keys(erreurFormulaire.value).length > 0) valider() },
)

async function sauvegarder() {
  erreurServeur.value = ''
  if (!valider()) return

  const ranks = RANGS.map((r, i) => ({
    rank:       r.rang,
    company_id: champsRangs.value[i].idSelectionne,
  }))

  try {
    if (modeEdition.value) {
      await mettreAJourPodium(anneeEdition.value, ranks)
    } else {
      await creerPodium(Number(champAnnee.value), ranks)
    }
    formulaireVisible.value = false
  } catch (err) {
    erreurServeur.value = err?.data?.message || 'Une erreur est survenue.'
  }
}

async function gererSuppression(annee) {
  if (confirmeSuppression.value !== annee) {
    confirmeSuppression.value = annee
    return
  }
  try {
    await supprimerPodium(annee)
  } finally {
    confirmeSuppression.value = null
  }
}

function podiumParRang(podium, rang) {
  return podium.trophees.find(t => t.rank === rang)?.company?.name || '—'
}
</script>

<template>
  <DashboardLayout vueCourante="trophees" :allerVers="allerVers">

    <!-- En-tête -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
      <h1 class="font-sans text-h2 font-bold text-texte-secondary">Trophées</h1>
      <button
        v-if="!formulaireVisible"
        @click="ouvrirCreation"
        class="rounded-[40px] bg-violet-900 px-5 py-2 font-sans text-small text-white hover:opacity-90 transition-opacity"
      >+ Nouveau podium</button>
    </div>

    <!-- Formulaire création / édition -->
    <div v-if="formulaireVisible" class="mb-6 rounded-[20px] bg-form-bg p-6 shadow-[0_4px_16px_rgba(104,23,100,0.10)]">
      <h2 class="mb-4 font-sans text-h4 font-semibold text-violet-800">
        {{ modeEdition ? `Modifier le podium ${anneeEdition}` : 'Nouveau podium' }}
      </h2>

      <!-- Bandeau erreurs -->
      <div
        v-if="aDesErreurs || erreurServeur"
        class="mb-4 rounded-xl border border-rouge-500 bg-rouge-500/10 p-4 font-sans text-small text-rouge-600"
      >
        <p class="font-semibold">Merci de corriger les points suivants :</p>
        <ul class="mt-1 list-disc pl-4">
          <li v-for="(msg, champ) in erreurFormulaire" :key="champ">{{ msg }}</li>
          <li v-if="erreurServeur">{{ erreurServeur }}</li>
        </ul>
      </div>

      <form @submit.prevent="sauvegarder" class="flex flex-col gap-5">

        <!-- Année -->
        <div class="flex flex-col gap-1 sm:w-36">
          <label for="annee" class="font-sans text-small font-medium text-violet-800">Année</label>
          <input
            id="annee"
            v-model="champAnnee"
            type="number"
            min="2000"
            max="2100"
            :disabled="modeEdition"
            class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400 disabled:opacity-50"
            :class="{ 'ring-rouge-500 ring-1': erreurFormulaire.annee }"
          />
        </div>

        <!-- 3 champs entreprise avec autocomplete -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
          <div v-for="(r, i) in RANGS" :key="r.rang" class="flex flex-col gap-1">
            <label class="font-sans text-small font-medium text-violet-800">
              <span
                class="mr-1 inline-block rounded-full px-2 py-0.5 text-xs font-semibold"
                :class="r.classes"
              >{{ r.label }}</span>
              Entreprise
            </label>
            <div class="relative">
              <input
                v-model="champsRangs[i].texte"
                @input="champsRangs[i].idSelectionne = null; champsRangs[i].ouvert = true"
                @focus="champsRangs[i].ouvert = true"
                @blur="fermerDropdown(r.rang)"
                type="text"
                placeholder="Chercher…"
                autocomplete="off"
                class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400"
                :class="{ 'ring-rouge-500 ring-1': erreurFormulaire[`rang${r.rang}`] }"
              />
              <ul
                v-show="champsRangs[i].ouvert && suggestionsParRang[i].length"
                class="absolute z-20 mt-1 max-h-48 w-full overflow-y-auto rounded-lg bg-white shadow-[0_4px_12px_rgba(0,0,0,0.15)]"
              >
                <li
                  v-for="e in suggestionsParRang[i]"
                  :key="e.id"
                  @mousedown.prevent="selectionnerEntreprise(r.rang, e)"
                  class="cursor-pointer px-3 py-2 font-sans text-small text-violet-950 hover:bg-violet-50"
                >{{ e.nom }}</li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="mt-2 flex justify-end gap-3">
          <button
            type="button"
            @click="annuler"
            class="rounded-[40px] bg-white px-6 py-2.5 font-sans text-regular text-texte-secondary shadow-[0_0_4px_rgba(0,0,0,0.15)] hover:bg-violet-50 transition-colors"
          >Annuler</button>
          <button
            type="submit"
            :disabled="chargement"
            class="rounded-[40px] bg-violet-900 px-8 py-2.5 font-sans text-regular text-white hover:opacity-90 transition-opacity disabled:opacity-60 disabled:cursor-wait"
          >{{ chargement ? 'Enregistrement…' : 'Enregistrer' }}</button>
        </div>

      </form>
    </div>

    <!-- Chargement initial -->
    <div v-if="chargement && !listePodiums.length" class="flex items-center justify-center py-16">
      <span class="font-sans text-regular text-violet-400">Chargement…</span>
    </div>

    <!-- Erreur -->
    <div
      v-else-if="erreur"
      class="rounded-xl border border-rouge-500 bg-rouge-500/10 p-4 font-sans text-small text-rouge-600"
    >{{ erreur }}</div>

    <!-- Aucun podium -->
    <div v-else-if="!listePodiums.length && !chargement" class="flex items-center justify-center py-16">
      <p class="font-sans text-regular text-violet-400">Aucun podium enregistré. Créez le premier !</p>
    </div>

    <!-- Cartes mobile / tableau desktop -->
    <template v-else>
    <div class="grid gap-3 sm:hidden">
      <div
        v-for="podium in listePodiums"
        :key="podium.year"
        class="rounded-[20px] bg-white p-4 shadow-[0_0_8px_rgba(104,23,100,0.10)]"
      >
        <div class="flex items-start justify-between gap-3">
          <div>
            <p class="font-sans text-xs uppercase tracking-wide text-violet-400">Année</p>
            <p class="mt-1 font-sans text-regular font-semibold text-violet-950">{{ podium.year }}</p>
          </div>
          <div class="flex flex-col items-end gap-2">
            <button
              @click="ouvrirEdition(podium)"
              class="rounded-[40px] bg-white px-3 py-1.5 font-sans text-xs text-texte-secondary shadow-[0_0_4px_rgba(0,0,0,0.15)] hover:bg-violet-50 transition-colors"
            >Modifier</button>
            <button
              @click="gererSuppression(podium.year)"
              :disabled="chargement"
              class="rounded-[40px] px-3 py-1.5 font-sans text-xs text-white transition-opacity hover:opacity-90 disabled:opacity-60"
              :class="confirmeSuppression === podium.year ? 'bg-rouge-700' : 'bg-rouge-500'"
            >{{ confirmeSuppression === podium.year ? 'Confirmer' : 'Supprimer' }}</button>
          </div>
        </div>
        <div class="mt-3 grid grid-cols-1 gap-2 text-small text-violet-800">
          <p><span class="font-semibold text-violet-500">Or:</span> {{ podiumParRang(podium, 1) }}</p>
          <p><span class="font-semibold text-violet-500">Argent:</span> {{ podiumParRang(podium, 2) }}</p>
          <p><span class="font-semibold text-violet-500">Bronze:</span> {{ podiumParRang(podium, 3) }}</p>
        </div>
      </div>
    </div>

    <!-- Tableau desktop -->
    <div class="hidden sm:block overflow-x-auto rounded-[20px] bg-white shadow-[0_0_8px_rgba(104,23,100,0.10)]">
      <table class="w-full font-sans text-small">
        <thead>
          <tr class="border-b border-violet-100 text-left text-violet-700">
            <th class="px-5 py-3 font-semibold">Année</th>
            <th class="px-5 py-3 font-semibold">Or</th>
            <th class="px-5 py-3 font-semibold hidden sm:table-cell">Argent</th>
            <th class="px-5 py-3 font-semibold hidden md:table-cell">Bronze</th>
            <th class="px-5 py-3"></th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(podium, i) in listePodiums"
            :key="podium.year"
            class="border-b border-violet-50 last:border-0"
            :class="i % 2 === 0 ? '' : 'bg-violet-50/20'"
          >
            <td class="px-5 py-3 font-semibold text-violet-950">{{ podium.year }}</td>
            <td class="px-5 py-3 text-violet-800">{{ podiumParRang(podium, 1) }}</td>
            <td class="px-5 py-3 text-violet-600 hidden sm:table-cell">{{ podiumParRang(podium, 2) }}</td>
            <td class="px-5 py-3 text-violet-500 hidden md:table-cell">{{ podiumParRang(podium, 3) }}</td>
            <td class="px-5 py-3 text-right">
              <div class="flex items-center justify-end gap-2">
                <button
                  @click="ouvrirEdition(podium)"
                  class="rounded-[40px] bg-white px-3 py-1.5 font-sans text-xs text-texte-secondary shadow-[0_0_4px_rgba(0,0,0,0.15)] hover:bg-violet-50 transition-colors"
                >Modifier</button>
                <button
                  @click="gererSuppression(podium.year)"
                  :disabled="chargement"
                  class="rounded-[40px] px-3 py-1.5 font-sans text-xs text-white transition-opacity hover:opacity-90 disabled:opacity-60"
                  :class="confirmeSuppression === podium.year ? 'bg-rouge-700' : 'bg-rouge-500'"
                >{{ confirmeSuppression === podium.year ? 'Confirmer' : 'Supprimer' }}</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    </template>

  </DashboardLayout>
</template>
