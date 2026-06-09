<script setup>
import { reactive, ref, computed, watch, onMounted, onUnmounted } from 'vue'
import { useContactForm } from '../composables/useContactForm'

const form = reactive({
  company_name: '', employees_count: '',
  street: '', postal_code: '', city: '',
  email: '', phone: '',
})

const showPmeMessage = ref(false)
const status = ref({ type: '', message: '' }) // succès uniquement

const { formErrors, globalError, submitting, validate, submit: submitContact } = useContactForm()
const aDesErreurs = computed(() => Object.keys(formErrors.value).length > 0)

watch(form, () => {
  if (Object.keys(formErrors.value).length > 0) validate(form)
}, { deep: true })

async function submit() {
  status.value = { type: '', message: '' }
  showPmeMessage.value = false

  if (!validate(form)) return

  // Seules les entreprises de 1000+ employés peuvent accueillir une collecte.
  if (Number(form.employees_count) < 1000) {
    showPmeMessage.value = true
    return
  }

  try {
    await submitContact(form)
    status.value = { type: 'success', message: 'Votre demande a bien été envoyée. Le CTS vous recontactera prochainement.' }
  } catch {
    // globalError est défini par le composable
  }
}

// Défilement vers le formulaire quand on arrive via "Prendre RDV" (#/prendre-rdv).
function scrollToForm() {
  const el = document.getElementById('prendre-rdv-form')
  if (!el) return
  const isDesktop = window.innerWidth >= 1024
  el.scrollIntoView({ behavior: 'smooth', block: isDesktop ? 'center' : 'start' })
}

function handleHashChange() {
  if (window.location.hash === '#/prendre-rdv') scrollToForm()
}

onMounted(() => {
  handleHashChange()
  window.addEventListener('hashchange', handleHashChange)
})

onUnmounted(() => {
  window.removeEventListener('hashchange', handleHashChange)
})

const pourquoiScroll = ref(null)
const pourquoiIndex = ref(0)
function updatePourquoiIndex() {
  const el = pourquoiScroll.value
  if (!el) return
  pourquoiIndex.value = Math.round(el.scrollLeft / (el.scrollWidth / 4))
}

const cardsScroll = ref(null)
const cardsIndex = ref(0)
function updateCardsIndex() {
  const el = cardsScroll.value
  if (!el) return
  cardsIndex.value = Math.round(el.scrollLeft / (el.scrollWidth / 3))
}
</script>

