<!-- Companies — liste des entreprises avec recherche -->
<script setup>
import { ref, onMounted } from 'vue'
import DashboardLayout from '../layouts/DashboardLayout.vue'
import { useCompanies } from '../composables/useCompanies.js'

defineProps({
  allerVers: { type: Function, required: true },
})

const { listeEntreprises, chargement, erreur, chargerEntreprises } = useCompanies()

const recherche = ref('')
let debounceTimer = null

function surRecherche() {
  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(() => chargerEntreprises(recherche.value), 300)
}

onMounted(() => chargerEntreprises())
</script>

<template>
  <DashboardLayout vueCourante="entreprises" :allerVers="allerVers">

    <!-- En-tête -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
      <h1 class="font-sans text-h2 font-bold text-texte-secondary">Entreprises</h1>

      <div class="flex items-center gap-3">
        <!-- Bouton création -->
        <button
          @click="allerVers('#/entreprises/nouvelle')"
          class="rounded-[40px] bg-violet-900 px-5 py-2 font-sans text-small text-white hover:opacity-90 transition-opacity"
        >+ Nouvelle entreprise</button>

        <!-- Recherche -->
        <div class="relative">
          <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
          </svg>
          <input
            v-model="recherche"
            @input="surRecherche"
            type="search"
            placeholder="Rechercher une entreprise…"
            class="rounded-[40px] bg-white py-2 pl-9 pr-4 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.15)] outline-none focus:ring-2 focus:ring-violet-400 w-64"
          />
        </div>
      </div>
    </div>

    <!-- Chargement -->
    <div v-if="chargement" class="flex items-center justify-center py-16">
      <span class="font-sans text-regular text-violet-400">Chargement…</span>
    </div>

    <!-- Erreur -->
    <div v-else-if="erreur" class="rounded-xl border border-rouge-500 bg-rouge-500/10 p-4 font-sans text-small text-rouge-600">
      {{ erreur }}
    </div>

    <!-- Aucun résultat -->
    <div v-else-if="!listeEntreprises.length" class="flex items-center justify-center py-16">
      <p class="font-sans text-regular text-violet-400">
        {{ recherche ? 'Aucune entreprise ne correspond à la recherche.' : 'Aucune entreprise enregistrée.' }}
      </p>
    </div>

    <!-- Tableau -->
    <div v-else>
      <p class="mb-2 font-sans text-small italic text-violet-400">Cliquez sur une ligne pour accéder au détail de l'entreprise.</p>
      <div class="overflow-x-auto rounded-[20px] bg-white shadow-[0_0_8px_rgba(104,23,100,0.10)]">
        <table class="w-full font-sans text-small">
          <thead>
            <tr class="border-b border-violet-100 text-left text-violet-700">
              <th class="px-5 py-3 font-semibold">Entreprise</th>
              <th class="px-5 py-3 font-semibold hidden sm:table-cell">Taille</th>
              <th class="px-5 py-3 font-semibold text-right">Collectes</th>
              <th class="px-5 py-3 font-semibold hidden md:table-cell">Contact</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(e, i) in listeEntreprises"
              :key="e.id"
              class="border-b border-violet-50 last:border-0 transition-colors hover:bg-violet-50/40 cursor-pointer"
              :class="i % 2 === 0 ? '' : 'bg-violet-50/20'"
              @click="allerVers('#/entreprises/' + e.id)"
            >
              <td class="px-5 py-3 font-medium text-violet-950">{{ e.nom }}</td>
              <td class="px-5 py-3 text-violet-600 hidden sm:table-cell">{{ e.taille }}</td>
              <td class="px-5 py-3 text-right text-violet-800 font-semibold">{{ e.nb_collectes }}</td>
              <td class="px-5 py-3 text-violet-500 hidden md:table-cell">{{ e.contact.email || '—' }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </DashboardLayout>
</template>
