<!-- Flux de questions (étapes 1 à 3) -->
<script setup>
import { ref, computed, watch } from 'vue'
import { useFetchApi } from '@/composables/api/useFetchApi'

const { fetchApi } = useFetchApi()

const props = defineProps({
  collecte:  { type: Object, required: true },
  allerVers: { type: Function, required: true },
})

const emit = defineEmits(['flow-termine'])

// Étape révélée
const etapeRevele = ref(1)

// Overlay pour corriger les infos
const overlayNonVisible = ref(false)

// États des actions
const lienGenere     = ref(false)
const mailEnvoye     = ref(false)
const pressePapierOk = ref(false)
const envoiMailEnCours = ref(false)

const lienCoBrande = ref('')
const erreurEnvoi  = ref('')

const flowTermine = computed(() => mailEnvoye.value === true)

watch(flowTermine, (val) => {
  emit('flow-termine', val)
})

// Vérifications des infos de la collecte
const verificationsInfos = computed(() => {
  if (!props.collecte) return []
  const problemes = []
  const e = props.collecte.entreprise
  const a = props.collecte.adresse

  if (!String(e?.nom ?? '').trim())
    problemes.push({ champ: "Nom de l'entreprise", message: 'Le nom est manquant.' })
  if (!String(props.collecte?.contact_email ?? '').includes('@'))
    problemes.push({ champ: 'Adresse e-mail', message: "L'adresse e-mail est invalide ou manquante." })
  if (!String(props.collecte?.contact_phone ?? '').trim())
    problemes.push({ champ: 'Téléphone', message: 'Le numéro de téléphone est manquant.' })
  if (!String(a?.rue ?? '').trim())
    problemes.push({ champ: 'Adresse', message: "La rue de l'adresse est manquante." })
  if (!String(a?.npa ?? '').trim())
    problemes.push({ champ: 'NPA', message: 'Le code postal est manquant.' })
  if (!String(a?.ville ?? '').trim())
    problemes.push({ champ: 'Ville', message: 'La ville est manquante.' })

  // Vérif dates
  if (!props.collecte.date_debut)
    problemes.push({ champ: 'Date de début', message: 'La date de début est manquante.' })
  if (!props.collecte.date_fin)
    problemes.push({ champ: 'Date de fin', message: 'La date de fin est manquante.' })
  if (props.collecte.date_debut && props.collecte.date_fin) {
    if (new Date(props.collecte.date_fin) <= new Date(props.collecte.date_debut))
      problemes.push({ champ: 'Dates', message: 'La date de fin doit être postérieure à la date de début.' })
  }

  return problemes
})

function repondreOui() {
  if (verificationsInfos.value.length > 0) {
    overlayNonVisible.value = true
    return
  }
  etapeRevele.value = 2
}

function repondreNon() {
  overlayNonVisible.value = true
}

function fermerOverlayEtEditer() {
  overlayNonVisible.value = false
  props.allerVers('#/collectes/' + props.collecte.id + '/edit')
}

function fermerOverlay() {
  overlayNonVisible.value = false
}

// Étape 2 : génération du lien (calcul instantané depuis jeton_public)
function genererLien() {
  lienCoBrande.value = window.location.origin + '/' + props.collecte.jeton_public
  lienGenere.value = true
  etapeRevele.value = 3
}

async function sauvegarderLienMemoire() {
  try {
    await navigator.clipboard.writeText(lienCoBrande.value)
    pressePapierOk.value = true
    setTimeout(() => { pressePapierOk.value = false }, 3000)
  } catch {
    pressePapierOk.value = false
  }
}

// Étape 3 : envoi du kit par mail à l'entreprise partenaire
async function envoyerMail() {
  envoiMailEnCours.value = true
  erreurEnvoi.value = ''
  try {
    await fetchApi({
      url: '/manage-collections/' + props.collecte.id + '/kit/send',
      method: 'POST',
      // Timeout étendu (SMTP)
      timeout: 30000,
    })
    mailEnvoye.value = true
  } catch (e) {
    erreurEnvoi.value = e?.data?.message || "L'envoi du kit a échoué. Veuillez réessayer."
  } finally {
    envoiMailEnCours.value = false
  }
}
</script>