<template>
  <!-- Hero -->
  <section class="py-10">
    <div class="px-4 lg:px-[60px]">
      <div class="flex flex-col-reverse gap-8 lg:grid lg:grid-cols-2 lg:gap-10 lg:items-center">
        <div>
          <h1 class="text-h1 font-semibold text-texte-primary-dark leading-tight max-w-[260px] lg:max-w-none">
            L’engagement, nous l’avons dans
            <span class="text-violet-500">le sang</span>.
          </h1>
          <p class="text-small lg:text-h4 text-texte-primary-dark mt-6">
            Organisez une collecte de sang dans votre entreprise genevoise. Le CTS vous accompagne de A à Z.
          </p>
          <ul class="mt-8 space-y-4">
            <li>
              <a href="#/informations" class="flex items-center justify-between rounded-2xl bg-form-bg p-3 lg:bg-transparent lg:p-0 lg:rounded-none">
                <span class="flex items-center gap-3 lg:gap-4">
                  <span class="grid h-[51px] w-[51px] place-items-center rounded-full bg-violet-200 shrink-0">
                    <img :src="'/images/icons/check.png'" alt="" class="h-6 w-6" />
                    <!--Image purement décorative, pas besoin d'alt, bonne pratique-->
                  </span>
                  <span class="text-regular text-texte-primary-dark">Simple à mettre en place</span>
                </span>
                <svg class="h-5 w-5 text-texte-primary-dark lg:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
              </a>
            </li>
            <li>
              <a href="#/informations" class="flex items-center justify-between rounded-2xl bg-form-bg p-3 lg:bg-transparent lg:p-0 lg:rounded-none">
                <span class="flex items-center gap-3 lg:gap-4">
                  <span class="grid h-[51px] w-[51px] place-items-center rounded-full bg-violet-200 shrink-0">
                    <img :src="'/images/icons/verified-user.png'" alt="" class="h-6 w-6" />
                    <!--Image purement décorative, pas besoin d'alt, bonne pratique-->
                  </span>
                  <span class="text-regular text-texte-primary-dark">Adapté à votre structure</span>
                </span>
                <svg class="h-5 w-5 text-texte-primary-dark lg:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
              </a>
            </li>
            <li>
              <a href="#/informations" class="flex items-center justify-between rounded-2xl bg-form-bg p-3 lg:bg-transparent lg:p-0 lg:rounded-none">
                <span class="flex items-center gap-3 lg:gap-4">
                  <span class="grid h-[51px] w-[51px] place-items-center rounded-full bg-violet-200 shrink-0">
                    <img :src="'/images/icons/favorite.png'" alt="" class="h-6 w-6" />
                    <!--Image purement décorative, pas besoin d'alt, bonne pratique-->
                  </span>
                  <span class="text-regular text-texte-primary-dark">Aucune expertise médicale requise</span>
                </span>
                <svg class="h-5 w-5 text-texte-primary-dark lg:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
              </a>
            </li>
          </ul>
          <div class="mt-8 flex flex-col items-center gap-4 lg:flex-row lg:items-center lg:gap-6 lg:flex-wrap">
            <a href="#prendre-rdv" class="flex w-full justify-center lg:inline-flex lg:w-auto items-center gap-2 rounded-full bg-button-primary px-6 py-2.5 text-regular text-white">Organiser une collecte</a>
            <a href="#/informations" class="text-violet-500 underline underline-offset-2 text-regular">En savoir plus &rarr;</a>
          </div>
        </div>
        <div>
          <div class="rounded-3xl bg-gradient-to-r from-violet-100 to-vert-300 overflow-hidden h-[208px] lg:h-[523px] flex items-end justify-center">
            <img :src="'/images/illustrations/composition.png'" loading="lazy" decoding="async" class="" />
            <!--Image purement décorative, pas besoin d'alt, bonne pratique-->
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Pourquoi accueillir une collecte -->
  <section class="bg-violet-100 pt-10 pb-6">
    <div class="px-4 lg:px-[60px]">
      <h2 class="text-h1 font-semibold pt-4 text-center text-violet-900">Pourquoi accueillir une collecte ?</h2>
      <div class="mx-auto mt-4 h-[4px] w-48 rounded-full bg-vert-300"></div>
      <p class="text-h4 text-violet-900 text-center mt-4">Un geste utile, directement sur votre lieu de travail.</p>


      <div
        ref="pourquoiScroll"
        @scroll.passive="updatePourquoiIndex"
        class="-mx-4 flex overflow-x-auto no-scrollbar snap-x snap-mandatory gap-4 px-4
               lg:mx-0 lg:px-0 lg:grid lg:grid-cols-4 lg:gap-6 lg:overflow-visible"
      >
        <div class="snap-center py-18 shrink-0 w-[calc(100vw-2rem)] lg:w-auto flex flex-col">
          <img :src="'/images/icons/croix.svg'" loading="lazy" decoding="async" class="h-16 w-auto mx-auto" alt="Icône de médicament" />
          <h3 class="text-h3 font-bold text-violet-900 text-center mt-12">Répondre à un besoin réel</h3>
          <p class="text-regular text-violet-900 text-center mt-4">Les produits sanguins sont nécessaires chaque jour pour soigner de nombreux patients. Chaque collecte compte.</p>
        </div>
        <div class="snap-center py-18  shrink-0 w-[calc(100vw-2rem)] lg:w-auto flex flex-col">
          <img :src="'/images/icons/collaborateur.svg'" loading="lazy" decoding="async" class="h-16 w-auto mx-auto" alt="Icône de médicament" />
          <h3 class="text-h3 font-bold text-violet-900 text-center mt-12">Faciliter l'engagement des collaborateurs</h3>
          <p class="text-regular text-violet-900 text-center mt-4">Organiser une collecte directement sur le lieu de travail réduit les contraintes et encourage la participation.</p>
        </div>
        <div class="snap-center py-19  shrink-0 w-[calc(100vw-2rem)] lg:w-auto flex flex-col">
          <img :src="'/images/icons/dynamique.svg'" loading="lazy" decoding="async" class="h-15 w-auto mx-auto" alt="Icône de médicament" />
          <h3 class="text-h3 font-bold text-violet-900 text-center mt-12">Créer une dynamique collective</h3>
          <p class="text-regular text-violet-900 text-center mt-4">Une collecte peut devenir un moment fédérateur autour d'une action commune et porteuse de sens.</p>
        </div>
        <div class="snap-center py-18  shrink-0 w-[calc(100vw-2rem)] lg:w-auto flex flex-col">
          <img :src="'/images/icons/trophe-icon.svg'" loading="lazy" decoding="async" class="h-16 w-auto mx-auto" alt="Icône de médicament" />
          <h3 class="text-h3 font-bold text-violet-900 text-center mt-12">Valoriser l'engagement de l'entreprise</h3>
          <p class="text-regular text-violet-900 text-center mt-4">Accueillir une collecte permet d'inscrire votre démarche sociétale dans une action visible, concrète et positive.</p>
        </div>
      </div>
      <div class="flex justify-center gap-2 mt-2 lg:hidden">
        <span v-for="i in 4" :key="i"
              :class="['h-2 w-2 rounded-full', pourquoiIndex === i - 1 ? 'bg-black' : 'bg-black/30']" />
      </div>
    </div>
  </section>

  <!-- Organisation pensee pour les entreprises -->
  <section class="py-10">
    <div class="px-4 lg:px-[60px]">
      <h2 class="text-h1 font-semibold text-center text-violet-900">Une organisation pensée pour les entreprises</h2>
      <div class="mx-auto mt-2 h-[3px] w-48 rounded-full bg-vert-300"></div>

      <div class="grid lg:grid-cols-2 gap-10 items-center mt-12">
        <div>
          <img :src="'/images/illustrations/nombreforce.png'" loading="lazy" decoding="async" class="w-full max-w-[503px] mx-auto" alt="Des gouttes de sang portent un cœur pour signifier la force du nombre" />
        </div>
        <div>
          <ul class="space-y-0 flex flex-col items-center lg:items-stretch">
            <li class="flex flex-col items-center text-center gap-4 lg:flex-row lg:items-stretch lg:text-left lg:gap-4 max-w-[369px] lg:max-w-none">
              <div class="flex flex-col items-center shrink-0 lg:min-h-[97px]">
                <span class="grid h-[101px] w-[101px] lg:h-[61px] lg:w-[61px] place-items-center rounded-full bg-violet-200 ring-1 ring-white shrink-0">
                  <img :src="'/images/icons/chat.png'" loading="lazy" decoding="async" alt="Icône de discussion" class="h-12 w-12 lg:h-7 lg:w-7" />
                </span>
                <span class="hidden lg:block lg:w-[3px] lg:flex-1 lg:bg-violet-300 lg:rounded-full lg:my-2"></span>
              </div>
              <div>
                <h4 class="hidden lg:block text-h4 font-bold text-texte-primary-dark">On échange avec vous</h4>
                <p class="text-h5 text-texte-primary-dark mt-1">Un interlocuteur dédié du CTS échange avec vous et comprend vos besoins</p>
              </div>
            </li>
            <span class="block h-16 w-[3px] bg-violet-400 mx-auto my-[13px] rounded-full lg:hidden"></span>
            <li class="flex flex-col items-center text-center gap-4 lg:flex-row lg:items-stretch lg:text-left lg:gap-4 max-w-[369px] lg:max-w-none">
              <div class="flex flex-col items-center shrink-0 lg:min-h-[97px]">
                <span class="grid h-[101px] w-[101px] lg:h-[61px] lg:w-[61px] place-items-center rounded-full bg-violet-200 ring-1 ring-white shrink-0">
                  <img :src="'/images/icons/calendar-check.png'" loading="lazy" decoding="async" alt="Icône de calendrier" class="h-12 w-12 lg:h-7 lg:w-7" />
                </span>
                <span class="hidden lg:block lg:w-[3px] lg:flex-1 lg:bg-violet-300 lg:rounded-full lg:my-2"></span>
              </div>
              <div>
                <h4 class="hidden lg:block text-h4 font-bold text-texte-primary-dark">On définit ensemble</h4>
                <p class="text-h5 text-texte-primary-dark mt-1">Nous définissons ensemble les modalités de collecte (date, lieu, format, ...)</p>
              </div>
            </li>
            <span class="block h-16 w-[3px] bg-violet-400 mx-auto my-[13px] rounded-full lg:hidden"></span>
            <li class="flex flex-col items-center text-center gap-4 lg:flex-row lg:items-stretch lg:text-left lg:gap-4 max-w-[369px] lg:max-w-none">
              <div class="flex flex-col items-center shrink-0 lg:min-h-[97px]">
                <span class="grid h-[101px] w-[101px] lg:h-[61px] lg:w-[61px] place-items-center rounded-full bg-violet-200 ring-1 ring-white shrink-0">
                  <img :src="'/images/icons/campaign.png'" loading="lazy" decoding="async" alt="Icône d'haut-parleur" class="h-12 w-12 lg:h-7 lg:w-7" />
                </span>
                <span class="hidden lg:block lg:w-[3px] lg:flex-1 lg:bg-violet-300 lg:rounded-full lg:my-2"></span>
              </div>
              <div>
                <h4 class="hidden lg:block text-h4 font-bold text-texte-primary-dark">On vous accompagne</h4>
                <p class="text-h5 text-texte-primary-dark mt-1">Nous vous fournissons des supports et outils pour informer et mobiliser vos équipes</p>
              </div>
            </li>
            <span class="block h-16 w-[3px] bg-violet-400 mx-auto my-[13px] rounded-full lg:hidden"></span>
            <li class="flex flex-col items-center text-center gap-4 lg:flex-row lg:items-stretch lg:text-left lg:gap-4 max-w-[369px] lg:max-w-none">
              <div class="flex flex-col items-center shrink-0">
                <span class="grid h-[101px] w-[101px] lg:h-[61px] lg:w-[61px] place-items-center rounded-full bg-violet-200 ring-1 ring-white shrink-0">
                  <img :src="'/images/icons/checklist.png'" loading="lazy" decoding="async" alt="Icône de checklist" class="h-12 w-12 lg:h-7 lg:w-7" />
                </span>
              </div>
              <div>
                <h4 class="hidden lg:block text-h4 font-bold text-texte-primary-dark">On s'occupe de tout</h4>
                <p class="text-h5 text-texte-primary-dark mt-1">Le CTS prend l'ensemble de l'organisation et du matériel nécessaire</p>
              </div>
            </li>
          </ul>
          <a href="#/informations#deroulement" class="block w-fit mx-auto lg:mx-0 mt-10 rounded-full bg-white shadow text-violet-900 text-center py-3 px-10 text-regular underline underline-offset-2">En savoir plus<span class="hidden lg:inline"> sur l'organisation d'une collecte</span></a>
        </div>
      </div>
    </div>
  </section>

  <!-- Parlons de votre future collecte -->
  <section id="prendre-rdv" class="py-10">
    <div class="px-4 lg:px-[60px]">
      <div class="rounded-3xl bg-violet-100 p-3 lg:p-14 overflow-hidden">
        <div class="grid lg:grid-cols-2 gap-10 items-center">
          <div class="flex flex-col items-center text-center lg:block lg:text-left min-w-0">
            <img :src="'/images/illustrations/goutte-mascotte.png'" loading="lazy" decoding="async" class="h-32 w-auto mb-6 lg:hidden" alt="Mascotte goutte de sang qui salue" />
            <h2 class="text-h1 font-semibold text-violet-950">
              Parlons de votre <span class="text-violet-500">future collecte</span>
            </h2>
            <div class="mt-8 flex flex-col items-center gap-6 lg:flex-row lg:items-center">
              <img :src="'/images/illustrations/goutte-mascotte.png'" loading="lazy" decoding="async" class="hidden lg:block h-48 w-auto" alt="Mascotte goutte de sang qui salue" />
              <div>
                <h3 class="text-h3 font-bold text-violet-950">Quelques informations suffisent pour démarrer</h3>
                <p class="text-h5 text-violet-950 mt-3">Le CTS vous recontacte ensuite pour organiser une collecte adaptée pour votre entreprise.</p>
              </div>
            </div>
          </div>

          <div id="prendre-rdv-form" class="min-w-0 scroll-mt-24 lg:scroll-mt-28">
            <form @submit.prevent="submit" novalidate class="bg-form-bg rounded-xl p-3 ring-1 ring-violet-900/30">
              <h3 class="text-h3 font-bold text-violet-900 text-center mb-4">Prendre <br class="lg:hidden" />rendez-vous</h3>

              <div class="grid grid-cols-2 gap-3 mb-3">
                <div class="flex flex-col gap-1">
                  <label for="rdv-company" class="font-sans text-small font-medium text-violet-800">Nom de l'entreprise <span class="text-rouge-500">*</span></label>
                  <input id="rdv-company" required v-model="form.company_name" type="text" placeholder="Nom de l'entreprise" class="w-full min-w-0 h-[50px] lg:h-[43px] rounded-lg bg-white px-3 text-small text-texte-primary-dark shadow-[0_0_4px_rgba(0,0,0,0.25)] placeholder:text-texte-primary-dark/40" :class="{ 'ring-1 ring-rouge-500': formErrors.company_name }" />
                </div>
                <div class="flex flex-col gap-1">
                  <label for="rdv-employees" class="font-sans text-small font-medium text-violet-800">Nombre d'employés <span class="text-rouge-500">*</span></label>
                  <input id="rdv-employees" required min="1" v-model="form.employees_count" type="number" placeholder="ex. 1200" class="w-full min-w-0 h-[50px] lg:h-[43px] rounded-lg bg-white px-3 text-small text-texte-primary-dark shadow-[0_0_4px_rgba(0,0,0,0.25)] placeholder:text-texte-primary-dark/40" :class="{ 'ring-1 ring-rouge-500': formErrors.employees_count }" />
                </div>
              </div>

              <div class="flex flex-col gap-1 mb-3">
                <label for="rdv-street" class="font-sans text-small font-medium text-violet-800">Adresse <span class="text-rouge-500">*</span></label>
                <input id="rdv-street" required v-model="form.street" type="text" placeholder="Rue n°" class="w-full min-w-0 h-[50px] lg:h-[43px] rounded-lg bg-white px-3 text-small text-texte-primary-dark shadow-[0_0_4px_rgba(0,0,0,0.25)] placeholder:text-texte-primary-dark/40" :class="{ 'ring-1 ring-rouge-500': formErrors.street }" />
              </div>

              <div class="flex gap-3 mb-3">
                <div class="flex flex-col gap-1">
                  <label for="rdv-postal" class="font-sans text-small font-medium text-violet-800">NPA <span class="text-rouge-500">*</span></label>
                  <input id="rdv-postal" required v-model="form.postal_code" type="text" placeholder="1200" class="w-24 min-w-0 h-[50px] lg:h-[43px] rounded-lg bg-white px-3 text-small text-texte-primary-dark shadow-[0_0_4px_rgba(0,0,0,0.25)] placeholder:text-texte-primary-dark/40" :class="{ 'ring-1 ring-rouge-500': formErrors.postal_code }" />
                </div>
                <div class="flex flex-col gap-1 flex-grow min-w-0">
                  <label for="rdv-city" class="font-sans text-small font-medium text-violet-800">Ville <span class="text-rouge-500">*</span></label>
                  <input id="rdv-city" required v-model="form.city" type="text" placeholder="Genève" class="w-full min-w-0 h-[50px] lg:h-[43px] rounded-lg bg-white px-3 text-small text-texte-primary-dark shadow-[0_0_4px_rgba(0,0,0,0.25)] placeholder:text-texte-primary-dark/40" :class="{ 'ring-1 ring-rouge-500': formErrors.city }" />
                </div>
              </div>

              <div class="flex flex-col gap-1 mb-3">
                <label for="rdv-email" class="font-sans text-small font-medium text-violet-800">Adresse e-mail <span class="text-rouge-500">*</span></label>
                <input id="rdv-email" required v-model="form.email" type="email" placeholder="contact@entreprise.ch" class="w-full min-w-0 h-[50px] lg:h-[43px] rounded-lg bg-white px-3 text-small text-texte-primary-dark shadow-[0_0_4px_rgba(0,0,0,0.25)] placeholder:text-texte-primary-dark/40" :class="{ 'ring-1 ring-rouge-500': formErrors.email }" />
              </div>

              <div class="flex flex-col gap-1 mb-4">
                <label for="rdv-phone" class="font-sans text-small font-medium text-violet-800">Téléphone <span class="text-rouge-500">*</span></label>
                <input id="rdv-phone" required v-model="form.phone" type="tel" placeholder="+41 22 000 00 00" class="w-full min-w-0 h-[50px] lg:h-[43px] rounded-lg bg-white px-3 text-small text-texte-primary-dark shadow-[0_0_4px_rgba(0,0,0,0.25)] placeholder:text-texte-primary-dark/40" :class="{ 'ring-1 ring-rouge-500': formErrors.phone }" />
              </div>

              <div v-if="showPmeMessage" class="mb-4 rounded-lg bg-white p-4 text-small text-texte-primary-dark ring-1 ring-violet-900/30">
                Seul les entreprises de plus de 1000 employés peuvent accueillir une collecte de sang. Si vous êtes une PME vous pouvez vous rendre
                <a href="#/informations#pme" class="underline text-violet-900">sur la page information</a> pour découvrir comment soutenir le don du sang autrement et faire la différence malgré votre taille !
              </div>
              <div v-if="status.type === 'success'" class="mb-4 rounded-lg bg-green-50 p-4 text-small text-green-800 ring-1 ring-green-300">
                {{ status.message }}
              </div>
              <div v-if="aDesErreurs || globalError" class="mb-4 flex items-start gap-3 rounded-xl border border-rouge-500 bg-rouge-500/10 p-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 shrink-0 text-rouge-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <circle cx="12" cy="12" r="10" /><line x1="12" y1="8" x2="12" y2="13" /><line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                <div class="font-sans text-small text-rouge-600">
                  <p class="font-semibold">Merci de corriger les points suivants :</p>
                  <ul class="mt-1 list-disc pl-4">
                    <li v-for="(message, champ) in formErrors" :key="champ">{{ message }}</li>
                    <li v-if="globalError">{{ globalError }}</li>
                  </ul>
                </div>
              </div>

              <p class="mb-4 text-xs text-gray-500">Vos données sont transmises au CTS et utilisées uniquement dans le cadre de l'organisation de votre collecte de sang.</p>
              <button type="submit" :disabled="submitting" class="w-full rounded-full lg:rounded-2xl bg-button-primary py-4 text-regular text-white shadow transition-colors hover:bg-[#410E3F] disabled:opacity-60">
                {{ submitting ? 'Envoi…' : 'Envoyer' }}
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Three info cards -->
  <section class="py-10 pb-20">
    <div class="px-4 lg:px-[60px]">
      <div
        ref="cardsScroll"
        @scroll.passive="updateCardsIndex"
        class="-mx-4 flex overflow-x-auto no-scrollbar snap-x snap-mandatory gap-4 px-4
               lg:mx-0 lg:px-0 lg:grid lg:grid-cols-3 lg:gap-6 lg:overflow-visible"
      >
        <div class="snap-center shrink-0 w-[calc(100vw-2rem)] lg:w-auto bg-violet-50 rounded-3xl p-10 flex flex-col items-center text-center">
          <img :src="'/images/classement/trophee-1.png'" loading="lazy" decoding="async" class="h-52 w-auto" alt="Trophée" />
          <h3 class="text-h3 font-bold text-violet-900 mt-6 min-h-[5rem]">Trophée de la générosité</h3>
          <p class="text-regular text-violet-900 mt-6 flex-1">Découvrez les entreprises reconnues pour leur engagement autour du don du sang</p>
          <a href="#/trophee" class="mt-6 inline-flex items-center justify-center rounded-full px-8 py-2 text-small underline underline-offset-2 w-full lg:w-56 bg-white text-violet-900 lg:bg-button-primary lg:text-beige-50 lg:ring-2 lg:ring-violet-50">En savoir plus</a>
        </div>
        <div class="snap-center shrink-0 w-[calc(100vw-2rem)] lg:w-auto bg-violet-50 rounded-3xl p-10 flex flex-col items-center text-center">
          <img :src="'/images/illustrations/infos.png'" loading="lazy" decoding="async" class="h-52 w-auto" alt="Mascotte goutte de sang qui se pose des questions" />
        <h3 class="text-h3 font-bold text-violet-900 mt-6 min-h-[5rem]">Comment se déroule une collecte ?</h3>
          <p class="text-regular text-violet-900 mt-6 flex-1">Organisation, logistique, communication, déroulement du jour J : retrouvez les informations pratiques pour accueillir une collecte en entreprise</p>
          <a href="#/informations#deroulement" class="mt-6 inline-flex items-center justify-center rounded-full px-8 py-2 text-small underline underline-offset-2 w-full lg:w-56 bg-white text-violet-900 lg:bg-button-primary lg:text-beige-50 lg:ring-2 lg:ring-violet-50">En savoir plus</a>
        </div>
        <div class="snap-center shrink-0 w-[calc(100vw-2rem)] lg:w-auto bg-violet-50 rounded-3xl p-10 flex flex-col items-center text-center">
          <img :src="'/images/classement/label.png'" loading="lazy" decoding="async" class="h-52 w-auto" alt="Label CTS" />
          <h3 class="text-h3 font-bold text-violet-900 mt-6 min-h-[5rem]">Label CTS</h3>
          <p class="text-regular text-violet-900 mt-6 flex-1">Mettre en lumière les entreprises engagées dans la promotion du don du sang</p>
          <a href="#/label" class="mt-6 inline-flex items-center justify-center rounded-full px-8 py-2 text-small underline underline-offset-2 w-full lg:w-56 bg-white text-violet-900 lg:bg-button-primary lg:text-beige-50 lg:ring-2 lg:ring-violet-50">En savoir plus</a>
        </div>
      </div>
      <div class="flex justify-center gap-2 mt-6 lg:hidden">
        <span v-for="i in 3" :key="i"
              :class="['h-2 w-2 rounded-full', cardsIndex === i - 1 ? 'bg-black' : 'bg-black/30']" />
      </div>
    </div>
  </section>
</template>
