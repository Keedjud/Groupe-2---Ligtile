<script setup>
import { ref, watch, onMounted, computed } from 'vue'
import LabelCard from '../components/LabelCard.vue'
import { useLabelCompanies } from '@/composables/useLabelCompanies'

const { fetchApi } = useLabelCompanies()

// Image hero de la page Label
const heroImg = '/images/' + 'label_top.png'

// ===== État =====
const companies = ref([])
const loading = ref(true)
const error = ref(null)
const years = ref([])

// Pagination
const currentPage = ref(1)
const lastPage = ref(1)
const pagesToShow = computed(() => {
  const pages = []
  const start = Math.max(1, currentPage.value - 2)
  const end = Math.min(lastPage.value, currentPage.value + 2)
  for (let i = start; i <= end; i++) pages.push(i)
  return pages
})

// Filtres
const search = ref('')
const selectedYear = ref('')
const selectedSize = ref('')

// Debounce recherche
let searchTimer = null
function onSearchInput() {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => {
    currentPage.value = 1
    fetchCompanies()
  }, 300)
}

// ===== Chargement des données =====
function buildQuery(page) {
  const params = new URLSearchParams()
  params.set('page', page)
  if (search.value) params.set('search', search.value)
  if (selectedYear.value) params.set('year', selectedYear.value)
  if (selectedSize.value) params.set('size', selectedSize.value)
  return params.toString()
}

async function fetchCompanies() {
  error.value = null

  try {
    const data = await fetchApi({
      url: `/label-companies?${buildQuery(currentPage.value)}`,
      method: 'GET',
    })
    companies.value = data.data
    currentPage.value = data.current_page
    lastPage.value = data.last_page
  } catch (err) {
    error.value = err?.data?.message || err?.statusText || 'Erreur de chargement.'
  } finally {
    loading.value = false
  }
}

async function fetchYears() {
  try {
    years.value = await fetchApi({ url: '/label-years', method: 'GET' })
  } catch {
    // silencieux — le filtre année sera simplement vide
  }
}

function goToPage(page) {
  if (page < 1 || page > lastPage.value) return
  currentPage.value = page
  fetchCompanies()
  document.getElementById('companies-grid')?.scrollIntoView({ behavior: 'smooth' })
}

function resetFilters() {
  search.value = ''
  selectedYear.value = ''
  selectedSize.value = ''
  currentPage.value = 1
  fetchCompanies()
}

watch([selectedYear, selectedSize], () => {
  currentPage.value = 1
  fetchCompanies()
})

onMounted(() => {
  fetchCompanies()
  fetchYears()
})
</script>

