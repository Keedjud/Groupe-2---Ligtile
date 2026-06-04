<!-- Navigation du dashboard : sidebar gauche sur desktop, barre horizontale sur mobile -->
<script setup>
import { useSessionAuth } from '../composables/useSessionAuth.js'

const props = defineProps({
  // clé de la vue active : 'collectes' | 'analytics'
  vueCourante: { type: String, default: 'collectes' },
  allerVers:   { type: Function, required: true },
})

const { deconnecter } = useSessionAuth()

function gererLogout() {
  deconnecter()
  props.allerVers('#/connexion')
}
</script>

<template>
  <!-- Sidebar desktop -->
  <aside class="hidden h-full w-[215px] shrink-0 flex-col gap-4 overflow-hidden px-[30px] py-8 lg:flex">
    <!-- Logo HUG -->
    <img :src="'/images/logos/logo-hug.png'" alt="logo HUG" class="mb-6 h-auto w-full max-w-[155px]" />

    <!-- Collectes -->
    <button
      @click="allerVers('#/collectes')"
      class="w-full rounded-[40px] px-6 py-2.5 font-sans text-regular transition-all"
      :class="vueCourante === 'collectes'
        ? 'bg-violet-950 font-semibold text-beige-50 underline shadow-[0_0_4px_rgba(0,0,0,0.25)]'
        : 'bg-white text-texte-secondary shadow-[0_0_4px_rgba(0,0,0,0.25)] hover:bg-violet-50'"
    >
      Collectes
    </button>

    <!-- Analytics -->
    <button
      @click="allerVers('#/analytics')"
      class="w-full rounded-[40px] px-6 py-2.5 font-sans text-regular transition-all"
      :class="vueCourante === 'analytics'
        ? 'bg-violet-950 font-semibold text-beige-50 underline shadow-[0_0_4px_rgba(0,0,0,0.25)]'
        : 'bg-white text-texte-secondary shadow-[0_0_4px_rgba(0,0,0,0.25)] hover:bg-violet-50'"
    >
      Analytics
    </button>

    <!-- Spacer -->
    <div class="flex-1" />

    <!-- Logout -->
    <button
      @click="gererLogout"
      class="w-full rounded-[40px] bg-rouge-500 px-6 py-2.5 font-sans text-regular text-white transition-opacity hover:opacity-90"
    >
      Logout
    </button>
  </aside>

  <!-- Barre mobile -->
  <nav class="flex items-center justify-between gap-2 border-b border-violet-200 bg-beige-50 px-4 py-2 lg:hidden">
    <img :src="'/images/logos/logo-hug.png'" alt="logo HUG" class="h-7 w-auto" />
    <div class="flex gap-2 flex-wrap">
      <button
        @click="allerVers('#/collectes')"
        class="rounded-[40px] px-4 py-1.5 font-sans text-small transition-all"
        :class="vueCourante === 'collectes' ? 'bg-violet-950 text-beige-50 underline' : 'bg-white text-texte-secondary shadow-[0_0_4px_rgba(0,0,0,0.25)]'"
      >Collectes</button>
      <button
        @click="allerVers('#/analytics')"
        class="rounded-[40px] px-4 py-1.5 font-sans text-small transition-all"
        :class="vueCourante === 'analytics' ? 'bg-violet-950 text-beige-50 underline' : 'bg-white text-texte-secondary shadow-[0_0_4px_rgba(0,0,0,0.25)]'"
      >Analytics</button>
      <button
        @click="gererLogout"
        class="rounded-[40px] bg-rouge-500 px-4 py-1.5 font-sans text-small text-white"
      >Logout</button>
    </div>
  </nav>
</template>
