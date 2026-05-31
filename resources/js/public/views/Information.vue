<script setup>
import { ref } from 'vue'

const form = ref({
  company: '',
  email: '',
  message: '',
})
const submitted = ref(false)
const submitting = ref(false)
const formError = ref(null)

function handleSubmit() {
  formError.value = null

  if (!form.value.company || !form.value.email || !form.value.message) {
    formError.value = 'Veuillez remplir tous les champs.'
    return
  }

  submitting.value = true
  submitted.value = true
  submitting.value = false
  // TODO: backend — ajouter try/catch avec formError.value = ...
}
</script>

<template>
  <div>
    <!-- ===== Section 1 : Hero — Pourquoi donner son sang ? ===== -->
    <section class="flex items-center px-4 py-10 lg:px-[60px] lg:py-[70px] min-h-[385px]">
      <div class="mx-auto flex w-full max-w-[1512px] flex-col items-center gap-8 md:flex-row md:gap-16 lg:gap-[249px]">
        <!-- Image mobile -->
        <img
          :src="'/images/lungs.png'"
          alt="Pourquoi donner son sang ?"
          class="w-[280px] h-auto object-contain shrink-0 md:hidden"
        />
        <!-- Image desktop -->
        <img
          :src="'/images/hi.png'"
          alt="Pourquoi donner son sang ?"
          class="hidden md:block w-[180px] lg:w-[202px] h-auto object-contain shrink-0"
        />
        <div class="flex flex-1 flex-col gap-6">
          <h1 class="font-sans text-h1 font-semibold text-texte-primary-dark">
            Pourquoi donner son sang ?
          </h1>
          <div>
            <p class="font-sans text-h4 font-normal text-texte-primary-dark">
              Un geste simple. Un impact qui dure.
            </p>
            <p class="mt-4 font-sans text-regular font-normal text-texte-primary-dark">
              Chaque jour en Suisse, des patients ont besoin de transfusions après un accident, une opération ou un traitement contre le cancer. Le sang ne se fabrique pas et ne se conserve pas indéfiniment.
            </p>
            <p class="mt-4 font-sans text-regular font-normal text-texte-primary-dark">
              Un seul don peut sauver jusqu'à 3 vies en 45 minutes. Pourtant, seulement 3% de la population suisse donne régulièrement. Chaque donneur compte.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== Section 2 : Comment se déroule une collecte ? (+ prévoir sur mobile) ===== -->
    <section class="bg-violet-100 px-4 py-12 lg:px-[60px] lg:py-[60px]">
      <div class="mx-auto flex max-w-[1512px] flex-col gap-8 lg:flex-row lg:gap-[87px]">
        <!-- Colonne gauche -->
        <div class="flex flex-1 flex-col gap-[54px] lg:max-w-[737px]">
          <!-- Badge -->
          <div class="inline-flex items-center gap-5 rounded-[40px] bg-white px-6 py-3 w-fit">
            <img :src="'/images/building.png'" class="h-[29px] w-[30px] shrink-0 object-contain" />
            <span class="font-sans text-h3 font-bold text-texte-primary-dark">
              Pour les entreprises de plus de 1 000 collaborateurs
            </span>
          </div>

          <div class="flex flex-col gap-6">
            <h2 class="font-sans text-h1 font-semibold text-violet-900">
              Comment se déroule une collecte ?
            </h2>
            <div>
              <p class="font-sans text-h4 font-normal text-violet-900">
                Une collecte organisée pour vous, avec vous.
              </p>
              <p class="mt-4 font-sans text-regular font-normal text-violet-900">
                Le CTS prend en charge l'intégralité de l'organisation médicale et logistique dans vos locaux. En collaboration avec votre entreprise, nous définissons ensemble une date, des plages horaires et un espace dédié à la collecte.
              </p>
              <p class="mt-4 font-sans text-regular font-normal text-violet-900">
                Vos collaborateurs s'inscrivent via un lien personnalisé. Le jour J, une équipe médicale est sur place pour tout installer.
              </p>
              <p class="mt-4 font-sans text-regular italic font-normal text-violet-900">
                À noter : il faut compter au moins 3 mois de délai pour planifier une collecte.
              </p>
            </div>
          </div>

          <!-- Mobile seulement : Ce que l'entreprise doit prévoir -->
          <div class="flex flex-col gap-6 md:hidden">
            <h2 class="font-sans text-h1 font-semibold text-violet-900">
              Ce que l'entreprise doit prévoir
            </h2>
            <div>
              <p class="font-sans text-h4 font-normal text-violet-900">
                Pour les collectes organisées dans vos locaux
              </p>
              <p class="mt-4 font-sans text-regular font-normal text-violet-900">
                Un espace d'au moins 100 m² avec bon éclairage, toilettes à proximité et accès de plain-pied pour le transport du matériel. Un parking à proximité pour les véhicules du CTS. Une personne de contact disponible sur site toute la journée. Une collation pour les donneurs et l'équipe médicale. Les frais sont remboursés par le CTS au prix coûtant.
              </p>
            </div>
          </div>
        </div>

        <img
          :src="'/images/composition_petites_gouttes.png'"
          alt="Collecte de sang"
          class="w-full lg:w-[700px] h-auto object-contain shrink-0"
        />
      </div>
    </section>

    <!-- ===== Section 3 : Ce que l'entreprise doit prévoir (desktop uniquement) ===== -->
    <section class="hidden md:block px-4 py-12 lg:px-[60px] lg:py-[60px]">
      <div class="mx-auto flex max-w-[1512px] flex-col gap-8 md:flex-row md:gap-[87px]">
        <div class="md:w-[619px] shrink-0">
          <h2 class="font-sans text-h1 font-semibold text-violet-950">
            Ce que l'entreprise doit prévoir
          </h2>
        </div>
        <div class="flex-1">
          <p class="font-sans text-h4 font-normal text-texte-primary-dark">
            Pour les collectes organisées dans vos locaux
          </p>
          <ul class="mt-4 flex list-inside list-disc flex-col gap-2 font-sans text-regular font-normal text-texte-primary-dark">
            <li>Un espace d'au moins 100 m² avec bon éclairage, toilettes à proximité et accès de plain-pied pour le transport du matériel.</li>
            <li>Un parking à proximité pour les véhicules du CTS.</li>
            <li>Une personne de contact disponible sur site toute la journée.</li>
            <li>Une collation pour les donneurs et l'équipe médicale.</li>
            <li>Les frais sont remboursés par le CTS au prix coûtant.</li>
          </ul>
        </div>
      </div>
    </section>

    <!-- ===== Section 4 : Ce que le CTS fourni ===== -->
    <section class="px-4 py-6 lg:px-[60px] lg:py-6 mt-8 lg:mt-12">
      <div class="mx-auto flex max-w-[1512px] flex-col gap-8 lg:flex-row lg:gap-[87px]">
        <!-- Colonne gauche -->
        <div class="flex flex-1 flex-col gap-9 lg:max-w-[619px]">
          <!-- Badge -->
          <div class="inline-flex items-center gap-5 rounded-[40px] bg-white px-6 py-3 w-fit">
            <img :src="'/images/building.png'" class="h-[29px] w-[30px] shrink-0 object-contain" />
            <span class="font-sans text-h3 font-bold text-texte-primary-dark">
              Pour toutes les entreprises
            </span>
          </div>

          <div class="flex flex-col gap-[54px]">
            <h2 class="font-sans text-h1 font-semibold text-texte-primary-dark">
              Ce que le CTS fourni
            </h2>
            <div>
              <p class="font-sans text-h4 font-normal text-texte-primary-dark">
                Vous n'êtes jamais seuls dans l'organisation.
              </p>
              <p class="mt-4 font-sans text-regular font-normal text-texte-primary-dark">
                Le CTS met à votre disposition tous les éléments nécessaires : un cahier des charges complet, la charte graphique officielle, ainsi qu'un kit de communication prêt à l'emploi pour annoncer la collecte à vos équipes. Un lien d'inscription privé est créé pour vos collaborateurs, avec un accès organisateur pour suivre les réservations en temps réel.
              </p>
              <p class="mt-4 font-sans text-regular font-normal text-texte-primary-dark">
                De votre côté, un espace adapté et la motivation de vos collaborateurs suffisent.
              </p>
            </div>
          </div>
        </div>

        <img
          :src="'/images/fournis.png'"
          alt="Ce que le CTS fourni"
          class="w-full lg:max-w-[500px] lg:max-h-[380px] h-auto rounded-[100px] object-contain"
        />
      </div>
    </section>

    <!-- ===== Section 5 : Moins de 1 000 collaborateurs + Formulaire ===== -->
    <section class="bg-violet-100 px-4 py-12 lg:px-[60px] lg:py-[57px] mt-16 lg:mt-24">
      <div class="mx-auto flex max-w-[1512px] flex-col items-center gap-8 lg:flex-row lg:gap-28">
        <!-- Colonne gauche : texte -->
        <div class="flex flex-col gap-6 lg:max-w-[636px]">
          <h2 class="font-sans text-h1 font-semibold text-violet-950">
            Votre entreprise compte
            <span class="text-violet-500">moins de <br />1 000 collaborateurs ?</span>
          </h2>
          <p class="font-sans text-h4 font-normal text-violet-950">
            Pas de problème !
          </p>
          <p class="font-sans text-regular font-normal text-violet-950">
            Vous pouvez vous rendre en groupe dans un centre de transfusion sanguine ou lors d'une collecte existante près de chez vous.
          </p>
          <p class="font-sans text-regular font-normal text-violet-950">
            Contactez-nous pour organiser votre venue !
          </p>
        </div>

        <!-- Colonne droite : formulaire -->
        <div class="relative flex w-full max-w-[490px] flex-col items-center gap-5 rounded-lg border-2 border-violet-900/30 bg-form-bg px-8 py-8 shadow-[0_4px_4px_rgba(0,0,0,0.10)]">
          <h3 class="text-center font-sans text-h3 font-bold text-violet-900">
            Une question ? <br />Organisons ensemble votre collecte.
          </h3>

          <!-- Formulaire ou confirmation -->
          <template v-if="!submitted">
            <p v-if="formError" class="w-full max-w-[450px] rounded-lg bg-rouge-100 px-4 py-2 text-center font-sans text-small text-rouge-600">
              {{ formError }}
            </p>
            <input
              v-model="form.company"
              type="text"
              placeholder="Nom de l'entreprise"
              class="h-[43px] w-full max-w-[450px] rounded-lg bg-white px-4 font-sans text-small text-black shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none placeholder:text-[#B8B8B8]"
            />
            <input
              v-model="form.email"
              type="email"
              placeholder="Adresse Mail"
              class="h-[43px] w-full max-w-[450px] rounded-lg bg-white px-4 font-sans text-small text-black shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none placeholder:text-[#B8B8B8]"
            />
            <textarea
              v-model="form.message"
              placeholder="Message"
              rows="4"
              class="h-[148px] w-full max-w-[450px] resize-none rounded-lg bg-white px-4 py-3 font-sans text-small text-black shadow-[0_0_4px_rgba(0,0,0,0.25)] outline-none placeholder:text-[#B8B8B8]"
            ></textarea>
            <button
              @click="handleSubmit"
              :disabled="submitting"
              class="h-[45px] w-full max-w-[450px] rounded-[40px] bg-button-primary font-sans text-regular text-texte-primary-light shadow-[0_4px_4px_rgba(0,0,0,0.25)] transition-colors hover:bg-violet-800 disabled:opacity-60"
            >
              {{ submitting ? 'Envoi...' : 'Envoyer' }}
            </button>
          </template>

          <!-- Confirmation -->
          <div v-else class="flex flex-col items-center gap-4 py-8 text-center">
            <div class="h-12 w-12 rounded-full bg-vert-200 flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-vert-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <p class="font-sans text-h5 font-semibold text-violet-900">
              Merci ! Votre message a bien été envoyé.
            </p>
            <p class="font-sans text-small text-texte-primary-dark">
              Nous vous recontacterons dans les plus brefs délais.
            </p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
