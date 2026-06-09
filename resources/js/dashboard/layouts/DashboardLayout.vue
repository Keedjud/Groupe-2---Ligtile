<script setup>
import FictifBanner       from '../components/FictifBanner.vue'
import SidebarNav         from '../components/SidebarNav.vue'
import OverlayNotification from '../components/OverlayNotification.vue'

defineProps({
  vueCourante: { type: String, default: 'collectes' },
  allerVers:   { type: Function, required: true },
})
</script>

<template>
  <div class="flex min-h-dvh flex-col overflow-x-hidden bg-beige-50 font-sans antialiased">

    <div class="shrink-0">
      <FictifBanner />
    </div>

    <!-- Corps : sidebar (gère mobile + desktop en interne) + zone de contenu -->
    <div class="flex min-h-0 flex-1 flex-col lg:flex-row">

      <!-- SidebarNav gère elle-même les deux états responsive -->
      <SidebarNav
        :vueCourante="vueCourante"
        :allerVers="allerVers"
      />

      <!-- Zone contenu (panneau violet-100 arrondi) -->
      <main class="min-w-0 flex-1 overflow-y-auto overflow-x-hidden p-4 lg:p-6">
        <div class="min-h-full rounded-[20px] bg-violet-100 p-4 lg:p-6">
          <slot />
        </div>
      </main>
    </div>

    <footer class="shrink-0 w-full bg-rouge-500 p-2.5 text-center font-sans text-small font-bold text-light-palette-black">
      Ce site est la réalisation d'un projet d'étudiant·es de la HEIG-VD en collaboration avec les HUG.
    </footer>

    <OverlayNotification />
  </div>
</template>
