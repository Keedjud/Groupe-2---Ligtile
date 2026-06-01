<!-- Upload de logo avec prévisualisation -->
<script setup>
import { ref } from 'vue'

const props = defineProps({
  modelValue: { type: String, default: null }, // URL actuelle
})
const emit = defineEmits(['update:modelValue'])

const apercuUrl = ref(props.modelValue)

function gererFichier(event) {
  const fichier = event.target.files?.[0]
  if (!fichier) return
  // Mock : crée une URL objet locale pour la prévisualisation
  const urlLocale = URL.createObjectURL(fichier)
  apercuUrl.value = urlLocale
  emit('update:modelValue', urlLocale)
}
</script>

<template>
  <div class="flex flex-col items-start gap-1">
    <span class="font-sans text-regular">Ajouter un logo</span>
    <label class="flex h-[60px] w-[60px] cursor-pointer items-center justify-center rounded-lg bg-violet-100 shadow-[0_0_4px_rgba(0,0,0,0.15)] hover:bg-violet-200 transition-colors overflow-hidden">
      <!-- Prévisualisation si logo existant -->
      <img
        v-if="apercuUrl"
        :src="apercuUrl"
        alt="logo"
        class="h-full w-full object-contain p-1"
      />
      <!-- Icône upload sinon -->
      <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-violet-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 12V4m0 0L8 8m4-4l4 4" />
      </svg>
      <input type="file" accept="image/*" @change="gererFichier" class="sr-only" />
    </label>
  </div>
</template>
