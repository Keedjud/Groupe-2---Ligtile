<!-- CollecteCard -->
<script setup>
defineProps({
  collecte:  { type: Object, required: true },
  allerVers: { type: Function, required: true },
})

// Format suisse jj.mm.aaaa
function formaterDate(dateStr) {
  if (!dateStr) return ''
  return new Date(dateStr).toLocaleDateString('fr-CH', {
    day: '2-digit', month: '2-digit', year: 'numeric',
  })
}
</script>

<template>
  <div class="flex flex-col items-center gap-[22px] rounded-[10px] bg-violet-50 p-2.5 shadow-[0_4px_4px_1px_rgba(104,23,100,0.30)]">

    <!-- Logo + Badge goutte -->
    <div class="flex w-full items-center justify-between px-1 py-1">
      <!-- Logo entreprise -->
      <div class="flex h-[42px] w-[75px] items-center justify-start overflow-hidden">
        <img
          v-if="collecte.logo_url"
          :src="collecte.logo_url"
          :alt="collecte.entreprise.nom"
          class="max-h-full max-w-full object-contain"
        />
        <div
          v-else
          class="flex h-10 w-16 items-center justify-center rounded bg-violet-100 text-xs font-semibold text-violet-700"
        >
          {{ collecte.entreprise.nom.slice(0, 3).toUpperCase() }}
        </div>
      </div>
      <!-- Badge label CTS -->
      <img
        :src="'/images/label_empty.png'"
        alt="Label CTS vide"
        class="h-[60px] w-[57px] shrink-0 object-contain"
      />
    </div>

    <!-- Dates -->
    <div class="flex flex-col items-start gap-2 rounded-[10px]">
      <!-- Date début -->
      <div class="flex items-center gap-2.5">
        <div class="flex h-6 w-6 items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-[18px] w-[18px] text-vert-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
            <line x1="16" y1="2" x2="16" y2="6" />
            <line x1="8" y1="2" x2="8" y2="6" />
            <line x1="3" y1="10" x2="21" y2="10" />
          </svg>
        </div>
        <span class="font-sans text-regular text-violet-950">Date de début</span>
        <span class="font-sans text-regular text-violet-950">{{ formaterDate(collecte.date_debut) }}</span>
      </div>
      <!-- Date fin -->
      <div class="flex items-center gap-2.5">
        <div class="flex h-6 w-6 items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-[18px] w-[18px] text-vert-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
            <line x1="16" y1="2" x2="16" y2="6" />
            <line x1="8" y1="2" x2="8" y2="6" />
            <line x1="3" y1="10" x2="21" y2="10" />
          </svg>
        </div>
        <span class="font-sans text-regular text-violet-950">Date de fin</span>
        <span class="font-sans text-regular text-violet-950">{{ formaterDate(collecte.date_fin) }}</span>
      </div>
    </div>

    <!-- Bouton voir -->
    <button
      @click="allerVers('#/collectes/' + collecte.id)"
      class="h-11 w-[166px] rounded-[40px] bg-white font-sans text-regular text-texte-secondary underline shadow-[0_0_4px_rgba(0,0,0,0.25)] transition-colors hover:bg-violet-50"
    >
      Voir la collecte
    </button>
  </div>
</template>
