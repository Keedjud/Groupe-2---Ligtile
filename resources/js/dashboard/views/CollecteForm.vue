<!-- Formulaire création ET édition d'une collecte (prop mode = 'create' | 'edit') -->
<script setup>
import { ref, computed, onMounted } from 'vue'
import DashboardLayout from '../layouts/DashboardLayout.vue'
import ColorField      from '../components/ColorField.vue'
import LogoUpload      from '../components/LogoUpload.vue'
import { useCollectes } from '../composables/useCollectes.js'

const props = defineProps({
  mode:      { type: String, default: 'create' }, // 'create' | 'edit'
  idCollecte:{ type: [String, Number], default: null },
  allerVers: { type: Function, required: true },
})

const { trouverParId, creerCollecte, mettreAJourCollecte, chargement } = useCollectes()

// Champs du formulaire
const champNom         = ref('')
const champNbEmployes  = ref('')
const champRue         = ref('')
const champNumero      = ref('')
const champNpa         = ref('')
const champVille       = ref('')
const champEmail       = ref('')
const champTelephone   = ref('')
const couleurPrincipale = ref('#681764')
const couleurSecondaire = ref('#C44444')
const logoUrl          = ref(null)
const champDateDebut   = ref('')
const champDateFin     = ref('')

const champsInvalides = ref({})

// Préchargement en mode édition
onMounted(() => {
  if (props.mode === 'edit' && props.idCollecte) {
    const collecte = trouverParId(props.idCollecte)
    if (collecte) {
      champNom.value          = collecte.entreprise.nom
      champNbEmployes.value   = collecte.entreprise.nb_employes
      champRue.value          = collecte.adresse.rue
      champNumero.value       = collecte.adresse.numero
      champNpa.value          = collecte.adresse.npa
      champVille.value        = collecte.adresse.ville
      champEmail.value        = collecte.entreprise.email
      champTelephone.value    = collecte.entreprise.telephone
      couleurPrincipale.value = collecte.couleur_principale
      couleurSecondaire.value = collecte.couleur_secondaire
      logoUrl.value           = collecte.logo_url
      champDateDebut.value    = collecte.date_debut
      champDateFin.value      = collecte.date_fin
    }
  }
})

const titrePage = computed(() =>
  props.mode === 'edit' ? 'Modifier la collecte' : 'Création d\'une nouvelle collecte'
)
const libelleBtn = computed(() =>
  props.mode === 'edit' ? 'Enregistrer' : 'Créer la collecte'
)

// Validation des champs
function valider() {
  const e = {}
  if (!champNom.value.trim())        e.nom       = 'Champ requis.'
  if (!champEmail.value.includes('@')) e.email   = 'Email invalide.'
  if (!champRue.value.trim())        e.rue       = 'Champ requis.'
  if (!champNpa.value.trim())        e.npa       = 'Champ requis.'
  if (!champVille.value.trim())      e.ville     = 'Champ requis.'
  if (!champDateDebut.value)         e.dateDebut = 'Champ requis.'
  if (!champDateFin.value)           e.dateFin   = 'Champ requis.'
  if (champDateDebut.value && champDateFin.value && champDateFin.value <= champDateDebut.value) {
    e.dateFin = 'La date de fin doit être postérieure à la date de début.'
  }
  champsInvalides.value = e
  return Object.keys(e).length === 0
}

// Envoi du formulaire
async function soumettre() {
  if (!valider()) return

  const donneesEnvoi = {
    entreprise: {
      nom:         champNom.value.trim(),
      nb_employes: Number(champNbEmployes.value) || 0,
      email:       champEmail.value.trim(),
      telephone:   champTelephone.value.trim(),
    },
    adresse: {
      rue:    champRue.value.trim(),
      numero: champNumero.value.trim(),
      npa:    champNpa.value.trim(),
      ville:  champVille.value.trim(),
    },
    date_debut:         champDateDebut.value,
    date_fin:           champDateFin.value,
    couleur_principale: couleurPrincipale.value,
    couleur_secondaire: couleurSecondaire.value,
    logo_url:           logoUrl.value,
  }

  if (props.mode === 'create') {
    const nouvelle = await creerCollecte(donneesEnvoi)
    props.allerVers('#/collectes/' + nouvelle.id)
  } else {
    await mettreAJourCollecte(props.idCollecte, donneesEnvoi)
    props.allerVers('#/collectes/' + props.idCollecte)
  }
}
</script>

