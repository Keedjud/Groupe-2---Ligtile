<!-- DateField - sélecteur JJ/MM/AAAA -->
<script setup>
import { ref, computed, watch } from 'vue'

const props = defineProps({
  modelValue: { type: String, default: '' }, // 'yyyy-mm-dd'
  hasError:   { type: Boolean, default: false },
})
const emit = defineEmits(['update:modelValue'])

const nomsMois = [
  'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
  'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre',
]

const anneeCourante = new Date().getFullYear()
const annees = []
for (let a = anneeCourante - 5; a <= anneeCourante + 6; a++) annees.push(a)

const jour = ref('')
const mois = ref('')
const annee = ref('')

function hydrater(valeur) {
  const m = (valeur || '').match(/^(\d{4})-(\d{2})-(\d{2})$/)
  if (m) {
    annee.value = m[1]
    mois.value = m[2]
    jour.value = m[3]
  }
}
hydrater(props.modelValue)
watch(() => props.modelValue, hydrater)

// Jours du mois
const jours = computed(() => {
  const a = Number(annee.value) || anneeCourante
  const mo = Number(mois.value) || 1
  const nb = new Date(a, mo, 0).getDate()
  return Array.from({ length: nb }, (_, i) => i + 1)
})

// Émission date ISO
watch([jour, mois, annee], () => {
  if (jour.value && mois.value && annee.value) {
    const max = new Date(Number(annee.value), Number(mois.value), 0).getDate()
    if (Number(jour.value) > max) jour.value = String(max).padStart(2, '0')
    emit('update:modelValue', `${annee.value}-${mois.value}-${jour.value}`)
  }
})

const classeSelect = computed(() => [
  'rounded-lg bg-white px-3 py-2.5 font-sans text-small text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none focus:ring-2 focus:ring-violet-400',
  props.hasError ? 'ring-rouge-500 ring-1' : '',
])
</script>

<template>
  <div class="flex gap-2">
    <select v-model="jour" :class="classeSelect" class="w-20">
      <option value="" disabled>JJ</option>
      <option v-for="j in jours" :key="j" :value="String(j).padStart(2, '0')">{{ j }}</option>
    </select>
    <select v-model="mois" :class="classeSelect" class="flex-1">
      <option value="" disabled>Mois</option>
      <option v-for="(nom, i) in nomsMois" :key="i" :value="String(i + 1).padStart(2, '0')">{{ nom }}</option>
    </select>
    <select v-model="annee" :class="classeSelect" class="w-24">
      <option value="" disabled>AAAA</option>
      <option v-for="a in annees" :key="a" :value="String(a)">{{ a }}</option>
    </select>
  </div>
</template>