<template>
  <div class="mx-auto max-w-[1512px] bg-brand-bg-beige">

    <!-- ===== Hero Section ===== -->
    <section class="flex flex-col items-center gap-10 px-3 py-10 lg:flex-row lg:gap-[160px] lg:px-[60px] lg:py-16">
      <!-- Image : première en mobile, à droite en desktop -->
      <img
        :src="heroImg"
        alt="Illustration Label CTS"
        class="h-[286px] w-[267px] object-contain lg:order-last lg:h-auto lg:max-h-[387px] lg:w-[361px]"
      />
      <div class="flex max-w-[762px] flex-col gap-6 lg:gap-[58px]">
        <h1 class="font-sans text-h1 font-semibold text-black">
          Votre engagement mérite d'être reconnu.
        </h1>
        <p class="font-sans text-regular text-black">
          Le Label CTS distingue les entreprises investies dans l'organisation et la promotion de collectes de sang en entreprise.<br /><br />
          Il valorise les démarches concrètes permettant de faciliter le don du sang auprès des collaborateurs et de renforcer l'engagement collectif autour de cette cause.
        </p>
      </div>
    </section>

    <!-- ===== Section "Comment obtenir le Label CTS ?" ===== -->
    <section class="flex flex-col items-center gap-6 px-3 pb-10 lg:gap-8 lg:px-[60px] lg:pb-16">
      <h2 class="text-center font-sans text-h1 font-semibold text-violet-900">
        Comment obtenir le Label CTS ?
      </h2>

      <!-- Version mobile : étapes empilées verticalement -->
      <div class="flex w-full flex-col items-center gap-0 rounded-[40px] bg-form-bg px-6 py-8 lg:hidden">
        <!-- Candidater -->
        <div class="flex flex-col items-center gap-1.5">
          <span class="font-sans text-h5 text-black">Candidater</span>
          <div class="flex h-[50px] w-[50px] items-center justify-center">
            <div class="flex h-[35px] w-[35px] items-center justify-center rounded-full bg-vert-400 text-white">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Ligne verticale -->
        <div class="my-0 h-[112px] w-[2px] rounded-[24px] bg-vert-400" />

        <!-- Organiser une collecte -->
        <div class="flex flex-col items-center gap-1.5">
          <span class="font-sans text-h5 text-black">Organiser une collecte</span>
          <div class="flex h-[50px] w-[50px] items-center justify-center">
            <div class="flex h-[35px] w-[35px] items-center justify-center rounded-full bg-vert-400 text-white">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Ligne verticale -->
        <div class="my-0 h-[112px] w-[2px] rounded-[24px] bg-vert-400" />

        <!-- Recevoir le label -->
        <div class="flex flex-col items-center gap-1.5">
          <span class="font-sans text-h5 text-black">Recevoir le label</span>
          <div class="flex h-[50px] w-[50px] items-center justify-center">
            <div class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-vert-400 bg-transparent">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-vert-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Version desktop : 3 étapes horizontales -->
      <div class="relative hidden w-full max-w-[1392px] flex-row items-center justify-between gap-0 rounded-[40px] border border-violet-900/30 bg-form-bg px-8 py-10 lg:flex lg:px-16">
        <!-- Candidater -->
        <div class="z-10 flex flex-col items-center gap-2 text-center">
          <div class="flex h-[50px] w-[50px] items-center justify-center">
            <div class="flex h-[35px] w-[35px] items-center justify-center rounded-full bg-vert-400 text-white">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
            </div>
          </div>
          <span class="font-sans text-h5 text-black">Candidater</span>
        </div>

        <div class="h-[3px] w-[440px] rounded-[24px] bg-vert-400" />

        <!-- Organiser une collecte -->
        <div class="z-10 flex flex-col items-center gap-2 text-center">
          <div class="flex h-[50px] w-[50px] items-center justify-center">
            <div class="flex h-[35px] w-[35px] items-center justify-center rounded-full bg-vert-400 text-white">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
            </div>
          </div>
          <span class="font-sans text-h5 text-black">Organiser une collecte</span>
        </div>

        <div class="h-[3px] w-[440px] rounded-[24px] bg-vert-400" />

        <!-- Recevoir le label -->
        <div class="z-10 flex flex-col items-center gap-2 text-center">
          <div class="flex h-[50px] w-[56px] items-center justify-center">
            <div class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-vert-400 bg-transparent">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-vert-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
            </div>
          </div>
          <span class="font-sans text-h5 text-black">Recevoir le label</span>
        </div>
      </div>

      <!-- Bouton CTA -->
      <a
        href="#/prendre-rdv"
        class="inline-flex h-[49px] items-center justify-center rounded-[40px] bg-violet-900 px-8 text-center font-sans text-h5 text-white transition-colors hover:bg-violet-800 lg:h-16 lg:w-full lg:max-w-[450px] lg:text-h3 lg:font-medium"
      >
        Organiser une collecte
      </a>
    </section>

    <!-- ===== Section "Les entreprises engagées à nos côtés" ===== -->
    <section class="flex flex-col gap-6 px-3 pb-10 lg:px-[60px] lg:pb-16">
      <div class="flex flex-col gap-6">
        <h2 class="font-sans text-h1 font-semibold text-black">
          Les entreprises engagées à nos côtés
        </h2>
        <p class="max-w-[1113px] font-sans text-h5 text-black">
          Découvrez les entreprises labellisées par le CTS pour leur engagement concret en faveur du don du sang.
        </p>
      </div>

      <!-- Barre de filtres -->
      <div class="flex items-center gap-3 rounded-[50px] bg-violet-50 px-4 py-2.5 lg:justify-between lg:gap-4 lg:px-[60px]">
        <!-- Filtres : scroll horizontal en mobile -->
        <div class="flex flex-nowrap items-center gap-[22px] overflow-x-auto lg:flex-wrap">
          <!-- Recherche -->
          <div class="flex shrink-0 items-center gap-3 rounded-[50px] bg-white px-3 shadow-[0_0_4px_rgba(0,0,0,0.25)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="11" cy="11" r="8" />
              <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input
              v-model="search"
              type="text"
              placeholder="Entreprise"
              class="w-[180px] bg-transparent py-3 font-sans text-regular text-violet-950 outline-none placeholder:text-violet-950 lg:w-[237px]"
              @input="onSearchInput"
            />
          </div>

          <!-- Filtre Année -->
          <div class="flex shrink-0 items-center gap-3 rounded-[50px] bg-white px-2.5 shadow-[0_0_4px_rgba(0,0,0,0.25)]">
            <select
              v-model="selectedYear"
              class="bg-transparent px-2.5 py-3 font-sans text-regular text-violet-950 outline-none"
            >
              <option value="">Labélisée depuis</option>
              <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
            </select>
          </div>

          <!-- Filtre Taille -->
          <div class="flex shrink-0 items-center gap-3 rounded-[50px] bg-white px-2.5 shadow-[0_0_4px_rgba(0,0,0,0.25)]">
            <select
              v-model="selectedSize"
              class="bg-transparent px-2.5 py-3 font-sans text-regular text-violet-950 outline-none"
            >
              <option value="">Taille de l'entreprise</option>
              <option value="grande">Grande entreprise</option>
              <option value="moyenne">Moyenne entreprise</option>
              <option value="petite">Petite entreprise</option>
            </select>
          </div>
        </div>

        <!-- Réinitialiser (desktop only) -->
        <button
          @click="resetFilters"
          class="hidden shrink-0 items-center gap-2.5 py-2.5 font-sans text-regular text-violet-800 transition-colors hover:text-violet-600 lg:flex"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
          </svg>
          Réinitialiser les filtres
        </button>
      </div>

      <!-- Grille des entreprises labellisées -->
      <div id="companies-grid" class="rounded-[10px] bg-violet-100 p-3 lg:px-[60px] lg:py-[57px]">
        <!-- État chargement -->
        <div v-if="loading" class="flex flex-col items-center justify-center gap-4 py-16">
          <div class="h-10 w-10 animate-spin rounded-full border-4 border-violet-200 border-t-violet-600"></div>
          <p class="font-sans text-regular text-violet-600">Chargement des entreprises...</p>
        </div>

        <!-- État erreur -->
        <div v-else-if="error" class="flex flex-col items-center justify-center gap-4 py-16">
          <p class="font-sans text-regular text-rouge-600">{{ error }}</p>
          <button @click="fetchCompanies" class="rounded-[40px] bg-violet-900 px-6 py-2 font-sans text-regular text-white transition-colors hover:bg-violet-800">
            Réessayer
          </button>
        </div>

        <!-- Grille -->
        <template v-else>
          <div v-if="companies.length === 0" class="py-16 text-center font-sans text-regular text-violet-600">
            Aucune entreprise trouvée pour ces critères.
          </div>
          <div v-else class="grid grid-cols-2 gap-4 lg:grid-cols-3 lg:gap-6 xl:grid-cols-4">
            <LabelCard v-for="company in companies" :key="company.id" :company="company" />
          </div>
        </template>

        <!-- Pagination -->
        <div v-if="!loading && lastPage > 1" class="mt-8 flex items-center justify-center gap-2 lg:mt-[50px] lg:gap-4">
          <button
            @click="goToPage(currentPage - 1)"
            :disabled="currentPage === 1"
            class="flex h-9 w-9 items-center justify-center rounded-full bg-form-bg transition-colors hover:bg-violet-100 disabled:opacity-40 lg:h-11 lg:w-11"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-violet-950" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" />
            </svg>
          </button>

          <button
            v-for="page in pagesToShow"
            :key="page"
            @click="goToPage(page)"
            class="flex h-9 w-9 items-center justify-center rounded-full font-sans text-regular transition-colors lg:h-11 lg:w-11"
            :class="page === currentPage
              ? 'bg-violet-400 text-violet-950 font-semibold'
              : 'bg-form-bg text-violet-950 hover:bg-violet-100'"
          >
            {{ page }}
          </button>

          <button
            @click="goToPage(currentPage + 1)"
            :disabled="currentPage === lastPage"
            class="flex h-9 w-9 items-center justify-center rounded-full bg-form-bg transition-colors hover:bg-violet-100 disabled:opacity-40 lg:h-11 lg:w-11"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-violet-950" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6" />
            </svg>
          </button>
        </div>
      </div>
    </section>

  </div>
</template>
