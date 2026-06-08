<!-- CompanyDetail — fiche entreprise avec édition inline -->
<script setup>
import { ref, computed, onMounted } from 'vue'
import DashboardLayout from '../layouts/DashboardLayout.vue'
import { useCompanies } from '../composables/useCompanies.js'

const props = defineProps({
  idEntreprise: { type: [String, Number], required: true },
  allerVers:    { type: Function, required: true },
})

const { chargement, chargerEntreprise, mettreAJourEntreprise, supprimerEntreprise } = useCompanies()

const entreprise = ref(null)
const modeEdition = ref(false)
const champsInvalides = ref({})
const erreurServeur = ref('')
const confirmeSuppression = ref(false)
const erreurSuppression = ref('')

async function gererSuppression() {
  if (!confirmeSuppression.value) {
    confirmeSuppression.value = true
    return
  }
  erreurSuppression.value = ''
  try {
    await supprimerEntreprise(props.idEntreprise)
    props.allerVers('#/entreprises')
  } catch (e) {
    erreurSuppression.value = e?.data?.message || 'Erreur lors de la suppression.'
    confirmeSuppression.value = false
  }
}

// Champs formulaire
const champNom       = ref('')
const champNbEmployes = ref('')
const champRue       = ref('')
const champNumero    = ref('')
const champNpa       = ref('')
const champVille     = ref('')
const champEmail     = ref('')
const champTelephone = ref('')

onMounted(async () => {
  entreprise.value = await chargerEntreprise(props.idEntreprise)
})

function ouvrirEdition() {
  const e = entreprise.value
  champNom.value        = e.nom
  champNbEmployes.value = e.nb_employes
  champRue.value        = e.adresse.rue
  champNumero.value     = e.adresse.numero
  champNpa.value        = e.adresse.npa
  champVille.value      = e.adresse.ville
  champEmail.value      = e.contact.email
  champTelephone.value  = e.contact.telephone
  champsInvalides.value = {}
  erreurServeur.value   = ''
  modeEdition.value     = true
}

function annulerEdition() {
  modeEdition.value = false
  champsInvalides.value = {}
  erreurServeur.value = ''
}

const regexEmail   = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
const regexNpa     = /^\d{4}$/
const regexNumero  = /^\d+[a-zA-Z]?$/

function valider() {
  const e = {}
  if (!champNom.value.trim())                                      e.nom        = "Nom requis."
  if (!champNbEmployes.value || Number(champNbEmployes.value) < 1) e.nbEmployes = "Nombre d'employé·es requis (≥ 1)."
  if (!champRue.value.trim())                                      e.rue        = 'Rue requise.'
  if (!regexNumero.test(champNumero.value.trim()))                 e.numero     = 'Numéro invalide (ex. 12 ou 12b).'
  if (!regexNpa.test(champNpa.value.trim()))                      e.npa        = 'NPA invalide (4 chiffres).'
  if (!champVille.value.trim())                                    e.ville      = 'Ville requise.'
  if (!regexEmail.test(champEmail.value.trim()))                   e.email      = 'E-mail invalide.'
  champsInvalides.value = e
  return Object.keys(e).length === 0
}

