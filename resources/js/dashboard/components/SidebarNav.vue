<!-- Navigation du dashboard : sidebar gauche sur desktop, header + menu déroulant sur mobile -->
<script setup>
import { useSessionAuth } from '../composables/useSessionAuth.js'
import { useDisclosure }  from '@/composables/useDisclosure'

const props = defineProps({
  // clé de la vue active : 'collectes' | 'entreprises' | 'trophees' | 'analytics'
  vueCourante: { type: String, default: 'collectes' },
  allerVers:   { type: Function, required: true },
})

const { deconnecter } = useSessionAuth()
const { isOpen, toggle, close } = useDisclosure()

// Liens de navigation (partagés desktop + mobile)
const liens = [
  { cle: 'collectes',   label: 'Collectes',   hash: '#/collectes'   },
  { cle: 'entreprises', label: 'Entreprises', hash: '#/entreprises' },
  { cle: 'trophees',    label: 'Trophées',    hash: '#/trophees'    },
  { cle: 'analytics',   label: 'Analytics',   hash: '#/analytics'   },
]

function naviguer(hash) {
  close()
  props.allerVers(hash)
}

function gererLogout() {
  close()
  deconnecter()
  props.allerVers('#/connexion')
}
</script>

<template>
  <!-- Sidebar desktop -->
  <aside class="hidden h-full w-[215px] shrink-0 flex-col gap-4 overflow-hidden px-[30px] py-8 lg:flex">
    <!-- Logo HUG -->
    <img :src="'/images/logos/logo-hug.png'" alt="logo HUG" class="mb-6 h-auto w-full max-w-[155px]" />

    <button
      v-for="lien in liens"
      :key="lien.cle"
      @click="allerVers(lien.hash)"
      class="w-full rounded-[40px] px-6 py-2.5 font-sans text-regular transition-all"
      :class="vueCourante === lien.cle
        ? 'bg-violet-950 font-semibold text-beige-50 underline shadow-[0_0_4px_rgba(0,0,0,0.25)]'
        : 'bg-white text-texte-secondary shadow-[0_0_4px_rgba(0,0,0,0.25)] hover:bg-violet-50'"
    >
      {{ lien.label }}
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

  <!-- Header mobile : logo + bouton menu -->
  <header class="sticky top-0 z-50 shrink-0 border-b border-violet-200 bg-beige-50 lg:hidden">
    <div class="flex items-center justify-between px-4 py-2.5">
      <img :src="'/images/logos/logo-hug.png'" alt="logo HUG" class="h-7 w-auto" />
      <button
        type="button"
        @click="toggle"
        class="flex h-10 w-10 items-center justify-center rounded-full text-violet-950"
        aria-label="Menu"
        :aria-expanded="isOpen"
      >
        <svg v-if="!isOpen" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
        </svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18" />
        </svg>
      </button>
    </div>

    <!-- Menu déroulant -->
    <Transition
      enter-active-class="transition duration-150 ease-out origin-top"
      enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition duration-100 ease-in origin-top"
      leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 -translate-y-2"
    >
      <nav v-show="isOpen" class="absolute inset-x-0 top-full z-40 flex flex-col gap-2 border-t border-violet-200 bg-beige-50 px-4 pb-4 pt-3 shadow-[0_4px_8px_rgba(0,0,0,0.12)]">
        <button
          v-for="lien in liens"
          :key="lien.cle"
          @click="naviguer(lien.hash)"
          class="w-full rounded-[40px] px-5 py-2.5 text-left font-sans text-regular transition-all"
          :class="vueCourante === lien.cle
            ? 'bg-violet-950 font-semibold text-beige-50 underline'
            : 'bg-white text-texte-secondary shadow-[0_0_4px_rgba(0,0,0,0.25)]'"
        >{{ lien.label }}</button>
        <button
          @click="gererLogout"
          class="mt-1 w-full rounded-[40px] bg-rouge-500 px-5 py-2.5 text-left font-sans text-regular text-white"
        >Logout</button>
      </nav>
    </Transition>
  </header>
</template>
