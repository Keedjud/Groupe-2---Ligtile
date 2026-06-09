<!-- CalendrierPicker — bouton + modale calendrier avec sélecteur d'année et validation de date minimale -->
<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  modelValue:  { type: String, default: '' },  // 'YYYY-MM-DD'
  hasError:    { type: Boolean, default: false },
  placeholder: { type: String, default: 'Choisir une date…' },
  min:         { type: String, default: '' },  // 'YYYY-MM-DD' — jours ≤ min désactivés
})
const emit = defineEmits(['update:modelValue'])

const ouvert  = ref(false)
const annee   = ref(new Date().getFullYear())
const mois    = ref(new Date().getMonth())   // 0–11
const modeVue = ref('jours')                // 'jours' | 'annees'

const NOMS_MOIS   = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                     'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre']
const EN_TETES    = ['L', 'M', 'M', 'J', 'V', 'S', 'D']
const ANNEE_BASE  = new Date().getFullYear()
// Plage affichée : -3 ans à +8 ans depuis aujourd'hui (12 ans)
const ANNEES      = Array.from({ length: 12 }, (_, i) => ANNEE_BASE - 3 + i)

function ouvrir() {
  const base = props.modelValue ? new Date(props.modelValue + 'T00:00:00') : new Date()
  annee.value   = base.getFullYear()
  mois.value    = base.getMonth()
  modeVue.value = 'jours'
  ouvert.value  = true
}

function fermer() { ouvert.value = false }

function moisPrec() {
  if (mois.value === 0) { mois.value = 11; annee.value-- }
  else mois.value--
}
function moisSuiv() {
  if (mois.value === 11) { mois.value = 0; annee.value++ }
  else mois.value++
}

function selectionnerAnnee(a) {
  annee.value   = a
  modeVue.value = 'jours'
}

// Cellules du calendrier (null = case vide avant le 1er du mois)
const cellules = computed(() => {
  const nbJours  = new Date(annee.value, mois.value + 1, 0).getDate()
  const decalage = (new Date(annee.value, mois.value, 1).getDay() + 6) % 7  // lundi = 0
  const c = []
  for (let i = 0; i < decalage; i++) c.push(null)
  for (let d = 1; d <= nbJours; d++) c.push(d)
  return c
})

function valISO(jour) {
  if (!jour) return null
  return `${annee.value}-${String(mois.value + 1).padStart(2, '0')}-${String(jour).padStart(2, '0')}`
}

// Un jour est désactivé si son ISO ≤ min (la date de fin doit être strictement après la date de début)
function estDesactive(jour) {
  if (!jour || !props.min) return false
  return valISO(jour) <= props.min
}

function selectionner(jour) {
  if (!jour || estDesactive(jour)) return
  emit('update:modelValue', valISO(jour))
  fermer()
}

const TODAY    = new Date().toISOString().slice(0, 10)
const affichage = computed(() => {
  if (!props.modelValue) return null
  const [y, m, d] = props.modelValue.split('-')
  return `${d}.${m}.${y}`
})
</script>

