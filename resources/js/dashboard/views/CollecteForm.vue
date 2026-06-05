<!-- CollecteForm -->
<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import DashboardLayout  from '../layouts/DashboardLayout.vue'
import ColorField       from '../components/ColorField.vue'
import LogoUpload       from '../components/LogoUpload.vue'
import CalendrierPicker from '../components/CalendrierPicker.vue'
import { useCollectes } from '../composables/useCollectes.js'
import { useFetchApi }  from '@/composables/api/useFetchApi'

const props = defineProps({
  mode:       { type: String, default: 'create' },  // 'create' | 'edit'
  idCollecte: { type: [String, Number], default: null },
  allerVers:  { type: Function, required: true },
})

const { trouverParId, creerCollecte, mettreAJourCollecte, chargement } = useCollectes()
const { fetchApi } = useFetchApi()

// ─── Champs du formulaire (snapshot collecte) ────────────────────────────────
const champRue          = ref('')
const champNumero       = ref('')
const champNpa          = ref('')
const champVille        = ref('')
const champEmail        = ref('')
const champTelephone    = ref('')
const couleurPrincipale = ref('#681764')
const couleurSecondaire = ref('#C44444')
const logoUrl           = ref(null)
const champDateDebut    = ref('')
const champDateFin      = ref('')
const champCapacity     = ref('')
const champOnedocUrl    = ref('')
const champKitUrl       = ref('')

// ─── Entreprise (création) ───────────────────────────────────────────────────
const rechercheEntreprise    = ref('')
const resultatsEntreprise    = ref([])
const entrepriseSelectionnee = ref(null)  // objet brut de l'API
let debounceTimer = null

// ─── Entreprise (édition) ───────────────────────────────────────────────────
const nomEntrepriseEdit = ref('')  // lecture seule en mode édition

async function surRechercheEntreprise() {
  resultatsEntreprise.value = []
  if (!rechercheEntreprise.value.trim()) return
  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(async () => {
    try {
      const res = await fetchApi({
        url: `/companies?search=${encodeURIComponent(rechercheEntreprise.value)}`,
      })
      resultatsEntreprise.value = res.slice(0, 8)
    } catch { /* silencieux */ }
  }, 300)
}

function selectionnerEntreprise(e) {
  entrepriseSelectionnee.value = e
  rechercheEntreprise.value    = ''
  resultatsEntreprise.value    = []
  // Pré-remplissage depuis les données de l'entreprise (modifiable par la suite)
  champRue.value       = e.address?.street      || ''
  champNumero.value    = String(e.address?.number      || '')
  champNpa.value       = String(e.address?.postal_code || '')
  champVille.value     = e.address?.city         || ''
  champEmail.value     = e.contact?.email        || ''
  champTelephone.value = e.contact?.phone        || ''
}

function deselectionnerEntreprise() {
  entrepriseSelectionnee.value = null
  rechercheEntreprise.value    = ''
  champRue.value       = ''
  champNumero.value    = ''
  champNpa.value       = ''
  champVille.value     = ''
  champEmail.value     = ''
  champTelephone.value = ''
  champDateDebut.value = ''
  champDateFin.value   = ''
  champCapacity.value  = ''
  champOnedocUrl.value = ''
  champKitUrl.value    = ''
  logoUrl.value        = null
  couleurPrincipale.value = '#681764'
  couleurSecondaire.value = '#C44444'
}

// ─── Validation croisée des dates ────────────────────────────────────────────
// Si on change la date de début et que la fin devient invalide, on la réinitialise
watch(champDateDebut, (newVal) => {
  if (champDateFin.value && champDateFin.value <= newVal) {
    champDateFin.value = ''
  }
})

// ─── Pré-chargement en mode édition ─────────────────────────────────────────
onMounted(() => {
  if (props.mode === 'edit' && props.idCollecte) {
    const collecte = trouverParId(props.idCollecte)
    if (collecte) {
      nomEntrepriseEdit.value = collecte.entreprise.nom
      champRue.value          = collecte.adresse.rue
      champNumero.value       = collecte.adresse.numero
      champNpa.value          = collecte.adresse.npa
      champVille.value        = collecte.adresse.ville
      champEmail.value        = collecte.contact_email
      champTelephone.value    = collecte.contact_phone
      couleurPrincipale.value = collecte.couleur_principale
      couleurSecondaire.value = collecte.couleur_secondaire
      logoUrl.value           = collecte.logo_url
      champDateDebut.value    = (collecte.date_debut || '').slice(0, 10)
      champDateFin.value      = (collecte.date_fin   || '').slice(0, 10)
      champCapacity.value     = collecte.capacity ?? ''
      champOnedocUrl.value    = collecte.onedoc_url || ''
      champKitUrl.value       = collecte.kit_url    || ''
    }
  }
})

