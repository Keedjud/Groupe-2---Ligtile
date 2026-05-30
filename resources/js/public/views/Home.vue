<script setup>
import { reactive, ref } from 'vue'

const form = reactive({
  company_name: '', company_size: '',
  street: '', postal_code: '', city: '',
  email: '', phone: '',
})

function submit() {
  // TODO: brancher l'envoi du formulaire au backend
}

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
    <div class="max-w-[1512px] mx-auto px-4 lg:px-[60px]">
      <div class="flex flex-col-reverse gap-8 lg:grid lg:grid-cols-2 lg:gap-10 lg:items-center">
        <div>
          <h1 class="text-h1 font-semibold text-texte-primary-dark leading-tight max-w-[260px] lg:max-w-none">
            Jamais la goutte de trop. Juste la goutte
            <span class="text-violet-500">qu'il faut</span>
          </h1>
          <p class="text-small lg:text-h4 text-texte-primary-dark mt-6">
            Organisez une collecte de sang dans votre entreprise genevoise. Le CTS vous accompagne de A à Z.
          </p>
          <ul class="mt-8 space-y-4">
            <li>
              <a href="#/informations" class="flex items-center justify-between rounded-2xl bg-form-bg p-3 lg:bg-transparent lg:p-0 lg:rounded-none">
                <span class="flex items-center gap-3 lg:gap-4">
                  <span class="grid h-[51px] w-[51px] place-items-center rounded-full bg-violet-200 shrink-0">
                    <img :src="'/images/check.png'" alt="" class="h-6 w-6" />
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
                    <img :src="'/images/verified_user.png'" alt="" class="h-6 w-6" />
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
                    <img :src="'/images/favorite.png'" alt="" class="h-6 w-6" />
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
          <div class="rounded-3xl bg-gradient-to-r from-violet-100 to-vert-300 overflow-hidden h-[208px] lg:h-[523px] relative">
            <img :src="'/images/composition.png'" class="absolute inset-0 h-full w-full object-cover" alt="Composition" />
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Pourquoi accueillir une collecte -->
  <section class="bg-violet-100 py-10">
    <div class="max-w-[1512px] mx-auto px-4 lg:px-[60px]">
      <h2 class="text-h1 font-semibold text-center text-violet-900">Pourquoi accueillir une collecte ?</h2>
      <p class="text-h4 text-violet-900 text-center mt-2">Un geste utile, directement sur votre lieu de travail.</p>
      <div class="mx-auto mt-2 h-[3px] w-48 rounded-full bg-vert-300"></div>

      <div
        ref="pourquoiScroll"
        @scroll.passive="updatePourquoiIndex"
        class="-mx-4 flex overflow-x-auto snap-x snap-mandatory gap-4 px-4 mt-12
               lg:mx-0 lg:px-0 lg:grid lg:grid-cols-4 lg:gap-6 lg:overflow-visible"
      >
        <div class="snap-center shrink-0 w-[calc(100vw-2rem)] lg:w-auto flex flex-col">
          <img :src="'/images/medoc1.png'" class="h-32 w-auto mx-auto" alt="" />
          <h3 class="text-h3 font-bold text-violet-900 text-center mt-4">Répondre à un besoin réel</h3>
          <p class="text-regular text-violet-900 text-center mt-4">Les produits sanguins sont nécessaires chaque jour pour soigner de nombreux patients. Chaque collecte compte.</p>
        </div>
        <div class="snap-center shrink-0 w-[calc(100vw-2rem)] lg:w-auto flex flex-col">
          <img :src="'/images/Medoc2.png'" class="h-32 w-auto mx-auto" alt="" />
          <h3 class="text-h3 font-bold text-violet-900 text-center mt-4">Faciliter l'engagement des collaborateurs</h3>
          <p class="text-regular text-violet-900 text-center mt-4">Organiser une collecte directement sur le lieu de travail réduit les contraintes et encourage la participation.</p>
        </div>
        <div class="snap-center shrink-0 w-[calc(100vw-2rem)] lg:w-auto flex flex-col">
          <img :src="'/images/medoc1.png'" class="h-32 w-auto mx-auto" alt="" />
          <h3 class="text-h3 font-bold text-violet-900 text-center mt-4">Créer une dynamique collective</h3>
          <p class="text-regular text-violet-900 text-center mt-4">Une collecte peut devenir un moment fédérateur autour d'une action commune et porteuse de sens.</p>
        </div>
        <div class="snap-center shrink-0 w-[calc(100vw-2rem)] lg:w-auto flex flex-col">
          <img :src="'/images/Medoc2.png'" class="h-32 w-auto mx-auto" alt="" />
          <h3 class="text-h3 font-bold text-violet-900 text-center mt-4">Valoriser l'engagement de l'entreprise</h3>
          <p class="text-regular text-violet-900 text-center mt-4">Accueillir une collecte permet d'inscrire votre démarche sociétale dans une action visible, concrète et positive.</p>
        </div>
      </div>
      <div class="flex justify-center gap-2 mt-6 lg:hidden">
        <span v-for="i in 4" :key="i"
              :class="['h-2 w-2 rounded-full', pourquoiIndex === i - 1 ? 'bg-black' : 'bg-black/30']" />
      </div>
    </div>
  </section>

  <!-- Organisation pensee pour les entreprises -->
  <section class="py-10">
    <div class="max-w-[1512px] mx-auto px-4 lg:px-[60px]">
      <h2 class="text-h1 font-semibold text-center text-violet-900">Une organisation pensée pour les entreprises</h2>
      <div class="mx-auto mt-2 h-[3px] w-48 rounded-full bg-vert-300"></div>

      <div class="grid lg:grid-cols-2 gap-10 items-center mt-12">
        <div>
          <img :src="'/images/nombreforce.png'" class="w-full max-w-[503px] mx-auto" alt="Le nombre fait la force" />
        </div>
        <div>
          <ul class="space-y-0 flex flex-col items-center lg:items-stretch">
            <li class="flex flex-col items-center text-center gap-4 lg:flex-row lg:items-stretch lg:text-left lg:gap-4 max-w-[369px] lg:max-w-none">
              <div class="flex flex-col items-center shrink-0">
                <span class="grid h-[101px] w-[101px] lg:h-[61px] lg:w-[61px] place-items-center rounded-full bg-violet-200 ring-1 ring-white shrink-0">
                  <img :src="'/images/chat.png'" alt="" class="h-12 w-12 lg:h-7 lg:w-7" />
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
              <div class="flex flex-col items-center shrink-0">
                <span class="grid h-[101px] w-[101px] lg:h-[61px] lg:w-[61px] place-items-center rounded-full bg-violet-200 ring-1 ring-white shrink-0">
                  <img :src="'/images/calendar_check.png'" alt="" class="h-12 w-12 lg:h-7 lg:w-7" />
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
              <div class="flex flex-col items-center shrink-0">
                <span class="grid h-[101px] w-[101px] lg:h-[61px] lg:w-[61px] place-items-center rounded-full bg-violet-200 ring-1 ring-white shrink-0">
                  <img :src="'/images/campaign.png'" alt="" class="h-12 w-12 lg:h-7 lg:w-7" />
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
                  <img :src="'/images/checklist.png'" alt="" class="h-12 w-12 lg:h-7 lg:w-7" />
                </span>
              </div>
              <div>
                <h4 class="hidden lg:block text-h4 font-bold text-texte-primary-dark">On s'occupe de tout</h4>
                <p class="text-h5 text-texte-primary-dark mt-1">Le CTS prend l'ensemble de l'organisation et du matériel nécessaire</p>
              </div>
            </li>
          </ul>
          <a href="#/informations" class="block w-fit mx-auto lg:mx-0 mt-10 rounded-full bg-white shadow text-violet-900 text-center py-3 px-10 text-regular underline underline-offset-2">En savoir plus<span class="hidden lg:inline"> sur l'organisation d'une collecte</span></a>
        </div>
      </div>
    </div>
  </section>

  <!-- Parlons de votre future collecte -->
  <section id="prendre-rdv" class="py-10">
    <div class="max-w-[1512px] mx-auto px-4 lg:px-[60px]">
      <div class="rounded-3xl bg-violet-100 p-3 lg:p-14">
        <div class="grid lg:grid-cols-2 gap-10 items-center">
          <div class="flex flex-col items-center text-center lg:block lg:text-left">
            <img :src="'/images/Goutte_mascotte.png'" class="h-32 w-auto mb-6 lg:hidden" alt="Mascotte goutte" />
            <h2 class="text-h1 font-semibold text-violet-950">
              Parlons de votre <span class="text-violet-500">future collecte</span>
            </h2>
            <div class="mt-8 flex flex-col items-center gap-6 lg:flex-row lg:items-center">
              <img :src="'/images/Goutte_mascotte.png'" class="hidden lg:block h-48 w-auto" alt="Mascotte goutte" />
              <div>
                <h3 class="text-h3 font-bold text-violet-950">Quelques informations suffisent pour démarrer</h3>
                <p class="text-h5 text-violet-950 mt-3">Le CTS vous recontacte ensuite pour organiser une collecte adaptée pour votre entreprise.</p>
              </div>
            </div>
          </div>

          <div>
            <form @submit.prevent="submit" class="bg-form-bg rounded-xl p-3 ring-1 ring-violet-900/30">
              <h3 class="text-h3 font-bold text-violet-900 text-center mb-4">Prendre <br class="lg:hidden" />rendez-vous</h3>

              <div class="grid grid-cols-2 gap-3 mb-3">
                <input v-model="form.company_name" type="text" placeholder="Nom de l'entreprise" class="w-full min-w-0 h-[50px] lg:h-[43px] rounded-[40px] lg:rounded-lg bg-white px-5 lg:px-3 text-small text-texte-primary-dark shadow-[0_0_4px_rgba(0,0,0,0.25)] placeholder:text-texte-primary-dark/70" />
                <select v-model="form.company_size" class="w-full min-w-0 h-[50px] lg:h-[43px] rounded-[40px] lg:rounded-lg bg-white pl-5 pr-8 lg:pl-3 lg:pr-8 text-small text-texte-primary-dark shadow-[0_0_4px_rgba(0,0,0,0.25)] appearance-none bg-no-repeat bg-[right_0.75rem_center] bg-[length:0.75rem] bg-[url('data:image/svg+xml;utf8,<svg%20xmlns=%22http://www.w3.org/2000/svg%22%20viewBox=%220%200%2020%2020%22%20fill=%22%23361136%22><path%20fill-rule=%22evenodd%22%20d=%22M5.23%207.21a.75.75%200%20011.06.02L10%2011.06l3.71-3.83a.75.75%200%20111.08%201.04l-4.25%204.39a.75.75%200%2001-1.08%200L5.21%208.27a.75.75%200%2001.02-1.06z%22%20clip-rule=%22evenodd%22/></svg>')]">
                  <option value="" disabled>Taille entreprise</option>
                  <option value="1-10">1-10</option>
                  <option value="11-50">11-50</option>
                  <option value="51-200">51-200</option>
                  <option value="201-500">201-500</option>
                  <option value="500+">500+</option>
                </select>
              </div>

              <input v-model="form.street" type="text" placeholder="Adresse" class="w-full h-[50px] lg:h-[43px] rounded-[40px] lg:rounded-lg bg-white px-5 lg:px-3 text-small text-texte-primary-dark shadow-[0_0_4px_rgba(0,0,0,0.25)] placeholder:text-texte-primary-dark/70 mb-3" />

              <div class="flex gap-3 mb-3">
                <input v-model="form.postal_code" type="text" placeholder="NPA" class="w-24 h-[50px] lg:h-[43px] rounded-[40px] lg:rounded-lg bg-white px-5 lg:px-3 text-small text-texte-primary-dark shadow-[0_0_4px_rgba(0,0,0,0.25)] placeholder:text-texte-primary-dark/70" />
                <input v-model="form.city" type="text" placeholder="Ville" class="flex-grow h-[50px] lg:h-[43px] rounded-[40px] lg:rounded-lg bg-white px-5 lg:px-3 text-small text-texte-primary-dark shadow-[0_0_4px_rgba(0,0,0,0.25)] placeholder:text-texte-primary-dark/70" />
              </div>

              <input v-model="form.email" type="email" placeholder="Adresse e-mail" class="w-full h-[50px] lg:h-[43px] rounded-[40px] lg:rounded-lg bg-white px-5 lg:px-3 text-small text-texte-primary-dark shadow-[0_0_4px_rgba(0,0,0,0.25)] placeholder:text-texte-primary-dark/70 mb-3" />

              <input v-model="form.phone" type="tel" placeholder="Téléphone" class="w-full h-[50px] lg:h-[43px] rounded-[40px] lg:rounded-lg bg-white px-5 lg:px-3 text-small text-texte-primary-dark shadow-[0_0_4px_rgba(0,0,0,0.25)] placeholder:text-texte-primary-dark/70 mb-4" />

              <button type="submit" class="w-full rounded-full lg:rounded-2xl bg-button-primary py-4 text-regular text-white shadow">
                Envoyer
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Three info cards -->
  <section class="py-10 pb-20">
    <div class="max-w-[1512px] mx-auto px-4 lg:px-[60px]">
      <div
        ref="cardsScroll"
        @scroll.passive="updateCardsIndex"
        class="-mx-4 flex overflow-x-auto snap-x snap-mandatory gap-4 px-4
               lg:mx-0 lg:px-0 lg:grid lg:grid-cols-3 lg:gap-6 lg:overflow-visible"
      >
        <div class="snap-center shrink-0 w-[calc(100vw-2rem)] lg:w-auto bg-violet-50 rounded-3xl p-10 flex flex-col items-center text-center gap-6">
          <img :src="'/images/trophey.png'" class="h-52 w-auto" alt="Trophée" />
          <h3 class="text-h3 font-bold text-violet-900">Trophée de la générosité</h3>
          <p class="text-regular text-violet-900">Découvrez les entreprises reconnues pour leur engagement autour du don du sang</p>
          <a href="#/trophee" class="inline-flex items-center justify-center rounded-full px-8 py-2 text-small underline underline-offset-2 w-full lg:w-56 bg-white text-violet-900 lg:bg-button-primary lg:text-beige-50 lg:ring-2 lg:ring-violet-50">En savoir plus</a>
        </div>
        <div class="snap-center shrink-0 w-[calc(100vw-2rem)] lg:w-auto bg-violet-50 rounded-3xl p-10 flex flex-col items-center text-center gap-6">
          <img :src="'/images/infos.png'" class="h-52 w-auto" alt="Informations" />
          <h3 class="text-h3 font-bold text-violet-900">Comment se déroule une collecte ?</h3>
          <p class="text-regular text-violet-900">Organisation, logistique, communication, déroulement du jour J : retrouvez les informations pratiques pour accueillir une collecte en entreprise</p>
          <a href="#/informations" class="inline-flex items-center justify-center rounded-full px-8 py-2 text-small underline underline-offset-2 w-full lg:w-56 bg-white text-violet-900 lg:bg-button-primary lg:text-beige-50 lg:ring-2 lg:ring-violet-50">En savoir plus</a>
        </div>
        <div class="snap-center shrink-0 w-[calc(100vw-2rem)] lg:w-auto bg-violet-50 rounded-3xl p-10 flex flex-col items-center text-center gap-6">
          <img :src="'/images/label.png'" class="h-52 w-auto" alt="Label CTS" />
          <h3 class="text-h3 font-bold text-violet-900">Label CTS</h3>
          <p class="text-regular text-violet-900">Mettre en lumière les entreprises engagées dans la promotion du don du sang</p>
          <a href="#/label" class="inline-flex items-center justify-center rounded-full px-8 py-2 text-small underline underline-offset-2 w-full lg:w-56 bg-white text-violet-900 lg:bg-button-primary lg:text-beige-50 lg:ring-2 lg:ring-violet-50">En savoir plus</a>
        </div>
      </div>
      <div class="flex justify-center gap-2 mt-6 lg:hidden">
        <span v-for="i in 3" :key="i"
              :class="['h-2 w-2 rounded-full', cardsIndex === i - 1 ? 'bg-black' : 'bg-black/30']" />
      </div>
    </div>
  </section>
</template>
