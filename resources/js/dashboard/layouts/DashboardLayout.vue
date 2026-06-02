<!-- Layout principal du dashboard : banner + header HUG + sidebar + contenu + notice -->
<script setup>
import FictifBanner from '../components/FictifBanner.vue'
import SidebarNav   from '../components/SidebarNav.vue'

defineProps({
  vueCourante: { type: String, default: 'collectes' },
  allerVers:   { type: Function, required: true },
})
</script>

<template>
  <div class="flex h-screen flex-col bg-beige-50 font-sans antialiased overflow-hidden">

    <div class="top-0 z-30">
      <FictifBanner />
    </div>

    <!-- Corps : sidebar (gère mobile + desktop en interne) + zone de contenu -->
    <div class="flex flex-1 flex-col lg:flex-row min-h-0">

      <!-- SidebarNav gère elle-même les deux états responsive -->
      <SidebarNav
        :vueCourante="vueCourante"
        :allerVers="allerVers"
      />

      <!-- Zone contenu (panneau violet-100 arrondi) -->
      <main class="flex-1 overflow-y-auto p-4 lg:p-6">
        <div class="min-h-full rounded-[20px] bg-violet-100 p-4 lg:p-6">
          <slot />
        </div>
      </main>
    </div>

    <!-- Notice bas de page -->
    <footer class="w-full bg-rouge-500 p-2.5 text-center font-sans text-h4 font-bold text-light-palette-black">
      Ce site est la réalisation d'un projet d'étudiant·es de la HEIG-VD en collaboration avec les HUG.
    </footer>

  </div>
</template>
