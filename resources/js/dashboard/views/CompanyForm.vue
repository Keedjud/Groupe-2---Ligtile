<!-- CompanyForm — création d'une nouvelle entreprise -->
<script setup>
import { ref, computed } from 'vue'
import DashboardLayout from '../layouts/DashboardLayout.vue'
import { useCompanies } from '../composables/useCompanies.js'
import { useOverlay }   from '../composables/useOverlay.js'

const props = defineProps({
  allerVers: { type: Function, required: true },
})

const { chargement, creerEntreprise } = useCompanies()
const { show: showOverlay } = useOverlay()

const champNom        = ref('')
const champNbEmployes = ref('')
const champRue        = ref('')
const champNumero     = ref('')
const champNpa        = ref('')
const champVille      = ref('')
const champEmail      = ref('')
const champTelephone  = ref('')

const champsInvalides = ref({})
const erreurServeur   = ref('')

const regexEmail  = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
const regexNpa    = /^\d{4}$/
const regexNumero = /^\d+[a-zA-Z]?$/
const regexTel    = /^[+\d][\d\s()/.-]{5,}$/

function valider() {
  const e = {}
  if (!champNom.value.trim())                                       e.nom        = "Nom requis."
  if (!champNbEmployes.value || Number(champNbEmployes.value) < 1)  e.nbEmployes = "Nombre d'employé·es requis (≥ 1)."
  if (!champRue.value.trim())                                       e.rue        = 'Rue requise.'
  if (!regexNumero.test(champNumero.value.trim()))                  e.numero     = 'Numéro invalide (ex. 12 ou 12b).'
  if (!regexNpa.test(champNpa.value.trim()))                        e.npa        = 'NPA invalide (4 chiffres).'
  if (!champVille.value.trim())                                     e.ville      = 'Ville requise.'
  if (!regexEmail.test(champEmail.value.trim()))                    e.email      = 'E-mail invalide.'
  if (!regexTel.test(champTelephone.value.trim()))                  e.telephone  = 'Téléphone requis (format valide).'
  champsInvalides.value = e
  return Object.keys(e).length === 0
}

async function soumettre() {
  erreurServeur.value = ''
  if (!valider()) return
  try {
    const nouvelle = await creerEntreprise({
      nom:         champNom.value.trim(),
      nb_employes: Number(champNbEmployes.value),
      adresse: {
        rue:    champRue.value.trim(),
        numero: champNumero.value.trim(),
        npa:    champNpa.value.trim(),
        ville:  champVille.value.trim(),
      },
      contact: {
        email:     champEmail.value.trim(),
        telephone: champTelephone.value.trim(),
      },
    })
    showOverlay("L'entreprise a bien été créée.")
    props.allerVers('#/entreprises/' + nouvelle.id)
  } catch (e) {
    erreurServeur.value = e?.data?.message || "Une erreur est survenue lors de la création."
  }
}

const aDesErreurs = computed(() => Object.keys(champsInvalides.value).length > 0)
</script>

<template>
  <DashboardLayout vueCourante="entreprises" :allerVers="allerVers">
    <div class="mx-auto max-w-2xl rounded-[20px] bg-form-bg p-4 shadow-[0_4px_16px_rgba(104,23,100,0.10)] sm:p-6">

      <h1 class="mb-6 text-center font-sans text-h3 font-bold text-violet-800">
        Nouvelle entreprise
      </h1>

      <!-- Erreurs -->
      <div v-if="aDesErreurs || erreurServeur"
        class="mb-5 flex items-start gap-3 rounded-xl border border-rouge-500 bg-rouge-500/10 p-4">
        <div class="font-sans text-small text-rouge-600">
          <p class="font-semibold">Merci de corriger les points suivants :</p>
          <ul class="mt-1 list-disc pl-4">
            <li v-for="(msg, champ) in champsInvalides" :key="champ">{{ msg }}</li>
            <li v-if="erreurServeur">{{ erreurServeur }}</li>
          </ul>
        </div>
      </div>

      <form @submit.prevent="soumettre" class="flex flex-col gap-4">

        <!-- Nom + Nb employés -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
          <div class="flex flex-1 flex-col gap-1">
            <label class="font-sans text-small font-medium text-violet-800">Nom de l'entreprise</label>
            <input v-model="champNom" type="text" placeholder="Nestlé"
              class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400"
              :class="{ 'ring-rouge-500 ring-1': champsInvalides.nom }" />
          </div>
          <div class="flex flex-col gap-1 sm:w-36">
            <label class="font-sans text-small font-medium text-violet-800">Nb d'employé·es</label>
            <input v-model="champNbEmployes" type="number" min="1" placeholder="ex. 1200"
              class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400"
              :class="{ 'ring-rouge-500 ring-1': champsInvalides.nbEmployes }" />
          </div>
        </div>

        <!-- Adresse siège -->
        <div class="flex flex-col gap-1">
          <label class="font-sans text-small font-semibold text-violet-950">Adresse du siège</label>
          <div class="flex flex-col gap-2 sm:flex-row">
            <input v-model="champRue" type="text" placeholder="Rue"
              class="flex-1 rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400"
              :class="{ 'ring-rouge-500 ring-1': champsInvalides.rue }" />
            <input v-model="champNumero" type="text" placeholder="N°"
              class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400 sm:w-20"
              :class="{ 'ring-rouge-500 ring-1': champsInvalides.numero }" />
          </div>
        </div>
        <div class="flex flex-col gap-3 sm:flex-row sm:items-start">
          <div class="flex flex-col gap-1 sm:w-28">
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

        <hr class="border-violet-100" />

        <!-- Contact référent -->
        <div class="flex flex-col gap-1">
          <label class="font-sans text-small font-semibold text-violet-950">E-mail contact référent</label>
          <input v-model="champEmail" type="email" placeholder="contact@entreprise.ch"
            class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400"
            :class="{ 'ring-rouge-500 ring-1': champsInvalides.email }" />
        </div>
        <div class="flex flex-col gap-1">
          <label class="font-sans text-small font-medium text-violet-800">Téléphone</label>
          <input v-model="champTelephone" type="tel" placeholder="+41 22 000 00 00"
            class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400"
            :class="{ 'ring-rouge-500 ring-1': champsInvalides.telephone }" />
        </div>

        <!-- Actions -->
        <div class="mt-2 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
          <button type="button" @click="allerVers('#/entreprises')"
            class="rounded-[40px] bg-white px-5 py-2.5 font-sans text-regular text-texte-secondary shadow-[0_0_4px_rgba(0,0,0,0.15)] hover:bg-violet-50 transition-colors"
          >Annuler</button>
          <button type="submit" :disabled="chargement"
            class="rounded-[40px] bg-violet-900 px-8 py-2.5 font-sans text-regular text-white hover:opacity-90 transition-opacity disabled:cursor-wait disabled:opacity-60"
          >{{ chargement ? 'Création…' : 'Créer l\'entreprise' }}</button>
        </div>

      </form>
    </div>
  </DashboardLayout>
</template>
