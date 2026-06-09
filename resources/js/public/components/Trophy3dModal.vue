<script setup>
import '@google/model-viewer'
const open = defineModel({ type: Boolean, required: true })

defineProps({
  src: { type: String, required: true },
})

function close() {
  open.value = false
}
</script>

<template>
  <Teleport to="body">
    <Transition
      enter-active-class="transition-opacity duration-300 ease-out"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-200 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="open"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4"
        @click.self="close"
      >
        <div class="relative flex h-[90dvh] w-full max-w-[1100px] flex-col overflow-hidden rounded-[25px] bg-form-bg p-6 md:h-[80dvh] md:p-[47px]">
          <!-- Bouton fermer -->
          <button
            type="button"
            aria-label="Fermer l'aperçu du trophée"
            class="absolute right-4 top-4 z-10 flex h-8 w-8 items-center justify-center rounded-full text-texte-primary-dark transition-colors hover:bg-violet-100 md:right-[40px] md:top-[40px]"
            @click="close"
          >
            ✕
          </button>

          <h3 class="font-sans text-h2 font-semibold leading-tight text-texte-primary-dark">
            Le Trophée de la Générosité en 3D
          </h3>

          <!-- Visionneuse 3D -->
          <model-viewer
            :src="src"
            alt="Modèle 3D du Trophée de la Générosité"
            camera-controls
            touch-action="pan-y"
            auto-rotate
            camera-orbit="-90deg 75deg 92%"
            shadow-intensity="1"
            class="mt-4 h-full w-full flex-1"
          ></model-viewer>

          <p class="mt-2 text-center font-sans text-small text-texte-primary-dark/70">
            Faites glisser pour faire pivoter le trophée.
          </p>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>
