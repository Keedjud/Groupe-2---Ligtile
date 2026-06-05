<<<<<<< HEAD
<!-- Upload de logo avec prévisualisation -->
<script setup>
import { ref } from 'vue'
=======
<!-- LogoUpload -->
<script setup>
import { ref, watch } from 'vue'
>>>>>>> develop

const props = defineProps({
  modelValue: { type: String, default: null }, // URL actuelle
})
const emit = defineEmits(['update:modelValue'])

<<<<<<< HEAD
const apercuUrl = ref(props.modelValue)

function gererFichier(event) {
  const fichier = event.target.files?.[0]
  if (!fichier) return
  // Mock : crée une URL objet locale pour la prévisualisation
  const urlLocale = URL.createObjectURL(fichier)
  apercuUrl.value = urlLocale
  emit('update:modelValue', urlLocale)
=======
const apercuUrl  = ref(props.modelValue)
const erreur     = ref(null)
const chargement = ref(false)

// Sync aperçu quand la valeur parent change (ex : mode édition)
watch(() => props.modelValue, (valeur) => { apercuUrl.value = valeur })

// Taille max 1 Mo (vérification client)
const TAILLE_MAX = 1024 * 1024

async function gererFichier(event) {
  const fichier = event.target.files?.[0]
  if (!fichier) return

  erreur.value = null

  if (fichier.size > TAILLE_MAX) {
    erreur.value = 'Logo trop volumineux (max 1 Mo).'
    return
  }

  chargement.value = true

  try {
    const formData = new FormData()
    formData.append('logo', fichier)

    const reponse = await fetch('/api/v1/logos/upload', {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XmlHttpRequest',
        'Accept': 'application/json',
      },
      body: formData,
      credentials: 'include',
    })

    if (!reponse.ok) {
      const data = await reponse.json().catch(() => ({}))
      erreur.value = data?.message ?? 'Erreur lors de l\'upload.'
      return
    }

    const data = await reponse.json()
    apercuUrl.value = data.logo_url
    emit('update:modelValue', data.logo_url)
  } catch {
    erreur.value = 'Erreur réseau lors de l\'upload.'
  } finally {
    chargement.value = false
  }
>>>>>>> develop
}
</script>

<template>
  <div class="flex flex-col items-start gap-1">
<<<<<<< HEAD
    <span class="font-sans text-regular">Ajouter un logo</span>
    <label class="flex h-[60px] w-[60px] cursor-pointer items-center justify-center rounded-lg bg-violet-100 shadow-[0_0_4px_rgba(0,0,0,0.15)] hover:bg-violet-200 transition-colors overflow-hidden">
      <!-- Prévisualisation si logo existant -->
      <img
        v-if="apercuUrl"
        :src="apercuUrl"
        alt="logo"
        class="h-full w-full object-contain p-1"
      />
=======
    <span class="font-sans text-small font-medium text-violet-800">Ajouter un logo</span>
    <label class="flex h-[60px] w-[60px] cursor-pointer items-center justify-center rounded-lg bg-violet-100 shadow-[0_0_4px_rgba(0,0,0,0.15)] hover:bg-violet-200 transition-colors overflow-hidden"
           :class="chargement ? 'opacity-60 pointer-events-none' : ''">

      <!-- Spinner pendant l'upload -->
      <svg v-if="chargement" class="h-6 w-6 animate-spin text-violet-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
      </svg>

      <!-- Prévisualisation si logo existant -->
      <img
        v-else-if="apercuUrl"
        :src="apercuUrl"
        alt="logo entreprise"
        class="h-full w-full object-contain p-1"
      />

>>>>>>> develop
      <!-- Icône upload sinon -->
      <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-violet-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 12V4m0 0L8 8m4-4l4 4" />
      </svg>
<<<<<<< HEAD
      <input type="file" accept="image/*" @change="gererFichier" class="sr-only" />
    </label>
=======

      <input type="file" accept="image/*" @change="gererFichier" class="sr-only" :disabled="chargement" />
    </label>
    <span v-if="erreur" class="font-sans text-small text-rouge-500">{{ erreur }}</span>
>>>>>>> develop
  </div>
</template>