<template>
  <DashboardLayout
    :vueCourante="'collectes'"
    :allerVers="allerVers"
  >
    <!-- Carte formulaire -->
    <div class="mx-auto max-w-2xl rounded-[20px] bg-form-bg p-6 shadow-[0_4px_16px_rgba(104,23,100,0.10)]">

      <!-- Titre -->
      <h1 class="mb-6 text-center font-sans text-h3 font-bold text-violet-800">
        {{ titrePage }}
      </h1>

      <form @submit.prevent="soumettre" class="flex flex-col gap-5">

        <!-- Ligne : Nom entreprise + Nb employés -->
        <div class="flex flex-col gap-4 sm:flex-row">
          <div class="flex flex-1 flex-col gap-1">
            <label class="font-sans text-small font-semibold text-violet-950">Nom de l'entreprise</label>
            <input v-model="champNom" type="text" placeholder="Nom de l'entreprise" required
              class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400" :class="{ 'ring-rouge-500 ring-1': champsInvalides.nom }" />
            <span v-if="champsInvalides.nom" class="font-sans text-small text-rouge-500">{{ champsInvalides.nom }}</span>
          </div>
          <div class="flex flex-col gap-1 sm:w-40">
            <label class="font-sans text-small font-semibold text-violet-950">Nb d'employé·es</label>
            <input v-model="champNbEmployes" type="number" min="1" placeholder="ex. 500" required
              class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400" />
          </div>
        </div>

        <!-- Adresse -->
        <div class="flex flex-col gap-1">
          <label class="font-sans text-small font-semibold text-violet-950">Adresse (rue + n°)</label>
          <div class="flex gap-2">
            <input v-model="champRue" type="text" placeholder="Rue" required
              class="flex-1 rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400" :class="{ 'ring-rouge-500 ring-1': champsInvalides.rue }" />
            <input v-model="champNumero" type="text" placeholder="N°" required
              class="w-20 rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400" />
          </div>
          <span v-if="champsInvalides.rue" class="font-sans text-small text-rouge-500">{{ champsInvalides.rue }}</span>
        </div>

        <!-- NPA + Ville -->
        <div class="flex gap-3">
          <div class="flex flex-col gap-1 w-28">
            <label class="font-sans text-small font-semibold text-violet-950">NPA</label>
            <input v-model="champNpa" type="text" placeholder="1200" required
              class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400" :class="{ 'ring-rouge-500 ring-1': champsInvalides.npa }" />
            <span v-if="champsInvalides.npa" class="font-sans text-small text-rouge-500">{{ champsInvalides.npa }}</span>
          </div>
          <div class="flex flex-1 flex-col gap-1">
            <label class="font-sans text-small font-semibold text-violet-950">Ville</label>
            <input v-model="champVille" type="text" placeholder="Genève" required
              class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400" :class="{ 'ring-rouge-500 ring-1': champsInvalides.ville }" />
            <span v-if="champsInvalides.ville" class="font-sans text-small text-rouge-500">{{ champsInvalides.ville }}</span>
          </div>
        </div>

        <!-- Email -->
        <div class="flex flex-col gap-1">
          <label class="font-sans text-small font-semibold text-violet-950">Adresse e-mail</label>
          <input v-model="champEmail" type="email" placeholder="contact@entreprise.ch" required
            class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400" :class="{ 'ring-rouge-500 ring-1': champsInvalides.email }" />
          <span v-if="champsInvalides.email" class="font-sans text-small text-rouge-500">{{ champsInvalides.email }}</span>
        </div>

        <!-- Téléphone -->
        <div class="flex flex-col gap-1">
          <label class="font-sans text-small font-semibold text-violet-950">Téléphone</label>
          <input v-model="champTelephone" type="tel" placeholder="+41 22 000 00 00" required
            class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400" />
        </div>

        <!-- Couleurs + Logo -->
        <div class="flex flex-wrap items-end gap-6">
          <ColorField label="Couleur principale" v-model="couleurPrincipale" />
          <ColorField label="Couleur secondaire" v-model="couleurSecondaire" />
          <LogoUpload v-model="logoUrl" />
        </div>

        <!-- Dates -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end">
          <div class="flex flex-1 flex-col gap-1">
            <label class="flex items-center gap-2 font-sans text-small font-semibold text-violet-950">
              <!-- Icône calendrier vert -->
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-vert-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" /><line x1="16" y1="2" x2="16" y2="6" /><line x1="8" y1="2" x2="8" y2="6" /><line x1="3" y1="10" x2="21" y2="10" />
              </svg>
              Date de début
            </label>
            <input v-model="champDateDebut" type="date" lang="fr-CH" required
              class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400" :class="{ 'ring-rouge-500 ring-1': champsInvalides.dateDebut }" />
            <span v-if="champsInvalides.dateDebut" class="font-sans text-small text-rouge-500">{{ champsInvalides.dateDebut }}</span>
          </div>
          <div class="flex flex-1 flex-col gap-1">
            <label class="flex items-center gap-2 font-sans text-small font-semibold text-violet-950">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-vert-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" /><line x1="16" y1="2" x2="16" y2="6" /><line x1="8" y1="2" x2="8" y2="6" /><line x1="3" y1="10" x2="21" y2="10" />
              </svg>
              Date de fin
            </label>
            <input v-model="champDateFin" type="date" lang="fr-CH" :min="champDateDebut" required
              class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400" :class="{ 'ring-rouge-500 ring-1': champsInvalides.dateFin }" />
            <span v-if="champsInvalides.dateFin" class="font-sans text-small text-rouge-500">{{ champsInvalides.dateFin }}</span>
          </div>
        </div>

        <!-- Bouton de soumission -->
        <div class="mt-2 flex justify-end">
          <button
            type="submit"
            :disabled="chargement"
            class="rounded-[40px] bg-violet-900 px-8 py-2.5 font-sans text-regular text-white transition-opacity hover:opacity-90 disabled:cursor-wait disabled:opacity-60"
          >
            {{ chargement ? 'Enregistrement…' : libelleBtn }}
          </button>
        </div>

      </form>
    </div>
  </DashboardLayout>
</template>