<template>
  <div class="relative">
    <div class="rounded-[20px] bg-beige-50/60 p-3 flex flex-col gap-9">

      <!-- Étape 1 : Informations correctes ? -->
      <div class="flex items-start gap-7">
        <!-- Pastille violette -->
        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-violet-500 font-sans text-regular text-black">
          1
        </div>
        <div class="flex flex-wrap items-center gap-4 sm:gap-8">
          <span class="font-sans text-h5">Les informations sont-elles correctes ?</span>
          <div v-if="etapeRevele === 1" class="flex gap-4">
            <button
              type="button"
              @click="repondreNon"
              class="rounded-[40px] bg-rouge-600 px-6 py-2.5 font-sans text-regular text-white transition-opacity hover:opacity-90"
            >Non</button>
            <button
              type="button"
              @click="repondreOui"
              class="rounded-[40px] bg-vert-400 px-6 py-2.5 font-sans text-regular text-white transition-opacity hover:opacity-90"
            >Oui</button>
          </div>
          <!-- Indicateur validé -->
          <span v-else class="inline-flex items-center gap-2 rounded-[40px] bg-vert-400 px-5 py-2 font-sans text-small text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            Validé
          </span>
        </div>
      </div>

      <!-- Étape 2 : Générer le lien co-brandé (révélé après Oui à l'étape 1) -->
      <Transition name="glisser">
        <div v-if="etapeRevele >= 2" class="flex items-start gap-7">
          <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-violet-500 font-sans text-regular text-black">
            2
          </div>
          <div class="flex flex-col gap-3">
            <div class="flex items-center gap-4">
              <span class="font-sans text-h5">Générer le lien du site</span>
              <button
                type="button"
                v-if="!lienGenere"
                @click="genererLien"
                class="rounded-[40px] bg-violet-900 px-5 py-2 font-sans text-small text-white transition-opacity hover:opacity-90"
              >Générer</button>
              <span v-else class="inline-flex items-center gap-2 rounded-[40px] bg-vert-400 px-5 py-2 font-sans text-small text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Généré
              </span>
            </div>
            <!-- Champ lien co-brandé -->
            <div v-if="lienCoBrande" class="flex items-center gap-2">
              <label class="font-sans text-small text-violet-950">Lien du site co-brandé :</label>
              <div class="flex items-center gap-2 rounded-lg bg-white px-3 py-2 shadow-[0_0_4px_rgba(0,0,0,0.15)]">
                <span class="select-all font-sans text-small text-violet-900 break-all">{{ lienCoBrande }}</span>
                <button
                  type="button"
                  @click="sauvegarderLienMemoire"
                  class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white text-violet-700 shadow-[0_0_4px_rgba(0,0,0,0.25)] transition-colors hover:bg-violet-50"
                  :title="pressePapierOk ? 'Copié !' : 'Copier le lien'"
                >
                  <!-- Icône copié ou copie -->
                  <svg v-if="!pressePapierOk" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                  </svg>
                  <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-vert-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </Transition>

      <!-- Étape 3 : Envoyer le kit -->
      <Transition name="glisser">
        <div v-if="etapeRevele >= 3" class="flex items-start gap-7">
          <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-violet-500 font-sans text-regular text-black">
            3
          </div>
          <div class="flex flex-col gap-2">
            <div class="flex items-center gap-4">
              <span class="font-sans text-h5">Envoyer le kit par mail</span>
              <button
                type="button"
                v-if="!mailEnvoye"
                @click="envoyerMail"
                :disabled="envoiMailEnCours"
                class="rounded-[40px] bg-violet-900 px-5 py-2 font-sans text-small text-white transition-opacity hover:opacity-90 disabled:opacity-60"
              >
                <span v-if="envoiMailEnCours" class="flex items-center gap-2">
                  <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.4 0 0 5.4 0 12h4z" />
                  </svg>
                  Envoi…
                </span>
                <span v-else>Envoyer</span>
              </button>
              <span v-else class="inline-flex items-center gap-2 rounded-[40px] bg-vert-400 px-5 py-2 font-sans text-small text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Envoyé
              </span>
            </div>
            <p v-if="erreurEnvoi" class="font-sans text-small text-rouge-600">{{ erreurEnvoi }}</p>
          </div>
        </div>
      </Transition>

    </div>

    <!-- Overlay correction des infos -->
    <Teleport to="body">
      <Transition name="fondu">
        <div
          v-if="overlayNonVisible"
          class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
          @click.self="fermerOverlay"
        >
          <div class="w-full max-w-lg rounded-[20px] bg-form-bg p-8 shadow-xl">
            <!-- Titre -->
            <h2 class="text-center font-sans text-h2 font-bold text-texte-secondary mb-6">
              Veuillez corriger les informations erronées
            </h2>

            <!-- Liste des problèmes détectés -->
            <div v-if="verificationsInfos.length > 0" class="mb-6 rounded-xl bg-rouge-500/10 p-4">
              <p class="font-sans text-small font-semibold text-rouge-500 mb-3">
                Les champs suivants doivent être corrigés :
              </p>
              <ul class="flex flex-col gap-2">
                <li
                  v-for="(prob, idx) in verificationsInfos"
                  :key="idx"
                  class="flex items-start gap-2 font-sans text-small text-violet-950"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mt-0.5 shrink-0 text-rouge-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="15" y1="9" x2="9" y2="15" />
                    <line x1="9" y1="9" x2="15" y2="15" />
                  </svg>
                  <span><strong>{{ prob.champ }}</strong> : {{ prob.message }}</span>
                </li>
              </ul>
            </div>

            <p v-else class="mb-6 text-center font-sans text-regular text-violet-800">
              Veuillez vérifier et corriger les informations de la collecte avant de continuer.
            </p>

            <!-- Actions -->
            <div class="flex justify-center gap-4">
              <button
                type="button"
                @click="fermerOverlay"
                class="rounded-[40px] bg-white px-6 py-3 font-sans text-regular text-texte-secondary shadow-[0_0_4px_rgba(0,0,0,0.15)] transition-opacity hover:opacity-80"
              >
                Annuler
              </button>
              <button
                type="button"
                @click="fermerOverlayEtEditer"
                class="rounded-[40px] bg-violet-900 px-8 py-3 font-sans text-regular text-white hover:opacity-90 transition-opacity"
              >
                Modifier la collecte
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<style scoped>
.glisser-enter-active,
.glisser-leave-active {
  transition: all 0.3s ease;
}
.glisser-enter-from {
  opacity: 0;
  transform: translateY(-8px);
}
.glisser-leave-to {
  opacity: 0;
}

.fondu-enter-active,
.fondu-leave-active {
  transition: opacity 0.2s ease;
}
.fondu-enter-from,
.fondu-leave-to {
  opacity: 0;
}
</style>