// ─── Validation ──────────────────────────────────────────────────────────────
const regexEmail  = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
const regexNpa    = /^\d{4}$/
const regexTel    = /^[+\d][\d\s()/.-]{5,}$/
const regexNumero = /^\d+[a-zA-Z]?$/

const champsInvalides = ref({})
const erreurServeur   = ref('')
const aDesErreurs     = computed(() => Object.keys(champsInvalides.value).length > 0)

function valider() {
  const e = {}
  if (props.mode === 'create' && !entrepriseSelectionnee.value) {
    e.entreprise = "Veuillez sélectionner une entreprise."
  }
  if (!champRue.value.trim())                                       e.rue       = 'Rue requise.'
  if (!regexNumero.test(champNumero.value.trim()))                  e.numero    = 'Numéro invalide (ex. 12 ou 12b).'
  if (!regexNpa.test(champNpa.value.trim()))                        e.npa       = 'NPA invalide (4 chiffres).'
  if (!champVille.value.trim())                                     e.ville     = 'Ville requise.'
  if (!regexEmail.test(champEmail.value.trim()))                    e.email     = 'Adresse e-mail invalide.'
  if (!regexTel.test(champTelephone.value.trim()))                  e.telephone = 'Téléphone invalide.'
  if (!logoUrl.value)                                               e.logo      = 'Logo requis.'
  if (!champCapacity.value || Number(champCapacity.value) < 1)      e.capacity  = 'Capacité requise (≥ 1 créneau).'
  if (!champOnedocUrl.value.trim())                                 e.onedocUrl = 'Lien Onedoc requis.'
  if (!champKitUrl.value.trim())                                    e.kitUrl    = 'Lien KDrive requis.'
  if (!champDateDebut.value)                                        e.dateDebut = 'Date de début requise.'
  if (!champDateFin.value)                                          e.dateFin   = 'Date de fin requise.'
  if (champDateDebut.value && champDateFin.value && champDateFin.value <= champDateDebut.value) {
    e.dateFin = 'La date de fin doit être postérieure à la date de début.'
  }
  champsInvalides.value = e
  return Object.keys(e).length === 0
}

// ─── Envoi ───────────────────────────────────────────────────────────────────
async function soumettre() {
  erreurServeur.value = ''
  if (!valider()) return

  const donneesEnvoi = {
    // En création : company_id obligatoire. En édition : pas de company dans le payload.
    ...(props.mode === 'create' ? { company_id: entrepriseSelectionnee.value.id } : {}),
    contact_email: champEmail.value.trim(),
    contact_phone: champTelephone.value.trim(),
    adresse: {
      rue:    champRue.value.trim(),
      numero: champNumero.value.trim(),
      npa:    champNpa.value.trim(),
      ville:  champVille.value.trim(),
    },
    date_debut:         champDateDebut.value,
    date_fin:           champDateFin.value,
    capacity:           Number(champCapacity.value),
    couleur_principale: couleurPrincipale.value,
    couleur_secondaire: couleurSecondaire.value,
    logo_url:           logoUrl.value,
    onedoc_url:         champOnedocUrl.value.trim(),
    kit_url:            champKitUrl.value.trim(),
  }

  try {
    if (props.mode === 'create') {
      const nouvelle = await creerCollecte(donneesEnvoi)
      props.allerVers('#/collectes/' + nouvelle.id)
    } else {
      await mettreAJourCollecte(props.idCollecte, donneesEnvoi)
      props.allerVers('#/collectes/' + props.idCollecte)
    }
  } catch (e) {
    erreurServeur.value = e?.data?.message || "Une erreur est survenue lors de l'enregistrement."
  }
}

const titrePage  = computed(() => props.mode === 'edit' ? 'Modifier la collecte' : 'Nouvelle collecte')
const libelleBtn = computed(() => props.mode === 'edit' ? 'Enregistrer' : 'Créer la collecte')
</script>

