<!-- LogoUpload -->
<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  modelValue: { type: String, default: null }, // URL actuelle
})
const emit = defineEmits(['update:modelValue'])

const apercuUrl = ref(props.modelValue)
const erreur = ref(null)

// Sync aperçu
watch(() => props.modelValue, (valeur) => { apercuUrl.value = valeur })

// Taille max 1 Mo
const TAILLE_MAX = 1024 * 1024

function gererFichier(event) {
  const fichier = event.target.files?.[0]
  if (!fichier) return

  erreur.value = null
  if (fichier.size > TAILLE_MAX) {
    erreur.value = 'Logo trop volumineux (max 1 Mo).'
    return
  }

  const lecteur = new FileReader()
  lecteur.onload = () => {
    apercuUrl.value = lecteur.result
    emit('update:modelValue', lecteur.result)
  }
  lecteur.readAsDataURL(fichier)
}
</script>

<template>
  <div class="flex flex-col items-start gap-1">
    <span class="font-sans text-small font-medium text-violet-800">Ajouter un logo</span>
    <label class="flex h-[60px] w-[60px] cursor-pointer items-center justify-center rounded-lg bg-violet-100 shadow-[0_0_4px_rgba(0,0,0,0.15)] hover:bg-violet-200 transition-colors overflow-hidden">
      <!-- Prévisualisation si logo existant -->
      <img
        v-if="apercuUrl"
        :src="apercuUrl"
        alt="logo entreprise"
        class="h-full w-full object-contain p-1"
      />
      <!-- Icône upload sinon -->
      <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-violet-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 12V4m0 0L8 8m4-4l4 4" />
      </svg>
      <input type="file" accept="image/*" @change="gererFichier" class="sr-only" />
    </label>
    <span v-if="erreur" class="font-sans text-small text-rouge-500">{{ erreur }}</span>
  </div>
</template>
