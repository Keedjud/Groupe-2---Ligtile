<!-- Vue liste des collectes avec grille de tuiles -->
<script setup>
import { onMounted } from 'vue'
import DashboardLayout from '../layouts/DashboardLayout.vue'
import CollecteCard    from '../components/CollecteCard.vue'
import { useCollectes } from '../composables/useCollectes.js'

const props = defineProps({
  allerVers: { type: Function, required: true },
})

const { listeCollectes, chargement, erreur, chargerCollectes } = useCollectes()

onMounted(() => { chargerCollectes() })
</script>

<template>
  <DashboardLayout vueCourante="collectes" :allerVers="allerVers">

    <!-- En-tête de section -->
    <div class="mb-6 flex items-center justify-between">
      <h1 class="font-sans text-h2 font-bold text-texte-secondary">Collectes</h1>
    </div>

    <!-- État chargement -->
    <div v-if="chargement" class="flex items-center justify-center py-16">
      <span class="font-sans text-regular text-violet-700">Chargement…</span>
    </div>

    <!-- Erreur -->
    <div v-else-if="erreur" class="rounded-lg bg-rouge-50 px-4 py-3 font-sans text-regular text-rouge-700">
      Une erreur est survenue lors du chargement des collectes.
    </div>

    <!-- Liste vide -->
    <div v-else-if="listeCollectes.length === 0" class="flex flex-col items-center gap-4 py-16 text-violet-600">
      <span class="font-sans text-h4">Aucune collecte pour l'instant.</span>
      <button
        @click="allerVers('#/collectes/nouvelle')"
        class="rounded-[40px] bg-violet-900 px-6 py-2.5 font-sans text-regular text-white hover:opacity-90 transition-opacity"
      >Créer la première collecte</button>
    </div>

    <!-- Grille de cartes -->
    <div v-else class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
      <CollecteCard
        v-for="collecte in listeCollectes"
        :key="collecte.id"
        :collecte="collecte"
        :allerVers="allerVers"
      />
    </div>

    <!-- Bouton flottant "Ajouter une collecte" -->
    <div class="fixed bottom-20 right-8 z-50">
      <button
        @click="allerVers('#/collectes/nouvelle')"
        class="flex items-center gap-2 rounded-[40px] bg-violet-900 px-6 py-3 font-sans text-regular font-bold text-white shadow-[0_4px_16px_rgba(104,23,100,0.30)] transition-all hover:scale-105 hover:bg-violet-800"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Créer une collecte
      </button>
    </div>

  </DashboardLayout>
</template>