<template>
  <DashboardLayout :vueCourante="'collectes'" :allerVers="allerVers">
    <div class="mx-auto max-w-2xl rounded-[20px] bg-form-bg p-6 shadow-[0_4px_16px_rgba(104,23,100,0.10)]">

      <h1 class="mb-6 text-center font-sans text-h3 font-bold text-violet-800">{{ titrePage }}</h1>

      <!-- Erreurs -->
      <div v-if="aDesErreurs || erreurServeur"
        class="mb-5 rounded-xl border border-rouge-500 bg-rouge-500/10 p-4 font-sans text-small text-rouge-600">
        <p class="font-semibold">Merci de corriger les points suivants :</p>
        <ul class="mt-1 list-disc pl-4">
          <li v-for="(msg, champ) in champsInvalides" :key="champ">{{ msg }}</li>
          <li v-if="erreurServeur">{{ erreurServeur }}</li>
        </ul>
      </div>

      <form @submit.prevent="soumettre" class="flex flex-col gap-4">

        <!-- ═══════════════════════════════════════════════════════════════
             SECTION ENTREPRISE
        ══════════════════════════════════════════════════════════════════ -->

        <!-- Mode édition : badge lecture seule -->
        <div v-if="mode === 'edit'"
          class="flex items-center gap-3 rounded-lg border border-violet-200 bg-violet-50 px-4 py-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
          </svg>
          <div>
            <p class="font-sans text-xs font-semibold uppercase tracking-wide text-violet-400">Entreprise</p>
            <p class="font-sans text-small font-semibold text-violet-900">{{ nomEntrepriseEdit }}</p>
          </div>
        </div>

        <!-- Mode création : sélecteur obligatoire -->
        <template v-else>
          <!-- Badge : entreprise sélectionnée -->
          <div v-if="entrepriseSelectionnee"
            class="flex items-center gap-3 rounded-lg border border-violet-200 bg-violet-50 px-4 py-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-vert-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            <div class="flex-1 min-w-0">
              <p class="font-sans text-small font-semibold text-violet-900">{{ entrepriseSelectionnee.name }}</p>
              <p class="font-sans text-xs text-violet-500">{{ entrepriseSelectionnee.size_label }}</p>
            </div>
            <button type="button" @click="deselectionnerEntreprise"
              class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-violet-400 transition-colors hover:bg-violet-200 hover:text-violet-700"
              title="Changer d'entreprise">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <!-- Recherche (si aucune entreprise sélectionnée) -->
          <div v-else class="flex flex-col gap-1">
            <label class="font-sans text-small font-semibold text-violet-950">
              Entreprise
              <span class="font-normal text-rouge-600"> *</span>
            </label>
            <p class="font-sans text-small text-violet-500">Sélectionnez l'entreprise partenaire pour pré-remplir les informations.</p>
            <div class="relative">
              <input
                v-model="rechercheEntreprise"
                @input="surRechercheEntreprise"
                @blur="() => setTimeout(() => resultatsEntreprise = [], 150)"
                type="search"
                placeholder="Rechercher par nom…"
                class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400"
                :class="{ 'ring-rouge-500 ring-1': champsInvalides.entreprise }"
              />
              <!-- Dropdown résultats -->
              <div v-if="resultatsEntreprise.length"
                class="absolute top-full z-20 mt-1 w-full overflow-hidden rounded-lg bg-white shadow-[0_4px_16px_rgba(104,23,100,0.15)]">
                <button
                  v-for="e in resultatsEntreprise"
                  :key="e.id"
                  type="button"
                  @mousedown.prevent="selectionnerEntreprise(e)"
                  class="flex w-full items-center justify-between border-b border-violet-50 px-4 py-2.5 text-left font-sans text-small last:border-0 transition-colors hover:bg-violet-50"
                >
                  <span class="font-medium text-violet-950">{{ e.name }}</span>
                  <span class="ml-3 shrink-0 text-xs text-violet-400">{{ e.size_label }}</span>
                </button>
              </div>
            </div>
          </div>
        </template>

        <!-- ═══════════════════════════════════════════════════════════════
             RESTE DU FORMULAIRE — masqué en création jusqu'à sélection
        ══════════════════════════════════════════════════════════════════ -->
        <template v-if="mode === 'edit' || entrepriseSelectionnee">

          <!-- Note pré-remplissage (création uniquement) -->
          <p v-if="mode === 'create' && entrepriseSelectionnee"
            class="font-sans text-small italic text-violet-400">
            Les champs ci-dessous sont pré-remplis avec les données de l'entreprise. Modifiez-les si le lieu ou le contact de la collecte diffère.
          </p>

          <!-- Adresse du lieu de collecte -->
          <div class="flex flex-col gap-1">
            <label class="font-sans text-small font-medium text-violet-800">Adresse du lieu de collecte (rue + n°)</label>
            <div class="flex gap-2">
              <input v-model="champRue" type="text" placeholder="Rue"
                class="flex-1 rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400"
                :class="{ 'ring-rouge-500 ring-1': champsInvalides.rue }" />
              <input v-model="champNumero" type="text" placeholder="N°"
                class="w-20 rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400"
                :class="{ 'ring-rouge-500 ring-1': champsInvalides.numero }" />
            </div>
          </div>

          <!-- NPA + Ville -->
          <div class="flex items-start gap-3">
            <div class="flex flex-col gap-1 w-28">
              <label class="font-sans text-small font-medium text-violet-800">NPA</label>
              <input v-model="champNpa" type="text" inputmode="numeric" maxlength="4" placeholder="1200"
                class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400"
                :class="{ 'ring-rouge-500 ring-1': champsInvalides.npa }" />
            </div>
            <div class="flex flex-1 flex-col gap-1">
              <label class="font-sans text-small font-medium text-violet-800">Ville</label>
              <input v-model="champVille" type="text" placeholder="Genève"
                class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400"
                :class="{ 'ring-rouge-500 ring-1': champsInvalides.ville }" />
            </div>
          </div>

          <!-- Email -->
          <div class="flex flex-col gap-1">
            <label class="font-sans text-small font-medium text-violet-800">Adresse e-mail</label>
            <input v-model="champEmail" type="email" placeholder="contact@entreprise.ch"
              class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400"
              :class="{ 'ring-rouge-500 ring-1': champsInvalides.email }" />
          </div>

          <!-- Téléphone -->
          <div class="flex flex-col gap-1">
            <label class="font-sans text-small font-medium text-violet-800">Téléphone</label>
            <input v-model="champTelephone" type="tel" placeholder="+41 22 000 00 00"
              class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400"
              :class="{ 'ring-rouge-500 ring-1': champsInvalides.telephone }" />
          </div>

          <!-- Couleurs + Logo -->
          <div class="flex flex-wrap items-start gap-6">
            <ColorField label="Couleur principale" v-model="couleurPrincipale" />
            <ColorField label="Couleur secondaire" v-model="couleurSecondaire" />
            <LogoUpload v-model="logoUrl" />
          </div>

          <!-- Dates -->
          <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
            <div class="flex flex-1 flex-col gap-1">
              <label class="font-sans text-small font-medium text-violet-800">Date de début</label>
              <CalendrierPicker
                v-model="champDateDebut"
                placeholder="Choisir une date de début…"
                :hasError="!!champsInvalides.dateDebut"
              />
            </div>
            <div class="flex flex-1 flex-col gap-1">
              <label class="font-sans text-small font-medium text-violet-800">Date de fin</label>
              <CalendrierPicker
                v-model="champDateFin"
                placeholder="Choisir une date de fin…"
                :min="champDateDebut"
                :hasError="!!champsInvalides.dateFin"
              />
              <p v-if="champDateDebut && !champDateFin"
                class="font-sans text-xs text-violet-400">Doit être postérieure à la date de début.</p>
            </div>
          </div>

          <hr class="border-violet-100" />

          <!-- Capacité -->
          <div class="flex flex-col gap-1 sm:w-48">
            <label class="font-sans text-small font-semibold text-violet-950">Capacité (créneaux disponibles)</label>
            <input v-model="champCapacity" type="number" min="1" placeholder="ex. 50"
              class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400"
              :class="{ 'ring-rouge-500 ring-1': champsInvalides.capacity }" />
          </div>

          <!-- Lien Onedoc -->
          <div class="flex flex-col gap-1">
            <label class="font-sans text-small font-semibold text-violet-950">Lien Onedoc</label>
            <p class="font-sans text-small text-violet-500">Créez la collecte sur Onedoc, puis copiez son URL ici.</p>
            <input v-model="champOnedocUrl" type="url" placeholder="https://onedoc.ch/…"
              class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400"
              :class="{ 'ring-rouge-500 ring-1': champsInvalides.onedocUrl }" />
          </div>

          <!-- Lien KDrive -->
          <div class="flex flex-col gap-1">
            <label class="font-sans text-small font-semibold text-violet-950">Lien KDrive du kit de communication</label>
            <p class="font-sans text-small text-violet-500">Lien vers le dossier KDrive contenant les fichiers co-brandés (affiches, flyers, visuels RS).</p>
            <input v-model="champKitUrl" type="url" placeholder="https://kdrive.infomaniak.com/…"
              class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400"
              :class="{ 'ring-rouge-500 ring-1': champsInvalides.kitUrl }" />
          </div>

          <!-- Soumission -->
          <div class="mt-2 flex justify-end">
            <button
              type="submit"
              :disabled="chargement"
              class="rounded-[40px] bg-violet-900 px-8 py-2.5 font-sans text-regular text-white transition-opacity hover:opacity-90 disabled:cursor-wait disabled:opacity-60"
            >{{ chargement ? 'Enregistrement…' : libelleBtn }}</button>
          </div>

        </template>

      </form>
    </div>
  </DashboardLayout>
</template>