<template>
  <!-- Bouton déclencheur -->
  <button
    type="button"
    @click="ouvrir"
    class="flex w-full items-center gap-2 rounded-lg bg-white px-3 py-2.5 text-left font-sans text-small shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none transition-colors hover:bg-violet-50 focus:ring-2 focus:ring-violet-400"
    :class="hasError ? 'ring-rouge-500 ring-1' : ''"
  >
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-vert-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
    </svg>
    <span :class="affichage ? 'text-violet-950' : 'text-violet-400'">
      {{ affichage ?? placeholder }}
    </span>
  </button>

  <!-- Modale -->
  <Teleport to="body">
    <Transition name="fondu">
      <div
        v-if="ouvert"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
        @click.self="fermer"
      >
        <div class="w-72 rounded-[20px] bg-white p-5 shadow-xl">

          <!-- ── En-tête mode JOURS ── -->
          <div v-if="modeVue === 'jours'" class="mb-3 flex items-center justify-between">
            <button type="button" @click="moisPrec"
              class="flex h-8 w-8 items-center justify-center rounded-full text-violet-700 transition-colors hover:bg-violet-100">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
              </svg>
            </button>
            <div class="flex items-center gap-1 font-sans text-small font-semibold text-violet-900">
              <span>{{ NOMS_MOIS[mois] }}</span>
              <!-- Bouton année → ouvre le sélecteur d'année -->
              <button
                type="button"
                @click="modeVue = 'annees'"
                class="flex items-center gap-0.5 rounded px-1 py-0.5 text-violet-700 transition-colors hover:bg-violet-100"
                title="Choisir une année"
              >
                {{ annee }}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
              </button>
            </div>
            <button type="button" @click="moisSuiv"
              class="flex h-8 w-8 items-center justify-center rounded-full text-violet-700 transition-colors hover:bg-violet-100">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
              </svg>
            </button>
          </div>

          <!-- ── En-tête mode ANNÉES ── -->
          <div v-else class="mb-3 flex items-center justify-between">
            <div></div>
            <span class="font-sans text-small font-semibold text-violet-900">Sélectionner une année</span>
            <button type="button" @click="modeVue = 'jours'"
              class="flex h-8 w-8 items-center justify-center rounded-full text-violet-400 transition-colors hover:bg-violet-100 hover:text-violet-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <!-- ── Corps mode JOURS ── -->
          <template v-if="modeVue === 'jours'">
            <!-- En-têtes jours -->
            <div class="mb-1 grid grid-cols-7 text-center">
              <span v-for="(j, i) in EN_TETES" :key="i"
                class="py-1 font-sans text-xs font-semibold text-violet-400">{{ j }}</span>
            </div>
            <!-- Grille jours -->
            <div class="grid grid-cols-7 gap-y-0.5 text-center">
              <button
                v-for="(jour, i) in cellules"
                :key="i"
                type="button"
                @click="selectionner(jour)"
                class="mx-auto flex h-8 w-8 items-center justify-center rounded-full font-sans text-small transition-colors"
                :class="[
                  !jour ? 'invisible pointer-events-none' : '',
                  estDesactive(jour)
                    ? 'cursor-not-allowed text-violet-200 pointer-events-none'
                    : valISO(jour) === modelValue
                      ? 'bg-violet-900 font-semibold text-white'
                      : valISO(jour) === TODAY
                        ? 'font-bold text-violet-900 ring-1 ring-violet-400 hover:bg-violet-100'
                        : jour ? 'text-violet-800 hover:bg-violet-100' : ''
                ]"
              >{{ jour }}</button>
            </div>
            <!-- Effacer -->
            <div v-if="modelValue" class="mt-3 text-center">
              <button type="button"
                @click="emit('update:modelValue', ''); fermer()"
                class="font-sans text-xs text-violet-400 underline hover:text-violet-600">
                Effacer la date
              </button>
            </div>
          </template>

          <!-- ── Corps mode ANNÉES ── -->
          <template v-else>
            <div class="grid grid-cols-3 gap-1.5">
              <button
                v-for="a in ANNEES"
                :key="a"
                type="button"
                @click="selectionnerAnnee(a)"
                class="rounded-lg py-2 font-sans text-small font-medium transition-colors"
                :class="a === annee
                  ? 'bg-violet-900 text-white'
                  : a === ANNEE_BASE
                    ? 'text-violet-900 ring-1 ring-violet-400 hover:bg-violet-100'
                    : 'text-violet-700 hover:bg-violet-100'"
              >{{ a }}</button>
            </div>
          </template>

        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<style scoped>
.fondu-enter-active, .fondu-leave-active { transition: opacity 0.15s ease; }
.fondu-enter-from, .fondu-leave-to { opacity: 0; }
</style>