async function sauvegarder() {
  erreurServeur.value = ''
  if (!valider()) return
  try {
    entreprise.value = await mettreAJourEntreprise(props.idEntreprise, {
      nom:        champNom.value.trim(),
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
    modeEdition.value = false
  } catch (e) {
    erreurServeur.value = e?.data?.message || "Une erreur est survenue lors de l'enregistrement."
  }
}

function formaterDate(dateStr) {
  if (!dateStr) return ''
  return new Date(dateStr).toLocaleDateString('fr-CH', {
    day: '2-digit', month: '2-digit', year: 'numeric',
  })
}

function statutCollecte(col) {
  const now   = new Date()
  const debut = new Date(col.date_debut)
  const fin   = new Date(col.date_fin)
  if (now < debut) return { label: 'À venir',   cls: 'bg-violet-100 text-violet-700' }
  if (now > fin)   return { label: 'Terminée',  cls: 'bg-gray-100 text-gray-500' }
  return               { label: 'En cours',   cls: 'bg-vert-100 text-vert-700' }
}

const aDesErreurs = computed(() => Object.keys(champsInvalides.value).length > 0)
</script>

<template>
  <DashboardLayout vueCourante="entreprises" :allerVers="allerVers">

    <!-- Chargement -->
    <div v-if="!entreprise && chargement" class="flex items-center justify-center py-16">
      <span class="font-sans text-regular text-violet-400">Chargement…</span>
    </div>

    <!-- Introuvable -->
    <div v-else-if="!entreprise" class="flex items-center justify-center py-16">
      <p class="font-sans text-h4 text-violet-700">Entreprise introuvable.</p>
    </div>

    <template v-else>

      <!-- Fiche -->
      <div class="mx-auto max-w-2xl rounded-[20px] bg-form-bg p-6 shadow-[0_4px_16px_rgba(104,23,100,0.10)]">

        <!-- En-tête -->
        <div class="mb-5 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
          <h1 class="font-sans text-h3 font-bold text-violet-800">{{ entreprise.nom }}</h1>
          <div class="flex flex-wrap gap-3 sm:justify-end">
            <button
              v-if="!modeEdition"
              @click="allerVers('#/analytics?company_id=' + entreprise.id)"
              class="rounded-[40px] bg-white px-4 py-2 font-sans text-small text-texte-secondary underline shadow-[0_0_4px_rgba(0,0,0,0.15)] hover:bg-violet-50 transition-colors"
            >Analytics</button>
            <button
              v-if="!modeEdition"
              @click="ouvrirEdition"
              class="rounded-[40px] bg-violet-900 px-5 py-2 font-sans text-small text-white hover:opacity-90 transition-opacity"
            >Modifier</button>
          </div>
        </div>

        <!-- Mode lecture -->
        <template v-if="!modeEdition">
          <div class="mb-6 grid grid-cols-1 gap-5 sm:grid-cols-2">
            <!-- Siège social -->
            <div class="flex flex-col gap-1">
              <p class="font-sans text-xs font-semibold uppercase tracking-wide text-violet-400">Siège social</p>
              <p class="font-sans text-small text-violet-950">
                {{ entreprise.adresse.rue }} {{ entreprise.adresse.numero }}<br/>
                {{ entreprise.adresse.npa }} {{ entreprise.adresse.ville }}
              </p>
            </div>
            <!-- Effectif -->
            <div class="flex flex-col gap-1">
              <p class="font-sans text-xs font-semibold uppercase tracking-wide text-violet-400">Effectif</p>
              <p class="font-sans text-small text-violet-950">
                {{ entreprise.nb_employes.toLocaleString('fr-CH') }} employé·es
              </p>
              <p class="font-sans text-xs text-violet-400">{{ entreprise.taille }}</p>
            </div>
            <!-- Contact référent -->
            <div class="flex flex-col gap-1">
              <p class="font-sans text-xs font-semibold uppercase tracking-wide text-violet-400">E-mail contact</p>
              <p class="font-sans text-small text-violet-950">{{ entreprise.contact.email || '—' }}</p>
            </div>
            <div class="flex flex-col gap-1">
              <p class="font-sans text-xs font-semibold uppercase tracking-wide text-violet-400">Téléphone</p>
              <p class="font-sans text-small text-violet-950">{{ entreprise.contact.telephone || '—' }}</p>
            </div>
          </div>
        </template>

        <!-- Mode édition -->
        <template v-else>
          <div
            v-if="aDesErreurs || erreurServeur"
            class="mb-4 flex items-start gap-3 rounded-xl border border-rouge-500 bg-rouge-500/10 p-4"
          >
            <div class="font-sans text-small text-rouge-600">
              <p class="font-semibold">Merci de corriger les points suivants :</p>
              <ul class="mt-1 list-disc pl-4">
                <li v-for="(msg, champ) in champsInvalides" :key="champ">{{ msg }}</li>
                <li v-if="erreurServeur">{{ erreurServeur }}</li>
              </ul>
            </div>
          </div>

          <form @submit.prevent="sauvegarder" class="flex flex-col gap-4">

            <!-- Nom + Nb employés -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
              <div class="flex flex-1 flex-col gap-1">
                <label class="font-sans text-small font-medium text-violet-800">Nom de l'entreprise</label>
                <input v-model="champNom" type="text"
                  class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400"
                  :class="{ 'ring-rouge-500 ring-1': champsInvalides.nom }" />
              </div>
              <div class="flex flex-col gap-1 sm:w-36">
                <label class="font-sans text-small font-medium text-violet-800">Nb d'employé·es</label>
                <input v-model="champNbEmployes" type="number" min="1"
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

            <!-- Contact référent -->
            <div class="flex flex-col gap-1">
              <label class="font-sans text-small font-semibold text-violet-950">E-mail contact référent</label>
              <input v-model="champEmail" type="email"
                class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400"
                :class="{ 'ring-rouge-500 ring-1': champsInvalides.email }" />
            </div>
            <div class="flex flex-col gap-1">
              <label class="font-sans text-small font-medium text-violet-800">Téléphone (optionnel)</label>
              <input v-model="champTelephone" type="tel"
                class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400" />
            </div>

            <!-- Actions -->
            <div class="mt-2 flex flex-col gap-3 sm:flex-row sm:justify-end">
              <button type="button" @click="annulerEdition"
                class="rounded-[40px] bg-white px-6 py-2.5 font-sans text-regular text-texte-secondary shadow-[0_0_4px_rgba(0,0,0,0.15)] hover:bg-violet-50 transition-colors"
              >Annuler</button>
              <button type="submit" :disabled="chargement"
                class="rounded-[40px] bg-violet-900 px-8 py-2.5 font-sans text-regular text-white hover:opacity-90 transition-opacity disabled:cursor-wait disabled:opacity-60"
              >{{ chargement ? 'Enregistrement…' : 'Enregistrer' }}</button>
            </div>
          </form>
        </template>

        <!-- Barre d'actions bas -->
        <div v-if="!modeEdition" class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
          <button
            @click="allerVers('#/entreprises')"
            class="rounded-[40px] bg-white px-5 py-2 font-sans text-small text-texte-secondary underline shadow-[0_0_4px_rgba(0,0,0,0.15)] hover:bg-violet-50 transition-colors"
          >← Retour</button>

          <div class="flex flex-col gap-1 sm:items-end">
            <button
              @click="gererSuppression"
              :disabled="chargement"
              class="rounded-[40px] px-5 py-2 font-sans text-small text-white transition-opacity hover:opacity-90 disabled:opacity-60"
              :class="confirmeSuppression ? 'bg-rouge-700' : 'bg-rouge-500'"
            >{{ confirmeSuppression ? 'Confirmer la suppression' : 'Supprimer' }}</button>
            <p v-if="erreurSuppression" class="font-sans text-xs text-rouge-600">{{ erreurSuppression }}</p>
          </div>
        </div>
      </div>

      <!-- Liste des collectes -->
      <div v-if="!modeEdition && entreprise.collectes.length" class="mx-auto mt-6 max-w-2xl rounded-[20px] bg-white p-5 shadow-[0_0_8px_rgba(104,23,100,0.10)]">
        <h2 class="mb-3 font-sans text-h5 font-semibold text-violet-900">
          Collectes ({{ entreprise.collectes.length }})
        </h2>
        <div class="grid gap-3 sm:hidden">
          <div
            v-for="col in entreprise.collectes"
            :key="col.id"
            class="rounded-[18px] bg-violet-50/40 p-4 shadow-[0_0_4px_rgba(104,23,100,0.08)]"
          >
            <div class="flex items-start justify-between gap-3">
              <div>
                <p class="font-sans text-xs uppercase tracking-wide text-violet-400">Période</p>
                <p class="mt-1 font-sans text-small font-semibold text-violet-950">{{ formaterDate(col.date_debut) }} - {{ formaterDate(col.date_fin) }}</p>
              </div>
              <span class="rounded-full px-2.5 py-1 font-sans text-xs font-medium" :class="statutCollecte(col).cls">
                {{ statutCollecte(col).label }}
              </span>
            </div>
            <div class="mt-3 grid grid-cols-2 gap-3 text-small text-violet-700">
              <div>
                <p class="font-sans text-xs uppercase tracking-wide text-violet-400">Capacité</p>
                <p class="mt-1 font-medium text-violet-900">{{ col.capacity }}</p>
              </div>
              <div class="text-right">
                <button
                  @click="allerVers('#/collectes/' + col.id)"
                  class="rounded-[40px] bg-white px-3 py-1 font-sans text-xs text-texte-secondary underline shadow-[0_0_4px_rgba(0,0,0,0.15)] hover:bg-violet-50 transition-colors"
                >Voir</button>
              </div>
            </div>
          </div>
        </div>
        <div class="hidden sm:block overflow-x-auto">
        <table class="w-full min-w-[480px] font-sans text-small">
          <thead>
            <tr class="border-b border-violet-100 text-left text-violet-700">
              <th class="pb-2 pr-4">Début</th>
              <th class="pb-2 pr-4">Fin</th>
              <th class="pb-2 pr-4">Statut</th>
              <th class="pb-2 text-right">Capacité</th>
              <th class="pb-2"></th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(col, i) in entreprise.collectes"
              :key="col.id"
              class="border-b border-violet-50 last:border-0"
              :class="i % 2 === 0 ? '' : 'bg-violet-50/20'"
            >
              <td class="py-2 pr-4">{{ formaterDate(col.date_debut) }}</td>
              <td class="py-2 pr-4">{{ formaterDate(col.date_fin) }}</td>
              <td class="py-2 pr-4">
                <span class="rounded-full px-2.5 py-0.5 font-sans text-xs font-medium" :class="statutCollecte(col).cls">
                  {{ statutCollecte(col).label }}
                </span>
              </td>
              <td class="py-2 pr-4 text-right text-violet-700">{{ col.capacity }}</td>
              <td class="py-2 text-right">
                <button
                  @click="allerVers('#/collectes/' + col.id)"
                  class="rounded-[40px] bg-white px-3 py-1 font-sans text-xs text-texte-secondary underline shadow-[0_0_4px_rgba(0,0,0,0.15)] hover:bg-violet-50 transition-colors"
                >Voir</button>
              </td>
            </tr>
          </tbody>
        </table>
        </div>
      </div>

    </template>
  </DashboardLayout>
</template>
